<?php
namespace App\Services;

use App\Models\Child;
use App\Models\GlucoseReading;
use App\Models\Meal;
use App\Models\Medication;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HealthAnalysisService
{
    public function analyze(
        Child $child,
        string $startDate,
        string $endDate
    ): array {

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($start->greaterThan($end)) {
            throw new \InvalidArgumentException(
                'تاريخ البداية يجب أن يسبق تاريخ النهاية.'
            );
        }

        // =====================================
        // جلب قراءات السكر
        // =====================================

        $readings = GlucoseReading::where(
            'child_id',
            $child->child_id
        )
            ->whereBetween('reading_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->orderBy('reading_date')
            ->orderBy('reading_time')
            ->get();

        // =====================================
        // جلب وجبات الطفل
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
        // جلب أدوية الطفل
        // =====================================

        $medications = Medication::where(
            'child_id',
            $child->child_id
        )
            ->whereDate(
                'start_date',
                '<=',
                $end->toDateString()
            )
            ->get();

        // =====================================
        // تنفيذ تحليل الوجبات مرة واحدة
        // =====================================

        $mealAnalysis = app(
            MealAnalysisService::class
        )->analyze(
            $child,
            $start->toDateString(),
            $end->toDateString()
        );

        // =====================================
        // تجهيز توافق نتائج التحليل القديمة
        // =====================================

        $mealGlucose = $this->buildMealGlucoseResult(
            $mealAnalysis
        );

        // =====================================
        // التحليل الصحي الشامل
        // =====================================

        return [

            'child_id' => $child->child_id,

            'period' => [
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
            ],

            // =====================================
            // تحليل السكر - عمل يمنى
            // =====================================

            'glucose' => app(
                GlucoseAnalysisService::class
            )->analyze(
                $readings,
                $child
            ),

            // =====================================
            // تحليل الوجبات الأساسي
            // =====================================

            'meals' => $this->analyzeMeals($meals),

            // =====================================
            // نتائج تحليل السكر المرتبطة بالوجبات
            // مع الحفاظ على المفاتيح القديمة
            // =====================================

            'meal_glucose' => $mealGlucose,

            // =====================================
            // التحليل التفصيلي للعلاقة بين الوجبات
            // وقراءات السكر
            // =====================================

            'meal_analysis' => $mealAnalysis,

            // =====================================
            // تحليل الأدوية - محفوظ دون تغيير
            // =====================================

            'medications' => $this->analyzeMedications(
                $medications,
                $start,
                $end
            ),

            // =====================================
            // الملاحظات العامة
            // =====================================

            'notes' => $this->buildNotes(
                $readings,
                $meals,
                $medications
            ),
        ];
    }

    // =====================================
    // دالة تحليل السكر القديمة
    // محفوظة دون حذف
    // =====================================

    private function analyzeGlucose(
        Collection $readings
    ): array {

        $values = $readings->pluck('glucose_level')
            ->map(fn ($value) => (float) $value);

        $dailyTrend = $readings
            ->groupBy(
                fn ($reading) =>
                    Carbon::parse(
                        $reading->reading_date
                    )->toDateString()
            )
            ->map(function ($group, $date) {

                return [
                    'date' => $date,

                    'average' => round(
                        $group->avg('glucose_level'),
                        2
                    ),

                    'count' => $group->count(),
                ];
            })
            ->values()
            ->all();

        $byType = $readings
            ->groupBy('reading_type')
            ->map(function ($group, $type) {

                return [
                    'type' => $type,
                    'count' => $group->count(),

                    'average' => round(
                        $group->avg('glucose_level'),
                        2
                    ),
                ];
            })
            ->values()
            ->all();

        return [
            'unit' => 'mg/dL',

            'count' => $readings->count(),

            'average' => $values->isEmpty()
                ? null
                : round($values->avg(), 2),

            'highest' => $values->isEmpty()
                ? null
                : $values->max(),

            'lowest' => $values->isEmpty()
                ? null
                : $values->min(),

            'daily_trend' => $dailyTrend,

            'by_reading_type' => $byType,

            'classification' => null,
        ];
    }

    // =====================================
    // تحليل الوجبات
    // =====================================

    private function analyzeMeals(
        Collection $meals
    ): array {

        $byType = $meals
            ->groupBy('meal_type')
            ->map(function ($group, $type) {

                return [
                    'meal_type' => $type,
                    'count' => $group->count(),
                ];
            })
            ->values()
            ->all();

        return [
            'count' => $meals->count(),
            'by_type' => $byType,
        ];
    }

    // =====================================
    // تجهيز نتيجة تحليل الوجبات والسكر
    // مع الحفاظ على توافق الاستجابة القديمة
    // =====================================

    private function buildMealGlucoseResult(
        array $mealAnalysis
    ): array {

        $summary = $mealAnalysis['summary'] ?? [];

        $patterns = collect(
            $mealAnalysis['patterns_by_meal_type'] ?? []
        );

        $byMealType = $patterns
            ->map(function ($pattern) {

                return [

                    'meal_type' =>
                        $pattern['meal_type'],

                    'after_meal_count' =>
                        $pattern['complete_comparisons_count'],

                    'after_meal_average' =>
                        $pattern['average_after_meal'],

                    'before_meal_count' =>
                        $pattern['complete_comparisons_count'],

                    'before_meal_average' =>
                        $pattern['average_before_meal'],

                    'average_glucose_difference' =>
                        $pattern['average_glucose_difference'],

                    'increased_count' =>
                        $pattern['increased_count'],

                    'decreased_count' =>
                        $pattern['decreased_count'],
                ];
            })
            ->values()
            ->all();

        return [

            // المفاتيح القديمة
            'linked_after_meal_count' =>
                $summary['within_window_count'] ?? 0,

            'by_meal_type' => $byMealType,

            // نتائج المقارنة الجديدة
            'paired_before_after' =>
                $mealAnalysis['paired_before_after'] ?? [],

            'comparison_available' =>
                $mealAnalysis['comparison_available'] ?? false,

            'complete_comparisons_count' =>
                $summary['complete_comparisons_count'] ?? 0,

            'average_glucose_difference' =>
                $summary['average_glucose_difference'] ?? null,

            'increased_comparisons_count' =>
                $summary['increased_comparisons_count'] ?? 0,

            'decreased_comparisons_count' =>
                $summary['decreased_comparisons_count'] ?? 0,

            'linking_method' => 'time_proximity',
        ];
    }

    // =====================================
    // تحليل الأدوية
    // =====================================

    private function analyzeMedications(
        Collection $medications,
        Carbon $start,
        Carbon $end
    ): array {

        $items = $medications->map(
            function ($medication) use ($start, $end) {

                $frequency = match (
                    $medication->frequency
                ) {
                    'مرة يوميًا' => 1,
                    'مرتان يوميًا' => 2,
                    '3 مرات يوميًا' => 3,
                    default => null,
                };

                $medicationStart = Carbon::parse(
                    $medication->start_date
                )->startOfDay();

                $effectiveStart = $medicationStart->greaterThan(
                    $start
                )
                    ? $medicationStart
                    : $start->copy();

                $days = $effectiveStart->greaterThan($end)
                    ? 0
                    : (int) $effectiveStart->diffInDays($end) + 1;

                return [

                    'medication_id' =>
                        $medication->medication_id,

                    'name' =>
                        $medication->medication_name,

                    'insulin_type' =>
                        $medication->insulin_type,

                    'dosage' =>
                        (float) $medication->dosage,

                    'dosage_unit' =>
                        $medication->dosage_unit,

                    'frequency' =>
                        $medication->frequency,

                    'times_per_day' =>
                        $frequency,

                    'reminder_times' =>
                        $medication->reminder_times,

                    'start_date' =>
                        $medicationStart->toDateString(),

                    'days_in_period' =>
                        $days,

                    'scheduled_occurrences' =>
                        $frequency === null
                            ? null
                            : $frequency * $days,
                ];
            }
        )->values();

        return [

            'medication_count' =>
                $items->count(),

            'scheduled_occurrences_total' =>
                $items->contains(
                    fn ($item) =>
                        $item['scheduled_occurrences'] === null
                )
                    ? null
                    : $items->sum('scheduled_occurrences'),

            'items' => $items->all(),
        ];
    }

    // =====================================
    // الملاحظات العامة
    // =====================================

    private function buildNotes(
        Collection $readings,
        Collection $meals,
        Collection $medications
    ): array {

        $notes = [];

        if ($readings->isEmpty()) {
            $notes[] =
                'لا توجد قراءات سكر مسجلة خلال الفترة.';
        }

        if ($meals->isEmpty()) {
            $notes[] =
                'لا توجد وجبات مسجلة خلال الفترة.';
        }

        if ($medications->isEmpty()) {
            $notes[] =
                'لا توجد أدوية مسجلة لهذه الفترة.';
        }

        $notes[] =
            'تتم مقارنة قراءات ما قبل الوجبة وما بعدها اعتمادًا على التقارب الزمني.';

        $notes[] =
            'هذه النتائج تلخص السجلات المتاحة وتحتاج مراجعة الطبيب.';

        return $notes;
    }
}