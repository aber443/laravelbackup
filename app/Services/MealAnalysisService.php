<?php

namespace App\Services;

use App\Models\Child;
use App\Models\GlucoseReading;
use App\Models\Meal;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MealAnalysisService
{
    // قراءة ما قبل الوجبة: خلال 60 دقيقة قبل الوجبة.
    private const BEFORE_MEAL_WINDOW = 60;

    // قراءة ما بعد الوجبة: من 60 إلى 120 دقيقة.
    private const WINDOW_START = 60;
    private const REFERENCE_WINDOW_END = 120;

    // هامش السماح حتى 135 دقيقة.
    private const WINDOW_END = 135;

    // أنواع القراءات كما تُحفظ في قاعدة البيانات.
    private const BEFORE_MEAL_READING_TYPE = 'قبل الوجبة';
    private const AFTER_MEAL_READING_TYPE = 'بعد الوجبة';
    private const REGISTERED_MEAL_STATUS = 'الوجبة مسجلة في التطبيق';

    // حدود تصنيف قراءات بعد الوجبة المعتمدة للمشروع.
    private const LOW_GLUCOSE_THRESHOLD = 70;
    private const HIGH_GLUCOSE_THRESHOLD = 180;

    /**
     * تحليل العلاقة بين الوجبات وقراءات السكر.
     * قراءة قبل الوجبة تُربط زمنيًا ونوعيًا،
     * والمقارنة الزمنية تستخدم أقرب قراءة بعد الوجبة.
     * إحصائيات مستوى السكر تشمل كل قراءة بعد الوجبة مرتبطة بوجبة مسجلة.
     */
    public function analyze(
        Child $child,
        string $startDate,
        string $endDate
    ): array {

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        if ($start->greaterThan($end)) {
            throw new \InvalidArgumentException(
                'تاريخ البداية يجب أن يسبق تاريخ النهاية.'
            );
        }

        // =====================================
        // 1. جلب وجبات الطفل خلال الفترة
        // =====================================

        $meals = Meal::where(
            'child_id',
            $child->child_id
        )
            ->whereBetween('meal_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->orderBy('meal_date')
            ->orderBy('meal_time')
            ->get();

        // =====================================
        // 2. جلب جميع قراءات السكر للطفل
        // =====================================

        // نجلب القراءات في نطاق أوسع بيوم قبل وبعد الفترة
        // حتى لا نفقد قراءة قريبة من وجبة عند حدود الفترة.
        // يجري التحقق من reading_type و meal_id عند الربط.

        $readings = GlucoseReading::where(
            'child_id',
            $child->child_id
        )
            ->whereBetween('reading_date', [
                $start->copy()->subDay()->toDateString(),
                $end->copy()->addDay()->toDateString(),
            ])
            ->orderBy('reading_date')
            ->orderBy('reading_time')
            ->get();

        // تجهيز التاريخ والوقت لكل قراءة.
        $readings = $readings->map(function ($reading) {

            $reading->analysis_datetime =
                $this->combineDateAndTime(
                    $reading->reading_date,
                    $reading->reading_time
                );

            return $reading;
        });

        // جميع قراءات «بعد الوجبة» المرتبطة بوجبات الفترة المحددة.
        // هذا المصدر مستقل عن اختيار أقرب قراءة للمقارنة الزمنية.
        $mealLookup = $meals->keyBy(fn ($meal) => (int) $meal->meal_id);

        $linkedAfterMealReadings = $readings
            ->filter(function ($reading) use ($mealLookup, $start, $end) {
                if (
                    $reading->reading_type !== self::AFTER_MEAL_READING_TYPE ||
                    $reading->meal_status !== self::REGISTERED_MEAL_STATUS ||
                    $reading->meal_id === null ||
                    !$mealLookup->has((int) $reading->meal_id)
                ) {
                    return false;
                }

                // التقرير يحسب القراءات التي سُجلت داخل الفترة المختارة.
                return $reading->analysis_datetime->betweenIncluded($start, $end);
            })
            ->unique('glucose_reading_id')
            ->map(function ($reading) use ($mealLookup) {
                $meal = $mealLookup->get((int) $reading->meal_id);
                $mealDateTime = $this->combineDateAndTime(
                    $meal->meal_date,
                    $meal->meal_time
                );

                $formatted = $this->formatReading(
                    $reading,
                    $meal,
                    $mealDateTime,
                    'after_meal'
                );

                $formatted['glucose_category'] = $this->glucoseCategory(
                    (float) $reading->glucose_level
                );
                $formatted['classification_label'] = match ($formatted['glucose_category']) {
                    'low' => 'منخفض',
                    'in_range' => 'ضمن النطاق',
                    'high' => 'مرتفع',
                };

                return $formatted;
            })
            ->sortBy('reading_datetime')
            ->values();

        $lowReadings = $linkedAfterMealReadings
            ->where('glucose_category', 'low')->values();
        $inRangeReadings = $linkedAfterMealReadings
            ->where('glucose_category', 'in_range')->values();
        $highReadings = $linkedAfterMealReadings
            ->where('glucose_category', 'high')->values();

        $linkedCount = $linkedAfterMealReadings->count();
        $percentage = fn (int $count): float => $linkedCount === 0
            ? 0.0
            : round($count * 100 / $linkedCount, 2);

        // توزيع الارتفاعات حسب تاريخ القراءة، دون استنتاج طبي آلي.
        $highReadingsByDay = $highReadings
            ->groupBy(fn ($reading) => substr($reading['reading_datetime'], 0, 10))
            ->map(fn ($items, $date) => [
                'date' => $date,
                'count' => $items->count(),
            ])
            ->values()
            ->all();

        // =====================================
        // 3. ربط القراءات بالوجبات زمنيًا
        // =====================================

        // منع استخدام القراءة نفسها لأكثر من وجبة.
        $usedReadingIds = [];

        $classifiedReadings = collect();

        $pairedComparisons = collect();

        foreach ($meals as $meal) {

            $mealDateTime = $this->combineDateAndTime(
                $meal->meal_date,
                $meal->meal_time
            );

            // ---------------------------------
            // البحث عن قراءة قبل الوجبة
            // ---------------------------------

            $beforeReading = $this->findNearestReading(
                $readings,
                $mealDateTime,
                -self::BEFORE_MEAL_WINDOW,
                0,
                self::BEFORE_MEAL_READING_TYPE,
                $usedReadingIds
            );

            // ---------------------------------
            // البحث عن قراءة بعد الوجبة
            // ---------------------------------

            $afterReading = $this->findNearestReading(
                $readings,
                $mealDateTime,
                self::WINDOW_START,
                self::WINDOW_END,
                self::AFTER_MEAL_READING_TYPE,
                $usedReadingIds,
                (int) $meal->meal_id
            );

            // ---------------------------------
            // تجهيز قراءة ما قبل الوجبة
            // ---------------------------------

            if ($beforeReading !== null) {

                $usedReadingIds[] =
                    $beforeReading->glucose_reading_id;

                $classifiedReadings->push(
                    $this->formatReading(
                        $beforeReading,
                        $meal,
                        $mealDateTime,
                        'before_meal'
                    )
                );
            }

            // ---------------------------------
            // تجهيز قراءة ما بعد الوجبة
            // ---------------------------------

            if ($afterReading !== null) {

                $usedReadingIds[] =
                    $afterReading->glucose_reading_id;

                $minutesAfterMeal =
                    $mealDateTime->diffInSeconds(
                        $afterReading->analysis_datetime,
                        false
                    ) / 60;

                $timingCategory =
                    $minutesAfterMeal <= self::REFERENCE_WINDOW_END
                        ? 'reference_window'
                        : 'grace_period';

                $classificationLabel =
                    $timingCategory === 'reference_window'
                        ? 'ضمن النافذة المرجعية (من ساعة إلى ساعتين)'
                        : 'ضمن هامش السماح (بعد ساعتين وحتى ساعتين و15 دقيقة)';

                $classifiedReadings->push(
                    $this->formatReading(
                        $afterReading,
                        $meal,
                        $mealDateTime,
                        'within_window',
                        $timingCategory,
                        $classificationLabel
                    )
                );
            }

            // ---------------------------------
            // المقارنة بين القراءتين
            // ---------------------------------

            if (
                $beforeReading !== null &&
                $afterReading !== null
            ) {

                $beforeValue = (float)
                    $beforeReading->glucose_level;

                $afterValue = (float)
                    $afterReading->glucose_level;

                $difference = round(
                    $afterValue - $beforeValue,
                    2
                );

                $minutesBeforeMeal =
                    $beforeReading->analysis_datetime
                        ->diffInSeconds(
                            $mealDateTime,
                            false
                        ) / 60;

                $minutesAfterMeal =
                    $mealDateTime->diffInSeconds(
                        $afterReading->analysis_datetime,
                        false
                    ) / 60;

                $pairedComparisons->push([

                    'meal_id' => $meal->meal_id,

                    'meal_type' => $meal->meal_type,

                    'meal_date' => Carbon::parse(
                        $meal->meal_date
                    )->toDateString(),

                    'meal_time' => (string) $meal->meal_time,

                    'before_reading' => [
                        'glucose_reading_id' =>
                            $beforeReading->glucose_reading_id,

                        'glucose_level' => $beforeValue,

                        'reading_datetime' =>
                            $beforeReading->analysis_datetime
                                ->toDateTimeString(),

                        'minutes_before_meal' =>
                            round($minutesBeforeMeal, 2),
                    ],

                    'after_reading' => [
                        'glucose_reading_id' =>
                            $afterReading->glucose_reading_id,

                        'glucose_level' => $afterValue,

                        'reading_datetime' =>
                            $afterReading->analysis_datetime
                                ->toDateTimeString(),

                        'minutes_after_meal' =>
                            round($minutesAfterMeal, 2),
                    ],

                    'glucose_difference' => $difference,

                    'change_direction' =>
                        $difference > 0
                            ? 'increase'
                            : (
                                $difference < 0
                                    ? 'decrease'
                                    : 'unchanged'
                            ),

                    'unit' => $afterReading->unit,
                ]);
            }
        }

        // =====================================
        // 4. ترتيب النتائج
        // =====================================

        $classifiedReadings = $classifiedReadings
            ->sortBy('reading_datetime')
            ->values();

        $withinWindow = $classifiedReadings
            ->where('classification', 'within_window')
            ->values();

        $beforeMealReadings = $classifiedReadings
            ->where('classification', 'before_meal')
            ->values();

        $referenceWindowReadings = $withinWindow
            ->where('timing_category', 'reference_window')
            ->values();

        $gracePeriodReadings = $withinWindow
            ->where('timing_category', 'grace_period')
            ->values();

        // =====================================
        // 5. تجهيز تفاصيل كل وجبة
        // =====================================

        $mealDetails = $meals->map(
            function ($meal) use (
                $classifiedReadings,
                $pairedComparisons,
                $linkedAfterMealReadings
            ) {

                $mealReadings = $classifiedReadings
                    ->where('meal_id', $meal->meal_id)
                    ->values();

                $before = $mealReadings
                    ->firstWhere(
                        'classification',
                        'before_meal'
                    );

                $after = $mealReadings
                    ->firstWhere(
                        'classification',
                        'within_window'
                    );

                $comparison = $pairedComparisons
                    ->firstWhere(
                        'meal_id',
                        $meal->meal_id
                    );

                return [

                    'meal_id' => $meal->meal_id,

                    'meal_type' => $meal->meal_type,

                    'meal_date' => Carbon::parse(
                        $meal->meal_date
                    )->toDateString(),

                    'meal_time' => (string) $meal->meal_time,

                    // فك ترميز مكونات الوجبة حتى تصل بأسماء المجموعات والأطعمة.
                    'ingredients' => $this->decodeIngredients(
                        $meal->ingredients
                    ),

                    'linked_readings_count' =>
                        $mealReadings->count(),

                    'before_meal_reading' => $before,

                    'after_meal_reading' => $after,

                    'comparison' => $comparison,

                    'has_complete_comparison' =>
                        $comparison !== null,

                    'within_window_count' =>
                        $after !== null ? 1 : 0,

                    'reference_window_count' =>
                        $after !== null &&
                        $after['timing_category'] === 'reference_window'
                            ? 1 : 0,

                    'grace_period_count' =>
                        $after !== null &&
                        $after['timing_category'] === 'grace_period'
                            ? 1 : 0,

                    'outside_window_count' => 0,

                    // جميع قراءات بعد الوجبة المرتبطة، بغض النظر عن نافذة المقارنة.
                    'after_meal_glucose_readings' => $linkedAfterMealReadings
                        ->where('meal_id', (int) $meal->meal_id)->values()->all(),
                    'after_meal_glucose_count' => $linkedAfterMealReadings
                        ->where('meal_id', (int) $meal->meal_id)->count(),
                ];
            }
        )->values();

        // =====================================
        // 6. عدد الوجبات بحسب نوعها
        // =====================================

        $mealsByType = $meals
            ->groupBy('meal_type')
            ->map(function ($group, $type) {

                return [
                    'meal_type' => $type,
                    'count' => $group->count(),
                ];

            })
            ->values()
            ->all();

        // =====================================
        // 7. تحليل النتائج بحسب نوع الوجبة
        // =====================================

        $patternsByMealType = $this->analyzeMealTypes(
            $meals,
            $pairedComparisons
        );

        // =====================================
        // 8. تجهيز ملاحظات التحليل
        // =====================================

        $notes = [];

        if ($meals->isEmpty()) {

            $notes[] =
                'لا توجد وجبات مسجلة خلال الفترة المحددة.';
        }

        if ($pairedComparisons->isEmpty()) {

            $notes[] =
                'لا توجد وجبات تتوفر لها قراءتا ما قبل الوجبة وما بعدها ضمن النوافذ الزمنية المحددة.';
        }

        if ($beforeMealReadings->isEmpty()) {

            $notes[] =
                'لا توجد قراءات قبل الوجبة ضمن الساعة السابقة للوجبات.';
        }

        if ($withinWindow->isEmpty()) {

            $notes[] =
                'لا توجد قراءات بعد الوجبة ضمن نافذة التحليل المحددة.';
        }

        $notes[] =
            'تم ربط قراءة ما قبل الوجبة حسب نوع القراءة والتوقيت، وربط قراءة ما بعد الوجبة حسب نوع القراءة ومعرّف الوجبة meal_id والتوقيت.';

        $notes[] =
            'قراءة ما قبل الوجبة يجب أن تقع خلال 60 دقيقة قبل تسجيل الوجبة.';

        $notes[] =
            'قراءة ما بعد الوجبة يجب أن تقع من 60 إلى 135 دقيقة بعد تسجيل الوجبة.';

        $notes[] =
            'القراءات بعد 120 وحتى 135 دقيقة تدخل ضمن هامش السماح التصميمي.';

        $notes[] =
            'القراءات التي لا تطابق النوافذ الزمنية لا تدخل في المقارنة، وتبقى ضمن سجل قراءات السكر العام.';

        $notes[] =
            'المقارنة الزمنية توضح التغير بين القراءتين ولا تثبت أن الوجبة وحدها سببت التغير.';

        $notes[] =
            'تصنيف مستوى السكر يحسب جميع قراءات بعد الوجبة المرتبطة بوجبة مسجلة داخل فترة التقرير، مستقلاً عن نافذة المقارنة الزمنية.';

        $notes[] =
            'تصنيف السكر: أقل من 70 منخفض، من 70 إلى 180 ضمن النطاق، وأكثر من 180 مرتفع (mg/dL). قرار تعديل العلاج للطبيب فقط.';

        // =====================================
        // 9. النتيجة النهائية
        // =====================================

        return [

            'child_id' => $child->child_id,

            'period' => [
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
            ],

            'analysis_window' => [

                'before_meal_minutes' =>
                    self::BEFORE_MEAL_WINDOW,

                'start_minutes' =>
                    self::WINDOW_START,

                'reference_end_minutes' =>
                    self::REFERENCE_WINDOW_END,

                'grace_period_end_minutes' =>
                    self::WINDOW_END,

                'end_minutes' =>
                    self::WINDOW_END,

                'linking_method' => 'reading_type_meal_id_and_time',

                'medical_approval' => false,
            ],

            'summary' => [

                'meals_count' => $meals->count(),

                // إحصائيات الدائرة: جميع قراءات بعد الوجبة المرتبطة.
                'after_meal_glucose_count' => $linkedCount,
                'low_glucose_count' => $lowReadings->count(),
                'in_range_glucose_count' => $inRangeReadings->count(),
                'high_glucose_count' => $highReadings->count(),
                'low_glucose_percentage' => $percentage($lowReadings->count()),
                'in_range_glucose_percentage' => $percentage($inRangeReadings->count()),
                'high_glucose_percentage' => $percentage($highReadings->count()),
                'days_with_high_readings_count' => count($highReadingsByDay),
                'high_readings_by_day' => $highReadingsByDay,
                'glucose_thresholds' => [
                    'low_below' => self::LOW_GLUCOSE_THRESHOLD,
                    'in_range_from' => self::LOW_GLUCOSE_THRESHOLD,
                    'in_range_to' => self::HIGH_GLUCOSE_THRESHOLD,
                    'high_above' => self::HIGH_GLUCOSE_THRESHOLD,
                    'unit' => 'mg/dL',
                ],

                // حقل قديم يستهلكه التقرير الحالي؛ أصبح يشمل كل القراءات المرتبطة.
                'linked_after_meal_readings_count' =>
                    $linkedCount,

                'before_meal_readings_count' =>
                    $beforeMealReadings->count(),

                'within_window_count' =>
                    $withinWindow->count(),

                'reference_window_count' =>
                    $referenceWindowReadings->count(),

                'grace_period_count' =>
                    $gracePeriodReadings->count(),

                'within_window_average' =>
                    $this->averageGlucose($withinWindow),

                'outside_window_count' => 0,

                'early_count' => 0,

                'late_count' => 0,

                'before_meal_count' =>
                    $beforeMealReadings->count(),

                'complete_comparisons_count' =>
                    $pairedComparisons->count(),

                'average_glucose_difference' =>
                    $this->averageDifference(
                        $pairedComparisons
                    ),

                'increased_comparisons_count' =>
                    $pairedComparisons
                        ->where(
                            'change_direction',
                            'increase'
                        )
                        ->count(),

                'decreased_comparisons_count' =>
                    $pairedComparisons
                        ->where(
                            'change_direction',
                            'decrease'
                        )
                        ->count(),
            ],

            'meals_by_type' => $mealsByType,

            'meal_details' => $mealDetails->all(),

            'paired_before_after' =>
                $pairedComparisons->all(),

            'comparison_available' =>
                $pairedComparisons->isNotEmpty(),

            'after_meal_glucose_readings' => $linkedAfterMealReadings->all(),
            'low_glucose_readings' => $lowReadings->all(),
            'in_range_glucose_readings' => $inRangeReadings->all(),
            'high_glucose_readings' => $highReadings->all(),

            'within_window_readings' =>
                $withinWindow->all(),

            'before_meal_readings' =>
                $beforeMealReadings->all(),

            'outside_window_readings' => [],

            'patterns_by_meal_type' =>
                $patternsByMealType,

            'notes' => $notes,
        ];
    }

    // =====================================
    // فك ترميز مكونات الوجبة
    // =====================================

    /**
     * تحويل ingredients من JSON إلى مصفوفة قابلة للعرض.
     * مثال:
     * {"البروتينات":["سمك","لحم"]}
     */
    private function glucoseCategory(float $glucose): string
    {
        if ($glucose < self::LOW_GLUCOSE_THRESHOLD) {
            return 'low';
        }

        if ($glucose > self::HIGH_GLUCOSE_THRESHOLD) {
            return 'high';
        }

        return 'in_range';
    }

    private function decodeIngredients($ingredients): array
    {
        // في حال كان الحقل محولًا مسبقًا إلى مصفوفة.
        if (is_array($ingredients)) {
            return $ingredients;
        }

        // لا توجد مكونات مسجلة.
        if ($ingredients === null || $ingredients === '') {
            return [];
        }

        // فك ترميز JSON المخزن في قاعدة البيانات.
        if (is_string($ingredients)) {
            $decoded = json_decode($ingredients, true);

            if (
                json_last_error() === JSON_ERROR_NONE &&
                is_array($decoded)
            ) {
                return $decoded;
            }
        }

        // تجنب إيقاف التقرير إذا كانت قيمة الحقل غير صالحة.
        return [];
    }

    // =====================================
    // البحث عن أقرب قراءة مناسبة للوجبة
    // =====================================

    private function findNearestReading(
        Collection $readings,
        Carbon $mealDateTime,
        int $minimumMinutes,
        int $maximumMinutes,
        string $requiredReadingType,
        array $usedReadingIds,
        ?int $requiredMealId = null
    ) {

        return $readings
            ->filter(function ($reading) use (
                $mealDateTime,
                $minimumMinutes,
                $maximumMinutes,
                $requiredReadingType,
                $usedReadingIds,
                $requiredMealId
            ) {

                // منع استخدام القراءة نفسها لأكثر من وجبة.
                if (
                    in_array(
                        $reading->glucose_reading_id,
                        $usedReadingIds
                    )
                ) {
                    return false;
                }

                // لا نستخدم قراءة من نوع آخر، خصوصًا قراءات الصائم.
                if ($reading->reading_type !== $requiredReadingType) {
                    return false;
                }

                // قراءة ما بعد الوجبة يجب أن تكون مرتبطة بالوجبة نفسها.
                // قراءة ما قبل الوجبة لا تتطلب meal_id.
                if ($requiredMealId !== null) {
                    if (
                        (int) $reading->meal_id !== $requiredMealId ||
                        $reading->meal_status !== self::REGISTERED_MEAL_STATUS
                    ) {
                        return false;
                    }
                }

                // الفرق بالدقائق:
                // السالب = قبل الوجبة.
                // الموجب = بعد الوجبة.

                $differenceMinutes =
                    $mealDateTime->diffInSeconds(
                        $reading->analysis_datetime,
                        false
                    ) / 60;

                return $differenceMinutes >= $minimumMinutes
                    && $differenceMinutes <= $maximumMinutes;
            })
            ->sortBy(function ($reading) use ($mealDateTime) {

                // اختيار أقرب قراءة زمنيًا للوجبة.

                return abs(
                    $mealDateTime->diffInSeconds(
                        $reading->analysis_datetime,
                        false
                    )
                );
            })
            ->first();
    }

    // =====================================
    // تجهيز بيانات القراءة
    // =====================================

    private function formatReading(
        $reading,
        $meal,
        Carbon $mealDateTime,
        string $classification,
        ?string $timingCategory = null,
        ?string $classificationLabel = null
    ): array {

        $minutesDifference =
            $mealDateTime->diffInSeconds(
                $reading->analysis_datetime,
                false
            ) / 60;

        if ($classification === 'before_meal') {

            $classificationLabel =
                'قراءة قبل الوجبة خلال الساعة السابقة';

            $timingCategory = 'before_meal';

        } elseif ($classification === 'within_window') {

            $classificationLabel ??=
                'قراءة بعد الوجبة ضمن النافذة الزمنية';

        }

        return [

            'glucose_reading_id' =>
                $reading->glucose_reading_id,

            // معرّف الوجبة المرتبطة بالقراءة.
            'meal_id' => (int) $meal->meal_id,

            'meal_type' => $meal->meal_type,

            'glucose_level' =>
                (float) $reading->glucose_level,

            'reading_type' => $reading->reading_type,

            'unit' => $reading->unit,

            'meal_datetime' =>
                $mealDateTime->toDateTimeString(),

            'reading_datetime' =>
                $reading->analysis_datetime->toDateTimeString(),

            'minutes_after_meal' =>
                round($minutesDifference, 2),

            'minutes_before_meal' =>
                $minutesDifference < 0
                    ? round(abs($minutesDifference), 2)
                    : null,

            'classification' => $classification,

            'classification_label' =>
                $classificationLabel,

            'timing_category' =>
                $timingCategory,

            'delay_minutes' => null,
        ];
    }

    // =====================================
    // دمج التاريخ والوقت
    // =====================================

    private function combineDateAndTime(
        $date,
        $time
    ): Carbon {

        $dateString = Carbon::parse($date)->toDateString();

        return Carbon::parse(
            $dateString . ' ' . (string) $time
        );
    }

    // =====================================
    // حساب متوسط السكر
    // =====================================

    private function averageGlucose(
        Collection $readings
    ): ?float {

        if ($readings->isEmpty()) {
            return null;
        }

        return round(
            $readings->avg(
                fn ($reading) =>
                    (float) $reading['glucose_level']
            ),
            2
        );
    }

    // =====================================
    // حساب متوسط الفرق بين القراءتين
    // =====================================

    private function averageDifference(
        Collection $comparisons
    ): ?float {

        if ($comparisons->isEmpty()) {
            return null;
        }

        return round(
            $comparisons->avg(
                'glucose_difference'
            ),
            2
        );
    }

    // =====================================
    // تحليل النتائج بحسب نوع الوجبة
    // =====================================

    private function analyzeMealTypes(
        Collection $meals,
        Collection $comparisons
    ): array {

        return $meals
            ->groupBy('meal_type')
            ->map(function ($group, $type) use ($comparisons) {

                $mealIds = $group->pluck('meal_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $typeComparisons = $comparisons
                    ->filter(
                        fn ($comparison) =>
                            in_array(
                                (int) $comparison['meal_id'],
                                $mealIds,
                                true
                            )
                    )
                    ->values();

                $differences = $typeComparisons
                    ->pluck('glucose_difference');

                return [

                    'meal_type' => $type,

                    'meals_count' => $group->count(),

                    'complete_comparisons_count' =>
                        $typeComparisons->count(),

                    'average_before_meal' =>
                        $typeComparisons->isEmpty()
                            ? null
                            : round(
                                $typeComparisons->avg(
                                    'before_reading.glucose_level'
                                ),
                                2
                            ),

                    'average_after_meal' =>
                        $typeComparisons->isEmpty()
                            ? null
                            : round(
                                $typeComparisons->avg(
                                    'after_reading.glucose_level'
                                ),
                                2
                            ),

                    'average_glucose_difference' =>
                        $differences->isEmpty()
                            ? null
                            : round($differences->avg(), 2),

                    'highest_glucose_difference' =>
                        $differences->isEmpty()
                            ? null
                            : round($differences->max(), 2),

                    'lowest_glucose_difference' =>
                        $differences->isEmpty()
                            ? null
                            : round($differences->min(), 2),

                    'increased_count' =>
                        $typeComparisons
                            ->where(
                                'change_direction',
                                'increase'
                            )
                            ->count(),

                    'decreased_count' =>
                        $typeComparisons
                            ->where(
                                'change_direction',
                                'decrease'
                            )
                            ->count(),
                ];
            })
            ->values()
            ->all();
    }
}