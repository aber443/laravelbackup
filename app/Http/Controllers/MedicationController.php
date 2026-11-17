<?php
namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\MedicationActivity;
use App\Models\Child;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index(Request $request)
    {
        // ========================================
        // 1. تحديد البطاقة المحددة
        // ========================================

        $view = $request->query('view');

        $allowedViews = [
            'all_medications',
            'children_with_medications',
            'active_users',
        ];

        if (!in_array($view, $allowedViews, true)) {
            $view = null;
        }

        // ========================================
        // 2. إجمالي سجلات الأدوية
        // ========================================

        $totalMedications = Medication::count();

        // ========================================
        // 3. انتشار تسجيل الأدوية بين الأطفال
        // ========================================

        $totalChildren = Child::count();

        $childrenWithMedications = Child::whereHas(
            'medications'
        )->count();

        $childrenWithoutMedications = Child::whereDoesntHave(
            'medications'
        )->count();

        $medicationUsagePercentage = $totalChildren > 0
            ? round(
                ($childrenWithMedications / $totalChildren) * 100,
                1
            )
            : 0;

        // ========================================
        // 4. نشاط المستخدمين خلال الشهر الحالي
        // ========================================

        $currentMonthStart = Carbon::now()->startOfMonth();

        $nextMonthStart = Carbon::now()
            ->addMonthNoOverflow()
            ->startOfMonth();

        $currentMonthActiveUsers = MedicationActivity::where(
            'occurred_at',
            '>=',
            $currentMonthStart
        )
            ->where('occurred_at', '<', $nextMonthStart)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        // ========================================
        // 5. نشاط المستخدمين خلال الشهر السابق
        // ========================================

        $previousMonthStart = Carbon::now()
            ->subMonthNoOverflow()
            ->startOfMonth();

        $previousMonthActiveUsers = MedicationActivity::where(
            'occurred_at',
            '>=',
            $previousMonthStart
        )
            ->where('occurred_at', '<', $currentMonthStart)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        // ========================================
        // 6. نسبة التغير الشهري
        // ========================================

        $monthlyActivityChange = null;

        if ($previousMonthActiveUsers > 0) {
            $monthlyActivityChange = round(
                (
                    ($currentMonthActiveUsers - $previousMonthActiveUsers)
                    / $previousMonthActiveUsers
                ) * 100,
                1
            );
        }

        // ========================================
        // 7. نشاط آخر 6 أشهر
        // ========================================

        $activityMonths = [];
        $monthlyActiveUsers = [];

        for ($i = 5; $i >= 0; $i--) {

            $monthStart = Carbon::now()
                ->subMonthsNoOverflow($i)
                ->startOfMonth();

            $monthEnd = $monthStart->copy()->addMonth();

            $activeUsers = MedicationActivity::where(
                'occurred_at',
                '>=',
                $monthStart
            )
                ->where('occurred_at', '<', $monthEnd)
                ->whereNotNull('user_id')
                ->distinct()
                ->count('user_id');

            $activityMonths[] = $monthStart->format('Y-m');

            $monthlyActiveUsers[] = $activeUsers;
        }

        // ========================================
        // 8. تجهيز بيانات تفاصيل البطاقات
        // ========================================

        $detailsMedications = collect();

        $detailsChildren = collect();

        $detailsActiveUsers = collect();

        // ----------------------------------------
        // البطاقة الأولى: جميع سجلات الأدوية
        // ----------------------------------------

        if ($view === 'all_medications') {

            $detailsMedications = Medication::with([
                'child.user'
            ])
                ->orderByDesc('start_date')
                ->orderByDesc('medication_id')
                ->get();
        }

        // ----------------------------------------
        // البطاقة الثانية: الأطفال الذين لديهم أدوية
        // ----------------------------------------

        elseif ($view === 'children_with_medications') {

            $detailsChildren = Child::with('user')
                ->withCount('medications')
                ->whereHas('medications')
                ->orderBy('name')
                ->get();
        }

        // ----------------------------------------
        // البطاقة الثالثة: المستخدمون النشطون هذا الشهر
        // ----------------------------------------

        elseif ($view === 'active_users') {

            $activities = MedicationActivity::with('user')
                ->where('occurred_at', '>=', $currentMonthStart)
                ->where('occurred_at', '<', $nextMonthStart)
                ->whereNotNull('user_id')
                ->orderByDesc('occurred_at')
                ->get();

            // تجميع العمليات حسب المستخدم
            // كل مستخدم يظهر مرة واحدة فقط
            $detailsActiveUsers = $activities
                ->groupBy('user_id')
                ->map(function ($userActivities) {

                    return [
                        'user' => $userActivities->first()->user,

                        'activities_count' => $userActivities->count(),

                        'last_activity' => $userActivities->max(
                            'occurred_at'
                        ),
                    ];

                })
                ->filter(function ($item) {
                    return $item['user'] !== null;
                })
                ->values();
        }

        // ========================================
        // 9. إرسال البيانات إلى صفحة الأدوية
        // ========================================

        return view('medications', compact(
            'totalMedications',
            'totalChildren',
            'childrenWithMedications',
            'childrenWithoutMedications',
            'medicationUsagePercentage',
            'currentMonthActiveUsers',
            'previousMonthActiveUsers',
            'monthlyActivityChange',
            'activityMonths',
            'monthlyActiveUsers',
            'view',
            'detailsMedications',
            'detailsChildren',
            'detailsActiveUsers'
        ));
    }
}