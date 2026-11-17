<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request, ReportService $reportService)
    {
        // الأقسام المتاحة في التقارير
        $availableSections = $reportService->sections();

        // التحقق من صحة اختيارات المستخدم
        $validated = $request->validate([
            'sections' => [
                'nullable',
                'array',
                'min:1',
            ],

            'sections.*' => [
                'string',
                Rule::in(array_keys($availableSections)),
            ],

            'period' => [
                'nullable',
                Rule::in([
                    'today',
                    '7days',
                    '30days',
                    '3months',
                    '6months',
                    'year',
                    'custom',
                ]),
            ],

            'from' => [
                'required_if:period,custom',
                'nullable',
                'date_format:Y-m-d',
            ],

            'to' => [
                'required_if:period,custom',
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:from',
            ],
        ]);

        // إذا لم تُحدد أقسام، نعرض جميع الأقسام
        $selectedSections = $validated['sections']
            ?? array_keys($availableSections);

        // الفترة الافتراضية: آخر 30 يومًا
        $period = $validated['period'] ?? '30days';

        // التواريخ المخصصة
        $from = $validated['from'] ?? null;
        $to = $validated['to'] ?? null;

        // إنشاء التقرير من قاعدة البيانات
        $report = $reportService->generate(
            $selectedSections,
            $period,
            $from,
            $to
        );

        // عرض صفحة التقارير
        return view('reports', compact(
            'report',
            'availableSections',
            'selectedSections',
            'period',
            'from',
            'to'
        ));
    }
}