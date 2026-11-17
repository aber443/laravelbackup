<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DashboardHomeController extends Controller
{
    public function index(Request $request)
    {
        $period = in_array($request->query('period'), ['daily', 'weekly', 'monthly'], true)
            ? $request->query('period')
            : 'weekly';

        $featurePeriod = in_array($request->query('feature_period'), ['weekly', 'monthly'], true)
            ? $request->query('feature_period')
            : 'weekly';

        $peakPeriod = in_array($request->query('peak_period'), ['weekly', 'monthly'], true)
            ? $request->query('peak_period')
            : 'weekly';

        $today = CarbonImmutable::today();

        $data = [
            'databaseStatus' => 'disconnected',
            'incompleteAccounts' => null,
            'period' => $period,
            'featurePeriod' => $featurePeriod,
            'peakPeriod' => $peakPeriod,
            'featurePeriods' => [
                'weekly' => ['label' => 'أسبوعي', 'caption' => 'آخر ٧ أيام'],
                'monthly' => ['label' => 'شهري', 'caption' => 'آخر ٣٠ يومًا'],
            ],

            // بطاقات الصفحة الرئيسية الحالية
            'usersCount' => null,
            'systemLogsCount' => null,
            'checklistsCount' => null,

            // إحصائيات النظام الحالية
            'errorsCount' => 0,
            'securityAlertsCount' => 0,
            'usageChartData' => [],
            'eventsChartData' => [],
            'featureUsageChartData' => [],
            'peakUsageRows' => [],

            // الإحصائيات الصحية الجديدة
            'governorateDistribution' => [],
            'genderDistribution' => [],
            'genderDistributionTotal' => 0,
            'glucoseTypeCounts' => [],
            'glucoseAnalytics' => [],
        ];

        try {
            DB::selectOne('SELECT 1');
            $data['databaseStatus'] = 'connected';

            $parentIds = DB::table('users')
                ->where('role', 'parent')
                ->pluck('user_id');

            $data['usersCount'] = $parentIds->count();
            $data['systemLogsCount'] = DB::table('system_logs')->count();
            $data['checklistsCount'] = DB::table('checklists')->count();

            // الإحصائية الأولى: عدد الأطفال حسب محافظة ولي الأمر
            $data['governorateDistribution'] = $this->governorateDistribution();

            // الإحصائية الثانية: توزيع الأطفال حسب الجنس
            $genderRows = $this->genderDistribution();
            $data['genderDistribution'] = $genderRows;
            $data['genderDistributionTotal'] = (int) collect($genderRows)->sum('total');

            $data['glucoseTypeCounts'] = $this->glucoseTypeCounts();
            $data['glucoseAnalytics'] = $this->glucoseAnalytics();

            // الرسومات الحالية في الداشبورد
            $data['usageChartData'] = $this->usageChart($period, $today);

            $events = $this->eventsChart($today);
            $data['eventsChartData'] = $events['chart'];
            $data['errorsCount'] = $events['errors'];
            $data['securityAlertsCount'] = $events['security'];

            $data['featureUsageChartData'] = $this->featureUsage($featurePeriod, $today);
            $data['peakUsageRows'] = $this->peakUsage($peakPeriod, $today);
        } catch (Throwable $exception) {
            report($exception);
        }

        if ($data['databaseStatus'] === 'connected') {
            try {
                $data['incompleteAccounts'] = $this->incompleteAccounts();
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return view('dashboard-home', $data);
    }

    /**
     * حسابات أولياء الأمور التي مضى على تسجيلها 24 ساعة على الأقل
     * ولم يُسجّل لها أي طفل حتى الآن.
     */
    private function incompleteAccounts(): array
    {
        $now = CarbonImmutable::now('Asia/Aden');
        $threshold = $now->subHours(24);

        $query = DB::table('users')
            ->where('role', 'parent')
            ->whereNotNull('created_at')
            ->where('created_at', '<=', $threshold->toDateTimeString())
            ->whereNotExists(function ($childrenQuery) {
                $childrenQuery->select(DB::raw(1))
                    ->from('children')
                    ->whereColumn('children.user_id', 'users.user_id');
            });

        $count = (clone $query)->count();

        $items = $count > 0
            ? $query->orderBy('created_at')
                ->orderBy('user_id')
                ->limit(5)
                ->get(['user_id', 'name', 'created_at'])
                ->map(static function ($user) use ($now): array {
                    $registeredAt = CarbonImmutable::parse($user->created_at, $now->getTimezone());

                    return [
                        'id' => (int) $user->user_id,
                        'name' => (string) ($user->name ?? ''),
                        'registeredAt' => $registeredAt->format('Y-m-d H:i'),
                        'hoursSinceRegistration' => (int) $registeredAt->diffInHours($now),
                    ];
                })
                ->all()
            : [];

        return ['count' => $count, 'items' => $items];
    }

    /**
     * عدد الأطفال المسجلين حسب محافظة ولي الأمر.
     * TRIM يعالج المسافات الزائدة مثل "صنعاء ".
     * المحافظات غير المدخلة تظهر تحت "غير محددة".
     */
    private function governorateDistribution(): array
    {
        $childrenByGovernorate = DB::table('children')
            ->join('users', 'children.user_id', '=', 'users.user_id')
            ->where('users.role', 'parent')
            ->selectRaw(
                "COALESCE(NULLIF(TRIM(users.city), ''), ?) AS governorate",
                ['غير محددة']
            );

        return DB::query()
            ->fromSub($childrenByGovernorate, 'children_by_governorate')
            ->select('governorate')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('governorate')
            ->orderByDesc('total')
            ->orderBy('governorate')
            ->get()
            ->map(static function ($row): array {
                return [
                    'governorate' => (string) $row->governorate,
                    'total' => (int) $row->total,
                ];
            })
            ->all();
    }

    /**
     * توحيد القيم الموجودة في قاعدة البيانات:
     * ولد = ذكور
     * بنت وأنثى = إناث
     */
    private function genderDistribution(): array
    {
        $genderExpression = "
            CASE
                WHEN TRIM(gender) = 'ولد' THEN 'ذكور'
                WHEN TRIM(gender) IN ('بنت', 'أنثى') THEN 'إناث'
                ELSE 'غير محدد'
            END
        ";

        $counts = DB::table('children')
            ->selectRaw("{$genderExpression} AS gender_label, COUNT(*) AS total")
            ->groupByRaw($genderExpression)
            ->pluck('total', 'gender_label');

        $rows = [];

        foreach (['ذكور', 'إناث', 'غير محدد'] as $label) {
            $total = (int) ($counts[$label] ?? 0);

            // لا نضيف فئة غير محدد للرسم إذا لم توجد لها بيانات.
            if ($total > 0 || $label !== 'غير محدد') {
                $rows[] = [
                    'label' => $label,
                    'total' => $total,
                ];
            }
        }

        return $rows;
    }

    /**
     * إحصائيات فعلية لكل نوع من أنواع قراءات السكر.
     * لا تُطبّق حدود تصنيف على الصائم أو قبل الوجبة.
     * تصنيف ما بعد الوجبة يبقى محصورًا بالقراءات المرتبطة بوجبة مسجلة.
     */
    private function glucoseAnalytics(): array
    {
        $types = ['صائم', 'قبل الوجبة', 'بعد الوجبة'];
        $analytics = [];

        foreach ($types as $type) {
            $query = DB::table('glucose_readings')
                ->where('reading_type', $type)
                ->whereNotNull('glucose_level')
                ->where('unit', 'mg/dL');

            $summary = (clone $query)
                ->selectRaw('COUNT(*) AS total, AVG(glucose_level) AS average, MIN(glucose_level) AS minimum, MAX(glucose_level) AS maximum')
                ->first();

            $readings = (clone $query)
                ->orderByDesc('reading_date')
                ->orderByDesc('reading_time')
                ->limit(30)
                ->get(['glucose_level', 'reading_date', 'reading_time'])
                ->reverse()
                ->values()
                ->map(static function ($reading): array {
                    $date = $reading->reading_date
                        ? CarbonImmutable::parse($reading->reading_date)->format('d/m')
                        : '';
                    $time = $reading->reading_time
                        ? substr((string) $reading->reading_time, 0, 5)
                        : '';

                    return [
                        'label' => trim($date . ' ' . $time),
                        'value' => (float) $reading->glucose_level,
                    ];
                })
                ->all();

            $classification = [];
            if ($type === 'بعد الوجبة') {
                $levelExpression = "CASE WHEN glucose_level < 70 THEN 'منخفض' WHEN glucose_level <= 180 THEN 'ضمن النطاق' ELSE 'مرتفع' END";
                $counts = (clone $query)
                    ->where('meal_status', 'الوجبة مسجلة في التطبيق')
                    ->whereNotNull('meal_id')
                    ->selectRaw("{$levelExpression} AS level_label, COUNT(*) AS total")
                    ->groupByRaw($levelExpression)
                    ->pluck('total', 'level_label');

                foreach (['منخفض', 'ضمن النطاق', 'مرتفع'] as $label) {
                    $classification[] = [
                        'label' => $label,
                        'total' => (int) ($counts[$label] ?? 0),
                    ];
                }
            }

            $analytics[$type] = [
                'total' => (int) ($summary->total ?? 0),
                'average' => $summary->average !== null ? round((float) $summary->average, 1) : null,
                'minimum' => $summary->minimum !== null ? (float) $summary->minimum : null,
                'maximum' => $summary->maximum !== null ? (float) $summary->maximum : null,
                'readings' => $readings,
                'classification' => $classification,
            ];
        }

        return $analytics;
    }

    private function glucoseTypeCounts(): array
    {
        $types = ['صائم', 'قبل الوجبة', 'بعد الوجبة'];
        $counts = DB::table('glucose_readings')
            ->whereIn('reading_type', $types)
            ->whereNotNull('glucose_level')
            ->where('unit', 'mg/dL')
            ->selectRaw('reading_type, COUNT(*) AS total')
            ->groupBy('reading_type')
            ->pluck('total', 'reading_type');

        $result = [];
        foreach ($types as $type) {
            $result[$type] = (int) ($counts[$type] ?? 0);
        }

        return $result;
    }

    private function usageChart(string $period, CarbonImmutable $today): array
    {
        if ($period === 'daily') {
            $cursor = $today->subDays(6)->startOfDay();
            $end = $today->addDay()->startOfDay();
        } elseif ($period === 'monthly') {
            $cursor = $today->startOfMonth()->subMonths(5);
            $end = $today->addDay()->startOfDay();
        } else {
            $cursor = $today->startOfWeek(CarbonImmutable::MONDAY)->subWeeks(7);
            $end = $today->addDay()->startOfDay();
        }

        $rows = [];

        while ($cursor->lt($end)) {
            if ($period === 'daily') {
                $next = $cursor->addDay();
                $label = $cursor->locale('ar')->translatedFormat('j M');
            } elseif ($period === 'monthly') {
                $next = $cursor->addMonth();
                $label = $cursor->locale('ar')->translatedFormat('M Y');
            } else {
                $next = $cursor->addWeek();
                $label = $cursor->locale('ar')->translatedFormat('j M');
            }

            $bucketEnd = $next->lt($end) ? $next : $end;

            $total = DB::table('system_logs')
                ->where('event_type', 'user_activity')
                ->whereIn('user_id', function ($query) {
                    $query->select('user_id')
                        ->from('users')
                        ->where('role', 'parent');
                })
                ->where('occurred_at', '>=', $cursor)
                ->where('occurred_at', '<', $bucketEnd)
                ->distinct()
                ->count('user_id');

            $rows[] = ['label' => $label, 'total' => (int) $total];
            $cursor = $next;
        }

        return $rows;
    }

    private function eventsChart(CarbonImmutable $today): array
    {
        $start = $today->subDays(6)->startOfDay();
        $end = $today->addDay()->startOfDay();

        $events = DB::table('system_logs')
            ->selectRaw('DATE(occurred_at) AS log_date, event_type, COUNT(*) AS total')
            ->whereIn('event_type', ['error', 'security'])
            ->where('occurred_at', '>=', $start)
            ->where('occurred_at', '<', $end)
            ->groupByRaw('DATE(occurred_at), event_type')
            ->get()
            ->groupBy('log_date');

        $rows = [];
        $errors = 0;
        $security = 0;

        for ($date = $start; $date->lt($end); $date = $date->addDay()) {
            $day = $events->get($date->toDateString(), collect())->keyBy('event_type');
            $dayErrors = (int) ($day->get('error')?->total ?? 0);
            $daySecurity = (int) ($day->get('security')?->total ?? 0);

            $rows[] = [
                'label' => $date->locale('ar')->translatedFormat('j M'),
                'errors' => $dayErrors,
                'security' => $daySecurity,
            ];

            $errors += $dayErrors;
            $security += $daySecurity;
        }

        return ['chart' => $rows, 'errors' => $errors, 'security' => $security];
    }

    private function featureTables(): array
    {
        return [
            'glucose_readings' => 'قراءات السكر',
            'meals' => 'الوجبات',
            'activities' => 'الأنشطة',
            'medications' => 'الأدوية',
            'reports' => 'التقارير',
            'reminders' => 'التذكيرات',
        ];
    }

    private function featureUsage(string $period, CarbonImmutable $today): array
    {
        $start = ($period === 'monthly' ? $today->subDays(29) : $today->subDays(6))
            ->startOfDay();
        $end = $today->addDay()->startOfDay();

        $rows = [];

        foreach ($this->featureTables() as $table => $label) {
            $total = DB::table($table)
                ->where('created_at', '>=', $start)
                ->where('created_at', '<', $end)
                ->count();

            $rows[] = ['label' => $label, 'total' => (int) $total];
        }

        return $rows;
    }

    private function peakUsage(string $period, CarbonImmutable $today): array
    {
        $start = ($period === 'monthly' ? $today->subDays(29) : $today->subDays(6))
            ->startOfDay();
        $end = $today->addDay()->startOfDay();

        $hours = array_fill(0, 24, 0);

        foreach ($this->featureTables() as $table => $label) {
            $counts = DB::table($table)
                ->selectRaw('HOUR(created_at) AS hour, COUNT(*) AS total')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<', $end)
                ->groupBy('hour')
                ->get();

            foreach ($counts as $row) {
                $hours[(int) $row->hour] += (int) $row->total;
            }
        }

        $ranges = [
            ['label' => 'الفجر', 'hours' => '00:00 - 05:59', 'from' => 0, 'to' => 5],
            ['label' => 'الصباح', 'hours' => '06:00 - 11:59', 'from' => 6, 'to' => 11],
            ['label' => 'الظهر والعصر', 'hours' => '12:00 - 17:59', 'from' => 12, 'to' => 17],
            ['label' => 'المساء', 'hours' => '18:00 - 23:59', 'from' => 18, 'to' => 23],
        ];

        $rows = [];

        foreach ($ranges as $range) {
            $total = 0;

            for ($hour = $range['from']; $hour <= $range['to']; $hour++) {
                $total += $hours[$hour];
            }

            $rows[] = [
                'label' => $range['label'],
                'hours' => $range['hours'],
                'total' => $total,
            ];
        }

        return $rows;
    }
}
