<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SystemLogExportController extends Controller
{
    public function __invoke(): StreamedResponse
    {
        $today = CarbonImmutable::today();
        $startDate = $today->subDays(6);
        $endDate = $today->addDay();

        $logs = SystemLog::query()
            ->select(['system_log_id', 'event_type', 'occurred_at'])
            ->whereIn('event_type', ['error', 'security'])
            ->where('occurred_at', '>=', $startDate)
            ->where('occurred_at', '<', $endDate)
            ->orderByDesc('occurred_at')
            ->orderByDesc('system_log_id');

        return response()->streamDownload(
            static function () use ($logs): void {
                $file = fopen('php://output', 'wb');

                try {
                    fwrite($file, "\xEF\xBB\xBF");
                    fputcsv($file, ['رقم السجل', 'نوع الحدث', 'وقت التسجيل'], ',', '"', '');

                    foreach ($logs->cursor() as $log) {
                        $type = $log->event_type === 'error'
                            ? 'خطأ تقني'
                            : 'تنبيه أمني';

                        fputcsv($file, [
                            $log->system_log_id,
                            $type,
                            $log->occurred_at->format('Y-m-d H:i:s'),
                        ], ',', '"', '');
                    }
                } finally {
                    fclose($file);
                }
            },
            'tinyguide-system-logs-' . $today->toDateString() . '.csv',
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }
}