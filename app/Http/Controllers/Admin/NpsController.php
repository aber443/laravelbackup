<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NpsController extends Controller
{
    public function index(Request $request): View
    {
        $periods = [
            'daily' => ['label' => 'يوم', 'days' => 1],
            'weekly' => ['label' => 'أسبوع', 'days' => 7],
            'monthly' => ['label' => 'شهر', 'days' => 30],
        ];

        $requestedPeriod = $request->query('period');

        $period = is_string($requestedPeriod)
            && isset($periods[$requestedPeriod])
                ? $requestedPeriod
                : 'weekly';

        $today = CarbonImmutable::today();

        $start = $today->subDays($periods[$period]['days'] - 1);
        $end = $today->addDay();

        $dateRange = $start->isSameDay($today)
            ? $today->format('d/m/Y')
            : $start->format('d/m/Y') . ' — ' . $today->format('d/m/Y');

        // إجمالي أولياء الأمور
        $eligibleCount = DB::table('users')
            ->where('role', 'parent')
            ->count();

        // أحدث تقييم لكل ولي أمر
        $latestRatingIds = DB::table('contact_messages')
            ->selectRaw('MAX(id) AS id')
            ->where('type', 'rating')
            ->whereBetween('rating', [1, 5])
            ->groupBy('user_id');

        // التقييمات خلال الفترة المحددة
        $ratings = DB::table('contact_messages as cm')
            ->joinSub($latestRatingIds, 'latest', function ($join) {
                $join->on('cm.id', '=', 'latest.id');
            })
            ->join('users', 'users.user_id', '=', 'cm.user_id')
            ->where('users.role', 'parent')
            ->where('cm.updated_at', '>=', $start)
            ->where('cm.updated_at', '<', $end)
            ->select('cm.rating')
            ->get();

        // توزيع النجوم من 1 إلى 5
        $scores = array_fill(1, 5, 0);

        foreach ($ratings as $rating) {
            $score = (int) $rating->rating;

            if ($score >= 1 && $score <= 5) {
                $scores[$score]++;
            }
        }

        $total = array_sum($scores);

        // فئات الرضا
        $detractors = $scores[1] + $scores[2];
        $passives = $scores[3];
        $promoters = $scores[4] + $scores[5];

        // متوسط التقييم
        $weightedSum = 0;

        foreach ($scores as $score => $count) {
            $weightedSum += $score * $count;
        }

        $averageRating = $total > 0
            ? round($weightedSum / $total, 1)
            : null;

        // نسبة الرضا
        $satisfactionRate = $total > 0
            ? round($promoters / $total * 100, 1)
            : null;

        // نسبة المشاركة
        $participation = $eligibleCount > 0
            ? round($total / $eligibleCount * 100, 1)
            : null;

        return view('admin.nps.index', compact(
            'periods',
            'period',
            'dateRange',
            'scores',
            'eligibleCount',
            'total',
            'detractors',
            'passives',
            'promoters',
            'averageRating',
            'satisfactionRate',
            'participation'
        ));
    }
}