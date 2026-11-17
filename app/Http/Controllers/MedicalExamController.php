<?php
namespace App\Http\Controllers;

use App\Models\MedicalExam;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MedicalExamController extends Controller
{
    public function index(Request $request)
    {
        // الفترات الزمنية المسموح بها
        $allowedPeriods = [
            'all',
            'day',
            'week',
            'month',
            '3months',
            '6months',
            'year'
        ];

        // الفترة المختارة
        $period = $request->query('period', 'all');

        if (!in_array($period, $allowedPeriods, true)) {
            $period = 'all';
        }

        // نوع التفاصيل المطلوبة
        $allowedDetails = [
            'all',
            'with_attachments',
            'without_attachments'
        ];

        $details = $request->query('details');

        if (!in_array($details, $allowedDetails, true)) {
            $details = null;
        }

        // تحديد بداية الفترة
        $startDate = null;

        switch ($period) {
            case 'day':
                $startDate = Carbon::now()->subDay();
                break;

            case 'week':
                $startDate = Carbon::now()->subDays(7);
                break;

            case 'month':
                $startDate = Carbon::now()->subDays(30);
                break;

            case '3months':
                $startDate = Carbon::now()->subMonthsNoOverflow(3);
                break;

            case '6months':
                $startDate = Carbon::now()->subMonthsNoOverflow(6);
                break;

            case 'year':
                $startDate = Carbon::now()->subYear();
                break;
        }

        // الاستعلام الأساسي
        $query = MedicalExam::query();

        if ($startDate !== null) {
            $query->where('created_at', '>=', $startDate);
        }

        // إجمالي الفحوصات
        $totalExams = (clone $query)->count();

        // الفحوصات التي تحتوي على مرفقات
        $examsWithAttachments = (clone $query)
            ->whereNotNull('attachment_path')
            ->where('attachment_path', '!=', '')
            ->count();

        // الفحوصات التي لا تحتوي على مرفقات
        $examsWithoutAttachments =
            $totalExams - $examsWithAttachments;

        // النسب
        $attachmentPercentage = $totalExams > 0
            ? round(($examsWithAttachments / $totalExams) * 100, 1)
            : 0;

        $withoutAttachmentPercentage = $totalExams > 0
            ? round(($examsWithoutAttachments / $totalExams) * 100, 1)
            : 0;

        // جلب سجلات الفحوصات عند الضغط على إحدى البطاقات
        $exams = collect();

        if ($details !== null) {

            $examsQuery = (clone $query)->with('child');

            if ($details === 'with_attachments') {
                $examsQuery
                    ->whereNotNull('attachment_path')
                    ->where('attachment_path', '!=', '');
            }

            if ($details === 'without_attachments') {
                $examsQuery->where(function ($q) {
                    $q->whereNull('attachment_path')
                        ->orWhere('attachment_path', '');
                });
            }

            $exams = $examsQuery
                ->orderByDesc('exam_date')
                ->orderByDesc('created_at')
                ->get();
        }

        return view('medical_exams', compact(
            'period',
            'details',
            'totalExams',
            'examsWithAttachments',
            'examsWithoutAttachments',
            'attachmentPercentage',
            'withoutAttachmentPercentage',
            'exams'
        ));
    }
}