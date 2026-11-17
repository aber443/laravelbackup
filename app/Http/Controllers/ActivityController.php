<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Child;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        // تحديد نوع التفاصيل المطلوبة عند الضغط على البطاقة
        $view = $request->query('view');

        $allowedViews = [
            'all_activities',
            'today_activities',
            'children_with_activities',
            'active_children',
        ];

        if (!in_array($view, $allowedViews, true)) {
            $view = null;
        }

        // تحديد الفترات الزمنية
        $today = Carbon::today();

        // آخر 7 أيام بما فيها اليوم
        $startCurrentWeek = $today->copy()->subDays(6);
        $endCurrentWeek = $today->copy();

        // الأيام السبعة السابقة
        $startPreviousWeek = $today->copy()->subDays(13);
        $endPreviousWeek = $today->copy()->subDays(7);

        $todayDate = $today->toDateString();

        $currentStartDate = $startCurrentWeek->toDateString();
        $currentEndDate = $endCurrentWeek->toDateString();

        $previousStartDate = $startPreviousWeek->toDateString();
        $previousEndDate = $endPreviousWeek->toDateString();

        // 1. إجمالي الأنشطة المسجلة
        $totalActivities = Activity::count();

        // 2. الأنشطة المسجلة اليوم
        $todayActivities = Activity::where(
            'activity_date',
            $todayDate
        )->count();

        // 3. الأطفال الذين استخدموا الميزة مرة واحدة على الأقل
        $childrenWithActivities = Child::whereHas(
            'activities'
        )->count();

        // 4. الأطفال النشطون خلال آخر 7 أيام
        $activeChildrenLast7Days = Child::whereHas(
            'activities',
            function ($query) use (
                $currentStartDate,
                $currentEndDate
            ) {
                $query->whereBetween('activity_date', [
                    $currentStartDate,
                    $currentEndDate
                ]);
            }
        )->count();

        // 5. عدد الأنشطة خلال آخر 7 أيام
        $currentWeekActivities = Activity::whereBetween(
            'activity_date',
            [$currentStartDate, $currentEndDate]
        )->count();

        // 6. عدد الأنشطة خلال الأيام السبعة السابقة
        $previousWeekActivities = Activity::whereBetween(
            'activity_date',
            [$previousStartDate, $previousEndDate]
        )->count();

        // 7. نسبة التغير مقارنة بالفترة السابقة
        $weeklyChangePercentage = null;

        if ($previousWeekActivities > 0) {
            $weeklyChangePercentage = round(
                (
                    ($currentWeekActivities - $previousWeekActivities)
                    / $previousWeekActivities
                ) * 100,
                1
            );
        }

        // 8. الأطفال الذين سجلوا نشاطًا في يومين مختلفين
        // على الأقل خلال آخر 7 أيام
        $childrenWithRepeatedActivity = Activity::query()
            ->whereBetween('activity_date', [
                $currentStartDate,
                $currentEndDate
            ])
            ->select('child_id')
            ->groupBy('child_id')
            ->havingRaw('COUNT(DISTINCT activity_date) >= 2')
            ->count();

        // 9. بيانات الرسم البياني لآخر 7 أيام
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);

            $dateString = $date->toDateString();

            $chartLabels[] = $date->format('Y-m-d');

            $chartData[] = Activity::where(
                'activity_date',
                $dateString
            )->count();
        }

        // 10. بيانات التفاصيل الخاصة بالبطاقات
        $detailsActivities = collect();
        $detailsChildren = collect();

        // جميع الأنشطة
        if ($view === 'all_activities') {

            $detailsActivities = Activity::with([
                'child.user'
            ])
                ->orderByDesc('activity_date')
                ->orderByDesc('activity_time')
                ->orderByDesc('activity_id')
                ->get();

        }

        // الأنشطة المسجلة اليوم فقط
        elseif ($view === 'today_activities') {

            $detailsActivities = Activity::with([
                'child.user'
            ])
                ->where('activity_date', $todayDate)
                ->orderByDesc('activity_time')
                ->orderByDesc('activity_id')
                ->get();

        }

        // الأطفال الذين لديهم أنشطة مسجلة
        elseif ($view === 'children_with_activities') {

            $detailsChildren = Child::with('user')
                ->withCount('activities')
                ->whereHas('activities')
                ->orderBy('name')
                ->get();

        }

        // الأطفال النشطون خلال آخر 7 أيام
        elseif ($view === 'active_children') {

            $detailsChildren = Child::with('user')
                ->withCount([
                    'activities' => function ($query) use (
                        $currentStartDate,
                        $currentEndDate
                    ) {
                        $query->whereBetween('activity_date', [
                            $currentStartDate,
                            $currentEndDate
                        ]);
                    }
                ])
                ->whereHas(
                    'activities',
                    function ($query) use (
                        $currentStartDate,
                        $currentEndDate
                    ) {
                        $query->whereBetween('activity_date', [
                            $currentStartDate,
                            $currentEndDate
                        ]);
                    }
                )
                ->orderBy('name')
                ->get();
        }

        // عرض الصفحة
        return view('activities', compact(
            'totalActivities',
            'todayActivities',
            'childrenWithActivities',
            'activeChildrenLast7Days',
            'currentWeekActivities',
            'previousWeekActivities',
            'weeklyChangePercentage',
            'childrenWithRepeatedActivity',
            'chartLabels',
            'chartData',
            'view',
            'detailsActivities',
            'detailsChildren'
        ));
    }
}