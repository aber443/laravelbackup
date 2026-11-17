<?php
namespace App\Services;

use App\Models\Child;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GlucoseAnalysisService
{
    /**
     * تحليل قراءات السكر حسب نوع السكري ونوع القراءة.
     *
     * الطفل اختياري مؤقتًا للحفاظ على توافق الاستدعاءات القديمة.
     */
    public function analyze(
        Collection $readings,
        ?Child $child = null
    ): array {

        // ترتيب القراءات حسب التاريخ والوقت.
        $readings = $readings->sortBy(
            fn ($reading) => Carbon::parse(
                Carbon::parse($reading->reading_date)->format('Y-m-d')
                . ' '
                . $reading->reading_time
            )->timestamp
        )->values();

        // تحديد نوع السكري.
        $diabetesType = $child?->diabetes_type;

        // =====================================
        // تصنيف القراءات
        // =====================================

        $classifiedReadings = $readings->map(
            function ($reading) use ($diabetesType) {

                $value = (float) $reading->glucose_level;
                $readingType = $reading->reading_type;

                $min = null;
                $max = null;

                // النوع الأول.
                if ($diabetesType === 'النوع الأول') {

                    if (
                        $readingType === 'قبل الوجبة' ||
                        $readingType === 'صائم'
                    ) {
                        $min = 70;
                        $max = 144;

                    } elseif ($readingType === 'بعد الوجبة') {

                        $min = 70;
                        $max = 180;
                    }
                }

                // النوع الثاني.
                elseif ($diabetesType === 'النوع الثاني') {

                    if ($readingType === 'صائم') {

                        $min = 70;
                        $max = 110;

                    } elseif ($readingType === 'بعد الوجبة') {

                        $min = 70;
                        $max = 140;
                    }

                    // قراءة قبل الوجبة غير الصيام:
                    // لا يوجد لها نطاق مستقل معتمد هنا.
                }

                // إذا لم يتوفر نطاق مرجعي.
                if ($min === null || $max === null) {

                    $classification = 'unclassified';

                } elseif ($value < $min) {

                    $classification = 'low';

                } elseif ($value > $max) {

                    $classification = 'high';

                } else {

                    $classification = 'in_range';
                }

                return [
                    'glucose_reading_id' =>
                        $reading->glucose_reading_id,

                    'reading_type' => $readingType,

                    'glucose_level' => $value,

                    'reading_date' => Carbon::parse(
                        $reading->reading_date
                    )->format('Y-m-d'),

                    'reading_time' => $reading->reading_time,

                    'classification' => $classification,

                    'reference_min' => $min,

                    'reference_max' => $max,
                ];
            }
        )->values();

        // =====================================
        // حساب الإحصائيات
        // =====================================

        $values = $readings->pluck('glucose_level')
            ->map(fn ($value) => (float) $value);

        $count = $values->count();

        $low = $classifiedReadings
            ->where('classification', 'low')
            ->count();

        $inRange = $classifiedReadings
            ->where('classification', 'in_range')
            ->count();

        $high = $classifiedReadings
            ->where('classification', 'high')
            ->count();

        $unclassified = $classifiedReadings
            ->where('classification', 'unclassified')
            ->count();

        // القراءات التي لديها نطاق مرجعي فقط.
        $classifiedCount = $low + $inRange + $high;

        $percent = function (int $value) use ($classifiedCount) {

            return $classifiedCount === 0
                ? 0
                : round(
                    ($value / $classifiedCount) * 100,
                    2
                );
        };

        // فئة فرعية من المرتفع.
        $markedHigh = $classifiedReadings
            ->filter(
                fn ($reading) =>
                    $reading['classification'] === 'high' &&
                    $reading['glucose_level'] >= 250
            )
            ->count();

        // =====================================
        // الاتجاه اليومي
        // =====================================

        $dailyTrend = $this->buildTrend(
            $readings,
            'Y-m-d'
        );

        // بداية الأسبوع يوم الاثنين.
        $weeklyTrend = $this->buildTrend(
            $readings,
            'o-\WW'
        );

        // الاتجاه الشهري.
        $monthlyTrend = $this->buildTrend(
            $readings,
            'Y-m'
        );

        // =====================================
        // الإحصائيات حسب نوع القراءة
        // =====================================

        $byType = $classifiedReadings
            ->groupBy('reading_type')
            ->map(function ($group, $type) {

                $eligible = $group->filter(
                    fn ($reading) =>
                        $reading['classification'] !== 'unclassified'
                );

                $eligibleCount = $eligible->count();

                $countByClassification = [
                    'low' => $eligible
                        ->where('classification', 'low')
                        ->count(),

                    'in_range' => $eligible
                        ->where('classification', 'in_range')
                        ->count(),

                    'high' => $eligible
                        ->where('classification', 'high')
                        ->count(),
                ];

                return [
                    'type' => $type,

                    'count' => $group->count(),

                    'average' => round(
                        $group->avg('glucose_level'),
                        2
                    ),

                    'classified_count' => $eligibleCount,

                    'unclassified_count' =>
                        $group->count() - $eligibleCount,

                    'classification' => [
                        'low' => [
                            'count' => $countByClassification['low'],
                            'percentage' => $eligibleCount === 0
                                ? 0
                                : round(
                                    ($countByClassification['low'] / $eligibleCount) * 100,
                                    2
                                ),
                        ],

                        'in_range' => [
                            'count' => $countByClassification['in_range'],
                            'percentage' => $eligibleCount === 0
                                ? 0
                                : round(
                                    ($countByClassification['in_range'] / $eligibleCount) * 100,
                                    2
                                ),
                        ],

                        'high' => [
                            'count' => $countByClassification['high'],
                            'percentage' => $eligibleCount === 0
                                ? 0
                                : round(
                                    ($countByClassification['high'] / $eligibleCount) * 100,
                                    2
                                ),
                        ],
                    ],
                ];
            })
            ->values()
            ->all();

        // =====================================
        // النتيجة النهائية
        // =====================================

        return [

            'unit' => 'mg/dL',

            'diabetes_type' => $diabetesType,

            'count' => $count,

            'average' => $count === 0
                ? null
                : round($values->avg(), 2),

            'highest' => $count === 0
                ? null
                : $values->max(),

            'lowest' => $count === 0
                ? null
                : $values->min(),

            'daily_trend' => $dailyTrend,

            'weekly_trend' => $weeklyTrend,

            'monthly_trend' => $monthlyTrend,

            'by_reading_type' => $byType,

            'classified_count' => $classifiedCount,

            'unclassified_count' => $unclassified,

            // البيانات الفردية لاستخدامها في الرسم لاحقًا.
            'readings' => $classifiedReadings->all(),

            'classification' => [

                'low' => [
                    'count' => $low,
                    'percentage' => $percent($low),
                ],

                'in_range' => [
                    'count' => $inRange,
                    'percentage' => $percent($inRange),
                ],

                'high' => [
                    'count' => $high,
                    'percentage' => $percent($high),
                ],

                // فئة فرعية من المرتفع.
                'marked_high' => [
                    'count' => $markedHigh,
                    'percentage' => $percent($markedHigh),
                ],

                // لا تدخل في حساب النسب الثلاث.
                'unclassified' => [
                    'count' => $unclassified,
                    'percentage' => $count === 0
                        ? 0
                        : round(
                            ($unclassified / $count) * 100,
                            2
                        ),
                ],
            ],
        ];
    }

    /**
     * بناء الاتجاه اليومي والأسبوعي والشهري.
     */
    private function buildTrend(
        Collection $readings,
        string $format
    ): array {

        return $readings
            ->groupBy(function ($reading) use ($format) {

                return Carbon::parse(
                    $reading->reading_date
                )->format($format);
            })
            ->map(function ($group, $period) {

                return [
                    'period' => $period,

                    'average' => round(
                        $group->avg('glucose_level'),
                        2
                    ),

                    'count' => $group->count(),
                ];
            })
            ->values()
            ->all();
    }
}