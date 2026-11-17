<?php
namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Child;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MealController extends Controller
{
    public function index(Request $request)
    {
        // نوع التفاصيل المطلوبة عند الضغط على البطاقة
        $view = $request->query('view');

        $allowedViews = [
            'all_meals',
            'today_meals',
            'children_with_meals',
            'children_without_meals',
        ];

        if (!in_array($view, $allowedViews, true)) {
            $view = null;
        }

        // إجمالي الوجبات المسجلة
        $totalMeals = Meal::count();

        // الوجبات المسجلة اليوم
        $todayMeals = Meal::whereDate(
            'meal_date',
            Carbon::today()
        )->count();

        // عدد الأطفال الذين لديهم وجبات
        $childrenWithMeals = Child::whereHas('meals')->count();

        // عدد الأطفال الذين ليس لديهم وجبات
        $childrenWithoutMeals = Child::whereDoesntHave('meals')->count();

        // إجمالي الأطفال
        $totalChildren = Child::count();

        // نسبة استخدام ميزة الوجبات
        $usagePercentage = $totalChildren > 0
            ? round(($childrenWithMeals / $totalChildren) * 100, 1)
            : 0;

        // الأطفال النشطون خلال آخر 7 أيام
        $activeChildrenLast7Days = Child::whereHas(
            'meals',
            function ($query) {
                $query->whereDate(
                    'meal_date',
                    '>=',
                    Carbon::today()->subDays(6)
                );
            }
        )->count();

        // الرسم البياني لآخر 7 أيام
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $chartLabels[] = $date->format('Y-m-d');

            $chartData[] = Meal::whereDate(
                'meal_date',
                $date->toDateString()
            )->count();
        }

        // بيانات التفاصيل
        $detailsMeals = collect();
        $detailsChildren = collect();

        if ($view === 'all_meals') {

            $detailsMeals = Meal::with('child.user')
                ->orderByDesc('meal_date')
                ->orderByDesc('meal_time')
                ->get();

        } elseif ($view === 'today_meals') {

            $detailsMeals = Meal::with('child.user')
                ->whereDate('meal_date', Carbon::today())
                ->orderByDesc('meal_time')
                ->get();

        } elseif ($view === 'children_with_meals') {

            $detailsChildren = Child::with('user')
                ->withCount('meals')
                ->whereHas('meals')
                ->orderBy('name')
                ->get();

        } elseif ($view === 'children_without_meals') {

            $detailsChildren = Child::with('user')
                ->withCount('meals')
                ->whereDoesntHave('meals')
                ->orderBy('name')
                ->get();
        }

        return view('meals', compact(
            'totalMeals',
            'todayMeals',
            'childrenWithMeals',
            'childrenWithoutMeals',
            'totalChildren',
            'usagePercentage',
            'activeChildrenLast7Days',
            'chartLabels',
            'chartData',
            'view',
            'detailsMeals',
            'detailsChildren'
        ));
    }
}