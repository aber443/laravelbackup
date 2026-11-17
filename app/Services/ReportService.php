<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    private const SECTIONS = [
        'users' => [
            'title' => 'المستخدمون',
            'table' => 'users',
        ],

        'children' => [
            'title' => 'الأطفال',
            'table' => 'children',
        ],

        'glucose' => [
            'title' => 'قراءات السكر',
            'table' => 'glucose_readings',
        ],

        'meals' => [
            'title' => 'الوجبات',
            'table' => 'meals',
        ],

        'activities' => [
            'title' => 'الأنشطة',
            'table' => 'activities',
        ],

        'medications' => [
            'title' => 'الأدوية',
            'table' => 'medications',
        ],

        'exams' => [
            'title' => 'الفحوصات الطبية',
            'table' => 'medical_exams',
        ],

        'health_insights' => [
            'title' => 'النصائح الصحية',
            'table' => 'health_insights',
        ],
    ];

    public function sections(): array
    {
        return collect(self::SECTIONS)
            ->mapWithKeys(fn ($section, $key) => [
                $key => $section['title'],
            ])
            ->all();
    }

    public function generate(
        array $selectedSections,
        string $period = '30days',
        ?string $from = null,
        ?string $to = null
    ): array {
        $dates = $this->resolveDates($period, $from, $to);

        $start = $dates['start'];
        $end = $dates['end'];

        $days = (int) $start->diffInDays($end) + 1;

        $previousEnd = $start->copy()->subDay();
        $previousStart = $previousEnd->copy()->subDays($days - 1);

        $selectedSections = array_values(
            array_intersect(
                array_keys(self::SECTIONS),
                $selectedSections
            )
        );

        $results = [];

        foreach ($selectedSections as $key) {
            $section = self::SECTIONS[$key];
            $table = $section['table'];

            $current = $this->countRecords($table, $start, $end);

            $previous = $this->countRecords(
                $table,
                $previousStart,
                $previousEnd
            );

            $results[$key] = [
                'title' => $section['title'],
                'current' => $current,
                'previous' => $previous,
                'change' => $this->calculateChange(
                    $current,
                    $previous
                ),
                'chart' => $this->chartData(
                    $table,
                    $start,
                    $end
                ),
                'details' => $this->sectionDetails(
                    $key,
                    $start,
                    $end
                ),
            ];
        }

        return [
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
                'previous_start' => $previousStart->format('Y-m-d'),
                'previous_end' => $previousEnd->format('Y-m-d'),
            ],
            'sections' => $results,
        ];
    }

    private function resolveDates(
        string $period,
        ?string $from,
        ?string $to
    ): array {
        $today = Carbon::today();

        switch ($period) {
            case 'today':
                $start = $today->copy();
                $end = $today->copy();
                break;

            case '7days':
                $start = $today->copy()->subDays(6);
                $end = $today->copy();
                break;

            case '3months':
                $start = $today->copy()->subMonthsNoOverflow(3);
                $end = $today->copy();
                break;

            case '6months':
                $start = $today->copy()->subMonthsNoOverflow(6);
                $end = $today->copy();
                break;

            case 'year':
                $start = $today->copy()->subYearNoOverflow();
                $end = $today->copy();
                break;

            case 'custom':
                if (!$from || !$to) {
                    throw new \InvalidArgumentException(
                        'يجب تحديد تاريخ البداية والنهاية.'
                    );
                }

                $start = Carbon::parse($from)->startOfDay();
                $end = Carbon::parse($to)->startOfDay();

                if ($start->greaterThan($end)) {
                    throw new \InvalidArgumentException(
                        'تاريخ البداية يجب أن يسبق تاريخ النهاية.'
                    );
                }

                break;

            case '30days':
            default:
                $start = $today->copy()->subDays(29);
                $end = $today->copy();
                break;
        }

        return compact('start', 'end');
    }

    private function baseQuery(string $table)
    {
        $query = DB::table($table);

        if ($table === 'users') {
            $query->where('role', 'parent');
        }

        return $query;
    }

    private function countRecords(
        string $table,
        Carbon $start,
        Carbon $end
    ): int {
        return $this->baseQuery($table)
            ->where(
                'created_at',
                '>=',
                $start->copy()->startOfDay()
            )
            ->where(
                'created_at',
                '<',
                $end->copy()->addDay()->startOfDay()
            )
            ->count();
    }

    private function calculateChange(
        int $current,
        int $previous
    ): ?float {
        if ($previous === 0) {
            return null;
        }

        return round(
            (($current - $previous) / $previous) * 100,
            1
        );
    }

    private function chartData(
        string $table,
        Carbon $start,
        Carbon $end
    ): array {
        $records = $this->baseQuery($table)
            ->selectRaw(
                'DATE(created_at) as day, COUNT(*) as total'
            )
            ->where(
                'created_at',
                '>=',
                $start->copy()->startOfDay()
            )
            ->where(
                'created_at',
                '<',
                $end->copy()->addDay()->startOfDay()
            )
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day')
            ->get()
            ->pluck('total', 'day');

        $labels = [];
        $values = [];

        $cursor = $start->copy();

        while ($cursor->lessThanOrEqualTo($end)) {
            $date = $cursor->format('Y-m-d');

            $labels[] = $date;
            $values[] = (int) ($records[$date] ?? 0);

            $cursor->addDay();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function sectionDetails(
        string $section,
        Carbon $start,
        Carbon $end
    ): array {
        $details = [];

        if ($section === 'users') {
            $details['الحسابات المفعّلة حاليًا'] =
                DB::table('users')
                    ->where('role', 'parent')
                    ->where('is_active', true)
                    ->count();

            $details['الحسابات المعطّلة حاليًا'] =
                DB::table('users')
                    ->where('role', 'parent')
                    ->where('is_active', false)
                    ->count();
        }

        if ($section === 'children') {
            $details['إجمالي الأطفال المسجلين حاليًا'] =
                DB::table('children')->count();
        }

        if (in_array($section, [
            'glucose',
            'meals',
            'activities',
            'medications',
            'exams',
            'health_insights',
        ], true)) {
            $table = self::SECTIONS[$section]['table'];

            $details['الأطفال المرتبطون بالسجلات'] =
                DB::table($table)
                    ->where(
                        'created_at',
                        '>=',
                        $start->copy()->startOfDay()
                    )
                    ->where(
                        'created_at',
                        '<',
                        $end->copy()->addDay()->startOfDay()
                    )
                    ->distinct()
                    ->count('child_id');

            $details['أولياء الأمور المرتبطون بالسجلات'] =
                DB::table($table)
                    ->join(
                        'children',
                        "$table.child_id",
                        '=',
                        'children.child_id'
                    )
                    ->join(
                        'users',
                        'children.user_id',
                        '=',
                        'users.user_id'
                    )
                    ->where('users.role', 'parent')
                    ->where(
                        "$table.created_at",
                        '>=',
                        $start->copy()->startOfDay()
                    )
                    ->where(
                        "$table.created_at",
                        '<',
                        $end->copy()->addDay()->startOfDay()
                    )
                    ->distinct()
                    ->count('users.user_id');
        }

        return $details;
    }
}