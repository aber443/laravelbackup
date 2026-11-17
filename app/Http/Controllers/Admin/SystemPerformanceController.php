<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemPerformance;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SystemPerformanceController extends Controller
{
    public function index(SystemPerformance $performance): View
    {
        $metrics = $performance->read();

        return view('admin.performance.index', [
            'metrics' => $metrics,
            'measuredAt' => now(),
        ]);
    }

    public function probe(): JsonResponse
    {
        return response()->json(['ok' => true], 200, [
            'Cache-Control' => 'no-store',
        ]);
    }
}