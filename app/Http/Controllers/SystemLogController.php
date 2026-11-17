<?php
namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SystemLogController extends Controller
{
    /**
     * عرض سجلات النظام والإحصائيات والفلاتر.
     */
    public function index(Request $request)
    {
        $filters = $this->validateFilters($request);

        $summaryQuery = $this->filterDates(
            SystemLog::query(),
            $filters
        );

        $totalLogs = (clone $summaryQuery)->count();

        $apiRequestsQuery = (clone $summaryQuery)
            ->where('event_type', 'api_request');

        $totalApiRequests = (clone $apiRequestsQuery)->count();

        $successfulApiRequests = (clone $apiRequestsQuery)
            ->where('action', 'api_request_success')
            ->count();

        $failedApiRequests = (clone $apiRequestsQuery)
            ->where('action', 'api_request_failed')
            ->count();

        $securityAlerts = (clone $summaryQuery)
            ->where('event_type', 'security')
            ->count();

        $errorLogs = (clone $summaryQuery)
            ->where('event_type', 'error')
            ->count();

        $logs = $this->filteredLogs($filters)
            ->paginate(15)
            ->withQueryString();

        return view('system-log.index', array_merge(
            $this->labels(),
            compact(
                'logs',
                'totalLogs',
                'totalApiRequests',
                'successfulApiRequests',
                'failedApiRequests',
                'securityAlerts',
                'errorLogs',
                'filters'
            )
        ));
    }

    /**
     * تصدير سجلات النظام إلى Excel.
     */
    public function export(Request $request)
    {
        $filters = $this->validateFilters($request);

        // فتح معاينة التصدير أولاً.
        if (!$request->has('filename')) {
            return redirect()->route(
                'system-logs.index',
                $filters + ['prepare_export' => 1]
            );
        }

        // التحقق من اسم الملف.
        $validated = $request->validate([
            'filename' => [
                'required',
                'string',
                'max:80',
                'regex:/\A[\p{L}\p{M}\p{N} ._-]+\z/u',
            ],
        ], [
            'filename.required' => 'اكتبي اسم الملف.',
            'filename.max' => 'اسم الملف طويل. اكتبي ٨٠ حرفًا أو أقل.',
            'filename.regex' => 'استخدمي الحروف والأرقام والمسافات أو الشرطة والنقطة في اسم الملف.',
        ]);

        // إزالة امتداد Excel إذا أدخله المستخدم.
        $basename = preg_replace(
            '/\.xlsx$/i',
            '',
            $validated['filename']
        );

        $basename = trim($basename, " ._-");

        $filename = (
            $basename !== ''
                ? $basename
                : 'tinyguide-system-logs'
        ) . '.xlsx';

        // جلب جميع السجلات المطابقة للفلاتر.
        $query = $this->filteredLogs($filters);

        $labels = $this->labels();

        // إنشاء ملف مؤقت.
        $tempPath = tempnam(
            sys_get_temp_dir(),
            'tinyguide_logs_'
        );

        if ($tempPath === false) {
            abort(500, 'تعذر تجهيز ملف التصدير.');
        }

        $workbook = new Spreadsheet();

        try {

            $sheet = $workbook->getActiveSheet();

            $sheet->setTitle('سجل النظام');

            // اتجاه الورقة من اليمين إلى اليسار.
            $sheet->setRightToLeft(true);

            // عناوين الأعمدة.
            $sheet->fromArray([
                [
                    'رقم السجل',
                    'نوع الحدث',
                    'العملية',
                    'المستخدم',
                    'المستوى',
                    'التاريخ والوقت',
                ],
            ], null, 'A1');

            // تنسيق العناوين.
            $sheet->getStyle('A1:F1')
                ->getFont()
                ->setBold(true);

            $sheet->freezePane('A2');

            // عرض الأعمدة.
            foreach ([
                'A' => 16,
                'B' => 21,
                'C' => 42,
                'D' => 30,
                'E' => 15,
                'F' => 23,
            ] as $column => $width) {

                $sheet->getColumnDimension($column)
                    ->setWidth($width);
            }

            $row = 2;

            // قراءة السجلات على دفعات.
            foreach ($query->lazy(250) as $log) {

                $actionKey = strtolower(
                    str_replace(
                        [' ', '-'],
                        '_',
                        trim((string) $log->action)
                    )
                );

                $userName = $log->user?->name
                    ?? (
                        $log->user_id
                            ? 'مستخدم غير متاح'
                            : 'النظام'
                    );

                $values = [
                    'A' => (string) $log->system_log_id,

                    'B' => $labels['eventTypeLabels'][$log->event_type]
                        ?? $log->event_type,

                    'C' => $labels['actionLabels'][$actionKey]
                        ?? $log->action,

                    'D' => $userName,

                    'E' => $labels['severityLabels'][$log->severity]
                        ?? $log->severity,

                    'F' => $log->occurred_at?->format('Y-m-d H:i:s')
                        ?? '—',
                ];

                foreach ($values as $column => $value) {

                    $sheet->setCellValueExplicit(
                        $column . $row,
                        (string) $value,
                        DataType::TYPE_STRING
                    );
                }

                $row++;
            }

            // حفظ ملف Excel في المسار المؤقت.
            $writer = new Xlsx($workbook);

            $writer->save($tempPath);

        } catch (\Throwable $e) {

            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            throw $e;

        } finally {

            $workbook->disconnectWorksheets();
        }

        // إرسال الملف إلى المستخدم وتنظيف الملف المؤقت.
        return response()->download(
            $tempPath,
            $filename,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'Cache-Control' => 'private, no-store',
            ]
        )->deleteFileAfterSend(true);
    }

    /**
     * عرض تفاصيل سجل معين.
     */
    public function show($id)
    {
        $log = SystemLog::with('user')->findOrFail($id);

        return view('system-log.show', array_merge(
            $this->labels(),
            ['log' => $log]
        ));
    }

    /**
     * التحقق من الفلاتر.
     */
    private function validateFilters(Request $request): array
    {
        $endDateRules = [
            'nullable',
            'date_format:Y-m-d',
        ];

        if ($request->filled('date_from')) {
            $endDateRules[] = 'after_or_equal:date_from';
        }

        return $request->validate([

            'event_type' => [
                'nullable',
                'in:api_request,admin_activity,user_activity,security,error,backup',
            ],

            'severity' => [
                'nullable',
                'in:info,warning,error,critical',
            ],

            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'date_from' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            'date_to' => $endDateRules,

        ]);
    }

    /**
     * تجهيز الاستعلام مع الفلاتر والبحث.
     */
    private function filteredLogs(array $filters): Builder
    {
        $query = $this->filterDates(
            SystemLog::with('user'),
            $filters
        );

        if (!empty($filters['event_type'])) {

            $query->where(
                'event_type',
                $filters['event_type']
            );
        }

        if (!empty($filters['severity'])) {

            $query->where(
                'severity',
                $filters['severity']
            );
        }

        $search = trim($filters['search'] ?? '');

        if ($search !== '') {

            $pattern = '%' . $search . '%';

            $query->where(function (Builder $searchQuery) use ($pattern) {

                $searchQuery
                    ->where('action', 'like', $pattern)

                    ->orWhere('description', 'like', $pattern)

                    ->orWhere('event_type', 'like', $pattern)

                    ->orWhere('severity', 'like', $pattern)

                    ->orWhere('user_id', 'like', $pattern)

                    ->orWhereHas('user', function (Builder $userQuery) use ($pattern) {

                        $userQuery->where('name', 'like', $pattern);

                    });

            });
        }

        return $query
            ->orderByDesc('occurred_at')
            ->orderByDesc('system_log_id');
    }

    /**
     * تطبيق فلتر التاريخ.
     */
    private function filterDates(Builder $query, array $filters): Builder
    {
        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'occurred_at',
                '>=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'occurred_at',
                '<=',
                $filters['date_to']
            );
        }

        return $query;
    }

    /**
     * التسميات العربية لأنواع الأحداث والعمليات.
     */
    private function labels(): array
    {
        return [

            'eventTypeLabels' => [

                'api_request' => 'طلب API',

                'admin_activity' => 'نشاط إداري',

                'user_activity' => 'نشاط مستخدم',

                'security' => 'حدث أمني',

                'error' => 'خطأ تقني',

                'backup' => 'نسخ احتياطي',

            ],

            'severityLabels' => [

                'info' => 'معلومات',

                'warning' => 'تحذير',

                'error' => 'خطأ',

                'critical' => 'حرج',

            ],

            'actionLabels' => [

                'admin_login_success' => 'تسجيل دخول ناجح',

                'admin_login_failed' => 'محاولة تسجيل دخول فاشلة',

                'admin_logout' => 'تسجيل خروج',

                'api_request_success' => 'نجاح طلب API',

                'api_request_failed' => 'فشل طلب API',

            ],

        ];
    }
}
