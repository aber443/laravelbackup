<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\SystemHealthCheck;
use App\Services\SystemHealthCheckService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ChecklistController extends Controller
{
    private function fixedLists(): array
    {
        return [
            [
                'title' => 'سلامة الخدمات والاتصال',
                'description' => 'التحقق من قاعدة البيانات وواجهات API وScheduler وخدمة Firebase FCM.',
                'icon' => 'connection',
                'checks' => [
                    ['key' => 'database_connection', 'label' => 'قاعدة البيانات'],
                    ['key' => 'api_health', 'label' => 'واجهات API'],
                    ['key' => 'scheduler_status', 'label' => 'Scheduler'],
                    ['key' => 'firebase_fcm', 'label' => 'Firebase FCM'],
                ],
            ],
            [
                'title' => 'سلامة البيانات',
                'description' => 'التحقق من وجود جداول البيانات الصحية الأساسية.',
                'icon' => 'database',
                'checks' => [
                    ['key' => 'required_tables', 'label' => 'جداول البيانات الأساسية'],
                ],
            ],
            [
                'title' => 'مراقبة الإشعارات',
                'description' => 'متابعة توفر جداول الإشعارات وأجهزة المستخدمين.',
                'icon' => 'bell',
                'checks' => [
                    ['key' => 'notification_tables', 'label' => 'جداول الإشعارات والأجهزة'],
                ],
            ],
            [
                'title' => 'سلامة التقارير',
                'description' => 'التحقق من وجود جدول التقارير وأعمدته الأساسية.',
                'icon' => 'report',
                'checks' => [
                    ['key' => 'reports_table', 'label' => 'جدول التقارير'],
                ],
            ],
        ];
    }

    /**
     * صفحة قوائم التحقق.
     */
    public function index(): View
    {
        $definitions = collect($this->fixedLists());

        $checkKeys = $definitions
            ->flatMap(fn ($item) => collect($item['checks'])->pluck('key'))
            ->unique()
            ->values()
            ->all();

        // أحدث نتيجة لكل فحص.
        $latestChecks = SystemHealthCheck::query()
            ->whereIn('check_key', $checkKeys)
            ->orderByDesc('checked_at')
            ->orderByDesc('id')
            ->get()
            ->unique('check_key')
            ->keyBy('check_key');

        $checklists = Checklist::query()
            ->whereIn('title', $definitions->pluck('title'))
            ->get();

        $healthCards = $definitions->map(function ($item) use (
            $latestChecks,
            $checklists
        ) {
            $subchecks = collect($item['checks'])->map(
                function ($check) use ($latestChecks) {
                    $result = $latestChecks->get($check['key']);

                    return [
                        'key' => $check['key'],
                        'label' => $check['label'],
                        'result' => $result,
                        'status' => $result?->status ?? 'pending',
                        'message' => $result?->message ?? 'لم يتم تنفيذ الفحص بعد.',
                        'last_check' => $result?->checked_at,
                        'details' => $result?->details ?? [],
                    ];
                }
            );

            $statuses = $subchecks->pluck('status');

            if ($statuses->contains('failed')) {
                $status = 'failed';
            } elseif ($statuses->contains('warning')) {
                $status = 'warning';
            } elseif ($statuses->contains('pending')) {
                $status = 'pending';
            } else {
                $status = 'passed';
            }

            // تصحيح عدّ النتائج.
            $passed = $statuses->filter(
                fn ($value) => $value === 'passed'
            )->count();

            $warnings = $statuses->filter(
                fn ($value) => $value === 'warning'
            )->count();

            $failed = $statuses->filter(
                fn ($value) => $value === 'failed'
            )->count();

            $pending = $statuses->filter(
                fn ($value) => $value === 'pending'
            )->count();

            if ($subchecks->count() > 1) {
                $messageParts = [];

                if ($passed > 0) {
                    $messageParts[] = "{$passed} فحوصات ناجحة";
                }

                if ($warnings > 0) {
                    $messageParts[] = "{$warnings} تحتاج مراجعة";
                }

                if ($failed > 0) {
                    $messageParts[] = "{$failed} فحوصات فاشلة";
                }

                if ($pending > 0) {
                    $messageParts[] = "{$pending} بانتظار الفحص";
                }

                $message = implode('، ', $messageParts);
            } else {
                $message = $subchecks->first()['message'];
            }

            $lastSubcheck = $subchecks
                ->filter(fn ($check) => $check['last_check'] !== null)
                ->sortByDesc(
                    fn ($check) => Carbon::parse(
                        $check['last_check']
                    )->timestamp
                )
                ->first();

            $item['checks'] = $subchecks->values()->all();

            $item['record'] = $checklists->firstWhere(
                'title',
                $item['title']
            );

            $item['status'] = $status;
            $item['message'] = $message;
            $item['last_check'] = $lastSubcheck['last_check'] ?? null;

            return $item;
        });

        // إحصائيات أحدث نتيجة لكل فحص.
        $latestStatuses = collect($checkKeys)->map(
            fn ($key) => $latestChecks->get($key)?->status ?? 'pending'
        );

        $passedChecks = $latestStatuses
            ->filter(fn ($status) => $status === 'passed')
            ->count();

        $warningChecks = $latestStatuses
            ->filter(fn ($status) => $status === 'warning')
            ->count();

        $failedChecks = $latestStatuses
            ->filter(fn ($status) => $status === 'failed')
            ->count();

        $pendingChecks = $latestStatuses
            ->filter(fn ($status) => $status === 'pending')
            ->count();

        $totalHealthChecks = count($checkKeys);

        $successRate = $totalHealthChecks > 0
            ? round(($passedChecks / $totalHealthChecks) * 100, 1)
            : 0;

        // نتائج آخر سبعة أيام.
        $startDate = now()->subDays(6)->startOfDay();

        $history = SystemHealthCheck::query()
            ->whereIn('check_key', $checkKeys)
            ->where('checked_at', '>=', $startDate)
            ->orderBy('checked_at')
            ->get()
            ->groupBy(function ($check) {
                return Carbon::parse($check->checked_at)
                    ->toDateString();
            });

        $chartData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $dailyChecks = $history->get($date, collect());

            $dailyTotal = $dailyChecks->count();

            $dailyPassed = $dailyChecks
                ->filter(fn ($check) => $check->status === 'passed')
                ->count();

            $dailyRate = $dailyTotal > 0
                ? round(($dailyPassed / $dailyTotal) * 100, 1)
                : null;

            $chartData->push([
                'date' => $date,
                'label' => Carbon::parse($date)
                    ->locale('ar')
                    ->translatedFormat('d M'),
                'total' => $dailyTotal,
                'passed' => $dailyPassed,
                'success_rate' => $dailyRate,
            ]);
        }

        return view('admin.checklists.index', compact(
            'checklists',
            'healthCards',
            'passedChecks',
            'warningChecks',
            'failedChecks',
            'pendingChecks',
            'totalHealthChecks',
            'successRate',
            'chartData'
        ));
    }

    /**
     * تشغيل جميع الفحوصات.
     */
    public function runHealthChecks(
        SystemHealthCheckService $healthCheckService
    ): RedirectResponse {
        $results = $healthCheckService->runAll();

        $passed = collect($results)
            ->filter(fn ($item) => $item['status'] === 'passed')
            ->count();

        $warnings = collect($results)
            ->filter(fn ($item) => $item['status'] === 'warning')
            ->count();

        $failed = collect($results)
            ->filter(fn ($item) => $item['status'] === 'failed')
            ->count();

        $message = "اكتمل الفحص: {$passed} ناجح، "
            . "{$warnings} يحتاج مراجعة، "
            . "{$failed} فاشل.";

        return redirect()
            ->route('admin.checklists.index')
            ->with('health_check_success', $message);
    }

    /**
     * تشغيل فحص منفرد.
     */
    public function runSingleHealthCheck(
        string $checkKey,
        SystemHealthCheckService $healthCheckService
    ): RedirectResponse {
        $allChecks = collect($this->fixedLists())
            ->flatMap(fn ($item) => collect($item['checks']))
            ->keyBy('key');

        abort_unless($allChecks->has($checkKey), 404);

        $result = $healthCheckService->runOne($checkKey);

        $label = $allChecks->get($checkKey)['label'];

        $message = match ($result['status']) {
            'passed' => "تم فحص {$label} بنجاح.",
            'warning' => "فحص {$label} يحتاج إلى مراجعة.",
            'failed' => "فشل فحص {$label}.",
            default => "لم يكتمل فحص {$label}.",
        };

        return redirect()
            ->route('admin.checklists.index')
            ->with('health_check_success', $message);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
        ], [
            'title.required' => 'اكتبي اسم القائمة.',
            'title.max' => 'اسم القائمة يجب ألا يتجاوز ١٥٠ حرفًا.',
        ]);

        $checklist = Checklist::create($validated);

        return redirect()
            ->route('admin.checklists.show', $checklist)
            ->with('checklist_success', 'تم إنشاء القائمة.');
    }

    public function show(Checklist $checklist): View
    {
        $items = $checklist->items()
            ->orderBy('id')
            ->get();

        $total = $items->count();

        $completed = $items
            ->whereNotNull('completed_at')
            ->count();

        $progress = $total > 0
            ? (int) round(($completed / $total) * 100)
            : 0;

        return view('admin.checklists.show', compact(
            'checklist',
            'items',
            'total',
            'completed',
            'progress'
        ));
    }

    public function update(
        Request $request,
        Checklist $checklist
    ): RedirectResponse {
        $validated = $request->validate([
            'new_title' => ['required', 'string', 'max:150'],
        ], [
            'new_title.required' => 'اكتبي اسم القائمة.',
            'new_title.max' => 'اسم القائمة يجب ألا يتجاوز ١٥٠ حرفًا.',
        ]);

        $checklist->update([
            'title' => $validated['new_title'],
        ]);

        return back()->with(
            'checklist_success',
            'تم تعديل اسم القائمة.'
        );
    }

    public function destroy(Checklist $checklist): RedirectResponse
    {
        $checklist->delete();

        return redirect()
            ->route('admin.checklists.index')
            ->with('checklist_success', 'تم حذف القائمة ومهامها.');
    }

    public function storeItem(
        Request $request,
        Checklist $checklist
    ): RedirectResponse {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
        ], [
            'title.required' => 'اكتبي اسم المهمة.',
            'title.max' => 'اسم المهمة يجب ألا يتجاوز ٢٠٠ حرف.',
        ]);

        $checklist->items()->create($validated);

        return redirect()
            ->route('admin.checklists.show', $checklist)
            ->with('checklist_success', 'تمت إضافة المهمة.');
    }

    public function updateItem(
        Request $request,
        Checklist $checklist,
        int $item
    ): RedirectResponse {
        $validated = $request->validate([
            'completed' => ['required', 'boolean'],
        ]);

        $task = $checklist->items()->findOrFail($item);

        $completed = (bool) $validated['completed'];

        if ($completed !== ($task->completed_at !== null)) {
            $task->completed_at = $completed ? now() : null;
            $task->save();
        }

        return redirect()
            ->route('admin.checklists.show', $checklist)
            ->with(
                'checklist_success',
                $completed
                    ? 'تم تحديد المهمة كمكتملة.'
                    : 'تم إرجاع المهمة إلى غير مكتملة.'
            );
    }

    public function renameItem(
        Request $request,
        Checklist $checklist,
        int $item
    ): RedirectResponse {
        $task = $checklist->items()->findOrFail($item);

        $validated = $request->validate([
            'new_title' => ['required', 'string', 'max:200'],
        ], [
            'new_title.required' => 'اكتبي اسم المهمة.',
            'new_title.max' => 'اسم المهمة يجب ألا يتجاوز ٢٠٠ حرف.',
        ]);

        $task->update([
            'title' => $validated['new_title'],
        ]);

        return back()->with(
            'checklist_success',
            'تم تعديل اسم المهمة.'
        );
    }

    public function destroyItem(
        Checklist $checklist,
        int $item
    ): RedirectResponse {
        $checklist->items()->findOrFail($item)->delete();

        return redirect()
            ->route('admin.checklists.show', $checklist)
            ->with('checklist_success', 'تم حذف المهمة.');
    }
}
