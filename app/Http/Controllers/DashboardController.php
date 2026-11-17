<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Child;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $allowedPeriods = ['7days', '30days', '12months', 'custom'];

        $period = $request->query('period', '30days');

        if (!in_array($period, $allowedPeriods, true)) {
            $period = '30days';
        }

        $today = Carbon::today();

        switch ($period) {
            case '7days':
                $startDate = $today->copy()->subDays(6)->startOfDay();
                $endDate = $today->copy()->endOfDay();
                break;

            case '12months':
                $startDate = $today->copy()
                    ->subMonths(11)
                    ->startOfMonth();

                $endDate = $today->copy()->endOfDay();
                break;

            case 'custom':
                $validated = $request->validate([
                    'start_date' => ['required', 'date'],
                    'end_date' => [
                        'required',
                        'date',
                        'after_or_equal:start_date',
                    ],
                ]);

                $startDate = Carbon::parse(
                    $validated['start_date']
                )->startOfDay();

                $endDate = Carbon::parse(
                    $validated['end_date']
                )->endOfDay();
                break;

            default:
                $period = '30days';
                $startDate = $today->copy()->subDays(29)->startOfDay();
                $endDate = $today->copy()->endOfDay();
                break;
        }

        $periodDays = $startDate->copy()
            ->startOfDay()
            ->diffInDays($endDate->copy()->startOfDay()) + 1;

        $previousEndDate = $startDate->copy()->subSecond();

        $previousStartDate = $startDate->copy()
            ->subDays($periodDays)
            ->startOfDay();

        $usersCount = User::where('role', '!=', 'admin')->count();
        $childrenCount = Child::count();

        $newUsersCount = User::where('role', '!=', 'admin')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $previousNewUsersCount = User::where('role', '!=', 'admin')
            ->whereBetween('created_at', [
                $previousStartDate,
                $previousEndDate,
            ])
            ->count();

        $newChildrenCount = Child::whereBetween('created_at', [
            $startDate,
            $endDate,
        ])->count();

        $previousNewChildrenCount = Child::whereBetween('created_at', [
            $previousStartDate,
            $previousEndDate,
        ])->count();

        $calculateChange = function ($current, $previous) {
            if ($previous == 0) {
                return $current == 0 ? 0 : null;
            }

            return round(
                (($current - $previous) / $previous) * 100,
                1
            );
        };

        $usersGrowth = $calculateChange(
            $newUsersCount,
            $previousNewUsersCount
        );

        $childrenGrowth = $calculateChange(
            $newChildrenCount,
            $previousNewChildrenCount
        );

        $activeUsersCount = null;

        $featureEngagement = [
            [
                'name' => 'قراءات السكر',
                'icon' => '🩸',
                'users' => null,
                'percentage' => null,
            ],
            [
                'name' => 'الوجبات',
                'icon' => '🍽️',
                'users' => null,
                'percentage' => null,
            ],
            [
                'name' => 'الأدوية',
                'icon' => '💊',
                'users' => null,
                'percentage' => null,
            ],
            [
                'name' => 'الأنشطة',
                'icon' => '🏃',
                'users' => null,
                'percentage' => null,
            ],
            [
                'name' => 'الفحوصات الطبية',
                'icon' => '🩺',
                'users' => null,
                'percentage' => null,
            ],
        ];

        $chartData = [];

        if ($period === '12months') {

            $chartStart = $startDate->copy()->startOfMonth();
            $chartEnd = $endDate->copy()->startOfMonth();

            while ($chartStart->lte($chartEnd)) {

                $monthStart = $chartStart->copy()->startOfMonth();
                $monthEnd = $chartStart->copy()->endOfMonth();

                $chartData[] = [
                    'label' => $chartStart->format('Y-m'),

                    'users' => User::where('role', '!=', 'admin')
                        ->whereBetween('created_at', [
                            $monthStart,
                            $monthEnd,
                        ])
                        ->count(),

                    'children' => Child::whereBetween('created_at', [
                        $monthStart,
                        $monthEnd,
                    ])->count(),
                ];

                $chartStart->addMonth();
            }

        } else {

            $chartStart = $startDate->copy()->startOfDay();
            $chartEnd = $endDate->copy()->startOfDay();

            $totalDays = $chartStart->diffInDays($chartEnd) + 1;

            $step = $totalDays > 60
                ? (int) ceil($totalDays / 30)
                : 1;

            while ($chartStart->lte($chartEnd)) {

                $bucketStart = $chartStart->copy()->startOfDay();

                $bucketEnd = $chartStart->copy()
                    ->addDays($step - 1)
                    ->endOfDay();

                if ($bucketEnd->gt($endDate)) {
                    $bucketEnd = $endDate->copy();
                }

                $chartData[] = [
                    'label' => $bucketStart->format('m/d'),

                    'users' => User::where('role', '!=', 'admin')
                        ->whereBetween('created_at', [
                            $bucketStart,
                            $bucketEnd,
                        ])
                        ->count(),

                    'children' => Child::whereBetween('created_at', [
                        $bucketStart,
                        $bucketEnd,
                    ])->count(),
                ];

                $chartStart->addDays($step);
            }
        }

        return view('dashboard', compact(
            'period',
            'startDate',
            'endDate',
            'usersCount',
            'childrenCount',
            'newUsersCount',
            'previousNewUsersCount',
            'newChildrenCount',
            'previousNewChildrenCount',
            'usersGrowth',
            'childrenGrowth',
            'activeUsersCount',
            'featureEngagement',
            'chartData'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | قائمة المستخدمين
    |--------------------------------------------------------------------------
    */

    public function users(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],

            'status' => ['nullable', Rule::in([
                'all',
                'active',
                'inactive',
            ])],
        ]);

        $search = trim($validated['search'] ?? '');
        $status = $validated['status'] ?? 'all';

        $parentsQuery = User::query()
            ->where('role', 'parent');

        $usersCount = (clone $parentsQuery)->count();

        $activeUsersCount = (clone $parentsQuery)
            ->where('is_active', true)
            ->count();

        $inactiveUsersCount = (clone $parentsQuery)
            ->where('is_active', false)
            ->count();

        $users = (clone $parentsQuery)
            ->withCount('children')

            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })

            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })

            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })

            ->orderByDesc('created_at')
            ->orderByDesc((new User())->getKeyName())
            ->get();

        $filteredUsersCount = $users->count();

        return view('users', compact(
            'users',
            'search',
            'status',
            'usersCount',
            'activeUsersCount',
            'inactiveUsersCount',
            'filteredUsersCount'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | تفاصيل ولي الأمر وأطفاله والرسوم البيانية
    |--------------------------------------------------------------------------
    */

    public function showUser(Request $request, User $user)
    {
        abort_unless(
            $user->role === 'parent',
            404,
            'حساب ولي الأمر غير موجود.'
        );

        // جلب الأطفال المرتبطين بولي الأمر فقط.
        $user->load([
            'children' => function ($query) {
                $query->orderBy(
                    $query->getModel()->getQualifiedKeyName()
                );
            },
        ]);

        $children = $user->children;
        $childrenCount = $children->count();

        /* تحديد الفترة من أزرار الصفحة: آخر أسبوع أو آخر شهر. */
        $periodLabels = [
            '7days' => 'آخر أسبوع',
            '30days' => 'آخر شهر',
        ];

        $requestedPeriod = $request->query('activity_period', '30days');
        $activityPeriod = is_string($requestedPeriod)
            && isset($periodLabels[$requestedPeriod])
                ? $requestedPeriod
                : '30days';

        $days = $activityPeriod === '7days' ? 7 : 30;
        $today = CarbonImmutable::today();
        $startDate = $today->subDays($days - 1);
        $chartEnd = $today->addDay();

        /*
         * كل السجلات تخص حساب ولي الأمر نفسه، وبالتالي تشمل استخدامه
         * للتطبيق مع جميع أطفاله المرتبطين به.
         * لا نطلب أعمدة url أو metadata لأنها غير موجودة في جدول system_logs الحالي.
         */
        $activityLogs = SystemLog::query()
            ->where('user_id', $user->getKey())
            ->whereIn('event_type', ['user_activity', 'api_request'])
            ->where(function ($query) {
                $query->whereNull('action')
                    ->orWhere('action', '!=', 'parent_registered');
            })
            ->where('occurred_at', '>=', $startDate)
            ->where('occurred_at', '<', $chartEnd);

        // إجمالي التفاعلات المسجلة للحساب خلال الفترة المختارة.
        $totalInteractions = (clone $activityLogs)->count();

        /* الرسم الأول: عدد التفاعلات يوميًا. */
        $dailyTotals = [];

        for ($date = $startDate; $date->lt($chartEnd); $date = $date->addDay()) {
            $dailyTotals[$date->toDateString()] = 0;
        }

        $dailyLogs = (clone $activityLogs)
            ->selectRaw('DATE(occurred_at) AS activity_date, COUNT(*) AS total')
            ->groupBy('activity_date')
            ->pluck('total', 'activity_date');

        foreach ($dailyLogs as $date => $total) {
            if (array_key_exists($date, $dailyTotals)) {
                $dailyTotals[$date] = (int) $total;
            }
        }

        $activityChartData = [];

        foreach ($dailyTotals as $date => $total) {
            $activityChartData[] = [
                'date' => $date,
                'label' => Carbon::parse($date)->format('m/d'),
                'total' => $total,
            ];
        }

        /*
         * الرسم الثاني: توزيع استخدام أقسام التطبيق.
         * يعتمد فقط على الوسم الصريح [section:...] الذي يضيفه middleware
         * LogApiRequests إلى description. لا نخمن القسم من نص عام، ولا نضع
         * الطلبات غير المصنفة في فئة عامة بنسبة 100%.
         */
        $sectionLabels = [
            'glucose-readings' => 'قراءات السكر',
            'meals' => 'الوجبات',
            'activities' => 'الأنشطة',
            'medications' => 'الأدوية',
            'reports' => 'التقارير',
            'reminders' => 'التذكيرات',
        ];

        $sectionCounts = array_fill_keys(array_keys($sectionLabels), 0);

        // نحتسب الطلبات الناجحة فقط حتى لا يظهر فشل الاتصال كاستخدام فعلي للقسم.
        $sectionLogs = (clone $activityLogs)
            ->where('event_type', 'api_request')
            ->where('action', 'api_request_success')
            ->where('description', 'like', '%[section:%')
            ->get(['description']);

        foreach ($sectionLogs as $log) {
            $description = (string) ($log->description ?? '');

            if (preg_match('/\[section:([a-z-]+)\]\s*$/i', $description, $matches)) {
                $sectionKey = strtolower($matches[1]);

                if (array_key_exists($sectionKey, $sectionCounts)) {
                    $sectionCounts[$sectionKey]++;
                }
            }
        }

        arsort($sectionCounts);

        $featureUsageData = [];

        foreach ($sectionCounts as $sectionKey => $count) {
            if ($count <= 0) {
                continue;
            }

            $featureUsageData[] = [
                'name' => $sectionLabels[$sectionKey],
                'count' => (int) $count,
            ];
        }

        return view('users-show', compact(
            'user',
            'children',
            'childrenCount',
            'activityPeriod',
            'periodLabels',
            'days',
            'startDate',
            'totalInteractions',
            'activityChartData',
            'featureUsageData'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | تعديل المستخدم
    |--------------------------------------------------------------------------
    */

    public function editUser(User $user)
    {
        abort_if(
            $user->isAdmin(),
            403,
            'لا يمكن تعديل حساب مسؤول من هذه الصفحة.'
        );

        return view('users-edit', compact('user'));
    }


    /*
    |--------------------------------------------------------------------------
    | تحديث المستخدم
    |--------------------------------------------------------------------------
    */

    public function updateUser(Request $request, User $user)
    {
        abort_if(
            $user->isAdmin(),
            403,
            'لا يمكن تعديل حساب مسؤول من هذه الصفحة.'
        );

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',

                Rule::unique('users', 'phone')->ignore(
                    $user->getKey(),
                    $user->getKeyName()
                ),
            ],
        ], [
            'name.required' => 'اسم المستخدم مطلوب.',
            'phone.required' => 'رقم هاتف ولي الأمر مطلوب.',
            'phone.unique' => 'رقم الهاتف مسجل لحساب آخر.',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرفًا.',
        ]);

        $user->name = $data['name'];
        $user->phone = $data['phone'];

        $user->save();

        return redirect()
            ->route('users.show', $user->getRouteKey())
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }


    /*
    |--------------------------------------------------------------------------
    | تعطيل المستخدم
    |--------------------------------------------------------------------------
    */

    public function disableUser(User $user)
    {
        abort_if(
            $user->isAdmin(),
            403,
            'لا يمكن تعطيل حساب مسؤول.'
        );

        $user->is_active = false;
        $user->save();

        return back()
            ->with('success', 'تم تعطيل حساب المستخدم بنجاح.');
    }


    /*
    |--------------------------------------------------------------------------
    | تفعيل المستخدم
    |--------------------------------------------------------------------------
    */

    public function enableUser(User $user)
    {
        abort_if(
            $user->isAdmin(),
            403,
            'لا يمكن تغيير حالة حساب مسؤول من هذه الصفحة.'
        );

        $user->is_active = true;
        $user->save();

        return back()
            ->with('success', 'تم تفعيل حساب المستخدم بنجاح.');
    }
}
