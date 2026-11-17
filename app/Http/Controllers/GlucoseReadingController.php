<?php
namespace App\Http\Controllers;

use App\Models\GlucoseReading;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GlucoseReadingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Glucose Readings - List, Search, Filters & Statistics
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // استقبال بيانات البحث والتصفية
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $search = trim((string) ($filters['search'] ?? ''));
        $dateFrom = $filters['date_from'] ?? '';
        $dateTo = $filters['date_to'] ?? '';

        // جلب القراءات مع الطفل وولي الأمر والوجبة المرتبطة
        $query = GlucoseReading::with([
            'child.user',
            'meal'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search - Child or Parent
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->whereHas('child', function ($childQuery) use ($search) {

                $childQuery->where(function ($q) use ($search) {

                    // البحث باسم الطفل
                    $q->where('name', 'like', '%' . $search . '%')

                        // البحث باسم ولي الأمر
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        });

                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($dateFrom !== '') {
            $query->where(
                'reading_date',
                '>=',
                $dateFrom
            );
        }

        if ($dateTo !== '') {
            $query->where(
                'reading_date',
                '<=',
                $dateTo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Readings List
        |--------------------------------------------------------------------------
        */

        $readings = $query
            ->orderByDesc('reading_date')
            ->orderByDesc('reading_time')
            ->orderByDesc('glucose_reading_id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | General Statistics
        |--------------------------------------------------------------------------
        */

        // إجمالي القراءات في النظام
        $totalReadings = GlucoseReading::count();

        // عدد قراءات اليوم بحسب تاريخ القياس
        $todayReadings = GlucoseReading::whereDate(
            'reading_date',
            Carbon::today()
        )->count();

        // عدد الأطفال الذين لديهم قراءات
        $childrenWithReadings = GlucoseReading::distinct()
            ->count('child_id');

        // عدد الأطفال الذين ليس لديهم قراءات
        $childrenWithoutReadings = Child::whereDoesntHave(
            'glucoseReadings'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Average Glucose - mg/dL
        |--------------------------------------------------------------------------
        */

        // نحسب المتوسط للقراءات التي وحدتها mg/dL فقط
        $averageGlucose = GlucoseReading::where('unit', 'mg/dL')
            ->avg('glucose_level');

        /*
        |--------------------------------------------------------------------------
        | Chart - Last 7 Days
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];
        $chartValues = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $chartLabels[] = $date->format('Y-m-d');

            $chartValues[] = GlucoseReading::whereDate(
                'reading_date',
                $date->toDateString()
            )->count();
        }

        /*
        |--------------------------------------------------------------------------
        | Measurement Types Distribution
        |--------------------------------------------------------------------------
        */

        $measurementTypes = GlucoseReading::query()
            ->selectRaw(
                'reading_type AS measurement_type, COUNT(*) AS total'
            )
            ->whereNotNull('reading_type')
            ->where('reading_type', '!=', '')
            ->groupBy('reading_type')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('glucose_readings', compact(
            'readings',
            'search',
            'dateFrom',
            'dateTo',
            'totalReadings',
            'todayReadings',
            'childrenWithReadings',
            'childrenWithoutReadings',
            'averageGlucose',
            'chartLabels',
            'chartValues',
            'measurementTypes'
        ));
    }
}