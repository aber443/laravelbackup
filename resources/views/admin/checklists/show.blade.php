<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $checklist->title }} | TinyGuide</title>

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

    <style>

        .tg-checklists {min-width:0;padding:clamp(16px,3vw,30px);background:#F1F8F8;color:#2C4A5A;font:16px/1.6 Arial,sans-serif}

        .tg-checklists, .tg-checklists *, .tg-checklists *::before, .tg-checklists *::after {box-sizing:border-box}

        .tg-checklists .check-frame {width:min(100%,860px);margin-inline:auto;padding:clamp(12px,3vw,22px);border:1px solid #A8DEDC;border-radius:20px;background:#EAF8F8}

        .tg-checklists .check-panel {position:relative;width:100%;margin-bottom:12px;padding:clamp(16px,3vw,24px);border:1px solid #A8DEDC;border-radius:18px;background:#fff;text-align:center}

        .tg-checklists .check-panel:last-child {margin-bottom:0}

        .tg-checklists h1,.tg-checklists h2,.tg-checklists h3 {margin:0 0 10px;overflow-wrap:anywhere}

        .tg-checklists .check-button,.tg-checklists .check-input {padding:8px 12px;border:1px solid #A8DEDC;border-radius:9px;color:inherit;font:inherit}

        .tg-checklists .check-button {display:inline-block;background:#E8F7F6;text-decoration:none;cursor:pointer}

        .tg-checklists .check-primary {border-color:#399D9A;background:#64C7C4;font-weight:700}

        .tg-checklists .check-input {min-width:0;background:#fff}

        .tg-checklists .check-form {display:flex;flex-wrap:wrap;justify-content:center;gap:8px;max-width:345px;margin-inline:auto}

        .tg-checklists .check-form .check-input {flex:1 1 165px}

        .tg-checklists .check-button:focus-visible,.tg-checklists .check-input:focus-visible {outline:2px solid #399D9A;outline-offset:2px}

        .tg-checklists .check-progress {display:block;width:100%;max-width:420px;height:10px;margin:8px auto 14px;appearance:none;border:1px solid #A8DEDC;border-radius:999px;overflow:hidden;background:#fff;color:#399D9A}

        .tg-checklists .check-progress::-webkit-progress-bar {background:#fff}

        .tg-checklists .check-progress::-webkit-progress-value,.tg-checklists .check-progress::-moz-progress-bar {background:#399D9A}

        .tg-checklists .check-grid {display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin:0;padding:0;list-style:none;text-align:start}

        .tg-checklists .check-tile {position:relative;display:grid;grid-template-rows:minmax(0,1fr) auto;gap:8px;flex:0 0 auto;width:210px;max-width:100%;min-height:136px;padding:12px;border:1px solid #A8DEDC;border-radius:12px;background:#F1F8F8;overflow-wrap:anywhere}

        .tg-checklists .check-tile h3 {margin-bottom:6px;padding-inline-end:28px;font-size:16px}

        .tg-checklists .check-tile p {margin:0;font-size:14px}

        .tg-checklists .check-task-state {color:#526D78;font-size:14px}

        .tg-checklists .check-task-done {border-color:#7BC3AA;background:#F0FAF5}

        .tg-checklists .check-task-done .check-task-state {color:#237457;font-weight:700}

        .tg-checklists .check-tile-actions {align-self:end}

        .tg-checklists .check-error {margin:8px 0;color:#9A3838}

        .tg-checklists .check-menu {position:absolute;z-index:2;inset-block-start:10px;inset-inline-end:10px;text-align:start}

        .tg-checklists .check-menu[open] {z-index:3}

        .tg-checklists .check-menu summary {display:grid;width:28px;height:28px;margin-inline-start:auto;place-items:center;list-style:none;border:1px solid #A8DEDC;border-radius:8px;background:#fff;cursor:pointer}

        .tg-checklists .check-menu summary::-webkit-details-marker {display:none}

        .tg-checklists .check-menu-popover {position:absolute;inset-block-start:calc(100% + 6px);inset-inline-end:0;min-width:110px;padding:4px;border:1px solid #A8DEDC;border-radius:9px;background:#fff;box-shadow:0 4px 14px rgb(44 74 90 / 15%)}

        .tg-checklists .check-menu-popover form {margin:0}

        .tg-checklists .check-menu-option {display:block;width:100%;padding:6px 10px;border:0;border-radius:6px;background:transparent;color:#2C4A5A;font:inherit;text-align:start;cursor:pointer}

        .tg-checklists .check-menu-option:hover,.tg-checklists .check-menu-option:focus-visible {background:#E8F7F6}

        .tg-checklists .check-menu-delete {color:#9A3838}

        .tg-checklists .check-edit-dialog {width:min(420px,calc(100% - 32px));margin:auto;padding:20px;border:1px solid #399D9A;border-radius:16px;background:#fff;color:#2C4A5A;text-align:start}

        .tg-checklists .check-edit-dialog::backdrop {background:rgb(44 74 90 / 35%)}

        .tg-checklists .check-edit-dialog .check-input {display:block;width:100%;margin:6px 0 12px}

        .tg-checklists .check-edit-actions {display:flex;flex-wrap:wrap;gap:8px}

        .tg-checklists .check-toast {position:fixed;z-index:50;inset-block-start:16px;inset-inline-end:16px;display:flex;align-items:center;gap:8px;max-width:min(320px,calc(100% - 32px));padding:8px 12px;border:1px solid #7BC3AA;border-radius:10px;background:#F0FAF5;color:#237457;box-shadow:0 4px 16px rgb(44 74 90 / 12%)}

        /* Service connectivity dashboard: same order and matching TinyGuide icons */

        .tg-checklists .service-heading {padding:26px 18px}

        .tg-checklists .service-heading h1 {margin-bottom:12px;font-size:clamp(22px,3vw,29px);font-weight:600}

        .tg-checklists .service-grid {display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}

        .tg-checklists .service-card {display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:184px;padding:20px 14px;border:1px solid #A8DEDC;border-radius:15px;background:#F8FCFC;text-align:center}

        .tg-checklists .service-icon {display:grid;width:46px;height:46px;margin-bottom:12px;place-items:center;border:1px solid #BCE5E3;border-radius:13px;background:#E8F7F6;color:#399D9A}

        .tg-checklists .service-icon svg {width:25px;height:25px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}

        .tg-checklists .service-card h3 {margin-bottom:5px;font-size:16px;font-weight:600}

        .tg-checklists .service-status {display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:5px 12px;border:1px solid #D6E3E7;border-radius:20px;background:#F0F4F6;color:#526D78;font-size:13px}

        .tg-checklists .service-status.passed {border-color:#A8DEDC;background:#E8F7F6;color:#2C7775}

        .tg-checklists .service-status.failed {border-color:#F0C6C6;background:#FFF2F2;color:#A33E3E}

        .tg-checklists .service-status.warning {border-color:#EEDBAA;background:#FFF9EA;color:#94691F}

        .tg-checklists .service-section-title {margin-bottom:20px;font-size:20px;font-weight:600}

        .tg-checklists .service-meta {margin:7px 0 0;color:#617985;font-size:12px;line-height:1.5}

        @media(max-width:520px) {.tg-checklists .service-grid {grid-template-columns:1fr}.tg-checklists .service-card {min-height:160px}}

    </style>

</head>

<body>

@php
    $isServiceChecklist = $checklist->title === 'سلامة الخدمات والاتصال';

    $databaseStatus = 'pending';
    $apiStatus = 'pending';
    $apiResponseTime = null;
    $apiCheckedAt = null;

    $problemStatus = 'pending';
    $problemCount = 0;

    if ($isServiceChecklist) {
        try {
            \Illuminate\Support\Facades\DB::select('SELECT 1 AS connection_test');
            $databaseStatus = 'passed';
        } catch (\Throwable $exception) {
            report($exception);
            $databaseStatus = 'failed';
        }

        try {
            $apiCheck = \App\Models\SystemHealthCheck::query()
                ->where('check_key', 'api_health')
                ->orderByDesc('checked_at')
                ->orderByDesc('id')
                ->first();

            if ($apiCheck) {
                $apiStatus = $apiCheck->status;
                $apiResponseTime = data_get($apiCheck->details, 'response_time_ms');
                $apiCheckedAt = $apiCheck->checked_at;
            }

            $latestResults = \App\Models\SystemHealthCheck::query()
                ->orderByDesc('checked_at')
                ->orderByDesc('id')
                ->get()
                ->unique('check_key');

            if ($latestResults->isEmpty()) {
                $problemStatus = 'pending';
            } else {
                $problems = $latestResults->filter(
                    fn ($check) => in_array($check->status, ['failed', 'warning'], true)
                );

                $problemCount = $problems->count();
                $problemStatus = $problemCount > 0 ? 'warning' : 'passed';
            }
        } catch (\Throwable $exception) {
            report($exception);
            $apiStatus = 'pending';
            $problemStatus = 'pending';
        }
    }

    $apiStatusLabel = match ($apiStatus) {
        'passed' => 'تعمل',
        'failed' => 'لا تستجيب',
        'warning' => 'تحتاج مراجعة',
        default => 'لم يتم الفحص بعد',
    };
@endphp

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content tg-checklists">

        <div class="check-frame">

            @if ($isServiceChecklist)

                <section class="check-panel service-heading">

                    <h1>سلامة الخدمات والاتصال</h1>

                    <a class="check-button" href="{{ route('admin.checklists.index') }}">العودة إلى القوائم</a>

                </section>

                <section class="check-panel" aria-labelledby="service-cards-heading">

                    <h2 id="service-cards-heading" class="service-section-title">حالة الخدمات</h2>

                    <div class="service-grid">

                        <article class="service-card">

                            <div class="service-icon" aria-hidden="true">

                                <svg viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14M20 5v14M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3M4 19c0 1.7 3.6 3 8 3s8-1.3 8-3"/></svg>

                            </div>

                            <h3>قاعدة البيانات</h3>

                            <span class="service-status {{ $databaseStatus }}">{{ $databaseStatus === 'passed' ? 'متصلة' : ($databaseStatus === 'failed' ? 'غير متصلة' : 'غير متاح') }}</span>

                        </article>

                        <article class="service-card">

                            <div class="service-icon" aria-hidden="true">

                                <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="18" rx="2"/><path d="M2 8h20m-13 4-3 3 3 3m6-6 3 3-3 3"/></svg>

                            </div>

                            <h3>واجهات API</h3>

                            <span class="service-status {{ $apiStatus }}">
                                {{ $apiStatusLabel }}
                            </span>

                            <p class="service-meta">
                                زمن الاستجابة:
                                <bdi>
                                    {{ $apiResponseTime !== null
                                        ? number_format((float) $apiResponseTime, 2) . ' ms'
                                        : 'غير متاح' }}
                                </bdi>
                            </p>

                            <p class="service-meta">
                                آخر فحص:
                                <bdi>
                                    {{ $apiCheckedAt
                                        ? \Illuminate\Support\Carbon::parse($apiCheckedAt)->format('Y-m-d h:i A')
                                        : 'لم يُنفذ بعد' }}
                                </bdi>
                            </p>

                        </article>

                        <article class="service-card">

                            <div class="service-icon" aria-hidden="true">

                                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="7" rx="2"/><rect x="3" y="14" width="18" height="7" rx="2"/><path d="M7 6.5h.01M7 17.5h.01M11 6.5h7M11 17.5h7"/></svg>

                            </div>

                            <h3>خدمات التطبيق</h3>

                            <span class="service-status">لم يتم إعداد الفحص</span>

                        </article>

                        <article class="service-card">

                            <div class="service-icon" aria-hidden="true">

                                <svg viewBox="0 0 24 24"><path d="M10.3 3.7 2.3 18a2 2 0 0 0 1.7 3h16a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0z"/><path d="M12 9v5M12 18h.01"/></svg>

                            </div>

                            <h3>المشكلات المكتشفة</h3>

                            <span class="service-status {{ $problemStatus }}">

                                @if ($problemStatus === 'pending') غير متاح

                                @elseif ($problemCount > 0) {{ $problemCount }} تحتاج مراجعة

                                @else لا توجد مشكلات مسجلة

                                @endif

                            </span>

                        </article>

                    </div>

                </section>

            @else

                <section class="check-panel check-list-summary" aria-labelledby="checklist-heading">

                    <details class="check-menu">

                        <summary aria-label="خيارات القائمة" title="خيارات القائمة"><span aria-hidden="true">⌄</span></summary>

                        <div class="check-menu-popover">

                            <button class="check-menu-option" type="button" data-edit-target="edit-list-{{ $checklist->id }}">تعديل</button>

                            <form method="POST" action="{{ route('admin.checklists.destroy', $checklist) }}" onsubmit="return confirm('تحذفي القائمة وكل المهام التابعة لها؟')">

                                @csrf @method('DELETE')

                                <button class="check-menu-option check-menu-delete" type="submit">حذف</button>

                            </form>

                        </div>

                    </details>

                    <h1 id="checklist-heading" dir="auto">{{ $checklist->title }}</h1>

                    <p>{{ $completed }} من {{ $total }} مهام مكتملة · <bdi>{{ $progress }}٪</bdi></p>

                    <progress class="check-progress" max="100" value="{{ $progress }}" aria-label="نسبة الإنجاز">{{ $progress }}٪</progress>

                    <a class="check-button" href="{{ route('admin.checklists.index') }}">العودة إلى القوائم</a>

                    <dialog id="edit-list-{{ $checklist->id }}" class="check-edit-dialog" aria-labelledby="edit-list-{{ $checklist->id }}-title">

                        <h2 id="edit-list-{{ $checklist->id }}-title">تعديل اسم القائمة</h2>

                        <form method="POST" action="{{ route('admin.checklists.update', $checklist) }}">

                            @csrf @method('PATCH')

                            <label for="edit-list-{{ $checklist->id }}-input">الاسم الجديد</label>

                            <input id="edit-list-{{ $checklist->id }}-input" class="check-input" name="new_title" type="text" value="{{ $checklist->title }}" maxlength="150" dir="auto" required autofocus>

                            <div class="check-edit-actions"><button class="check-button check-primary" type="submit">حفظ</button><button class="check-button" type="button" data-close-edit>إلغاء</button></div>

                        </form>

                    </dialog>

                </section>

                @if (session('checklist_success'))

                    <p id="checklist-toast" class="check-toast" role="status"><span aria-hidden="true">✓</span><span>{{ session('checklist_success') }}</span></p>

                @endif

                <section class="check-panel" aria-labelledby="add-task-heading">

                    <h2 id="add-task-heading">إضافة مهمة</h2>

                    <form class="check-form" method="POST" action="{{ route('admin.checklists.items.store', $checklist) }}">

                        @csrf

                        <input class="check-input" id="task-title" name="title" type="text" value="{{ old('title') }}" maxlength="200" required aria-label="اسم المهمة" placeholder="اكتبي المهمة">

                        <button class="check-button check-primary" type="submit">إضافة المهمة</button>

                        @error('title') <p class="check-error" role="alert">{{ $message }}</p> @enderror

                    </form>

                </section>

                <section class="check-panel" aria-labelledby="tasks-heading">

                    <h2 id="tasks-heading">المهام</h2>

                    @error('new_title') <p class="check-error" role="alert">{{ $message }}</p> @enderror

                    @if ($items->isEmpty())

                        <p>ما في مهام في هذه القائمة حتى الآن.</p>

                    @else

                        <ul class="check-grid">

                            @foreach ($items as $task)

                                @php($done = $task->completed_at !== null)

                                <li @class(['check-tile', 'check-task-done' => $done])>

                                    <details class="check-menu">

                                        <summary aria-label="خيارات المهمة" title="خيارات المهمة"><span aria-hidden="true">⌄</span></summary>

                                        <div class="check-menu-popover">

                                            <button class="check-menu-option" type="button" data-edit-target="edit-task-{{ $task->id }}">تعديل</button>

                                            <form method="POST" action="{{ route('admin.checklists.items.destroy', [$checklist, $task]) }}" onsubmit="return confirm('تحذفي هذه المهمة؟')">

                                                @csrf @method('DELETE')

                                                <button class="check-menu-option check-menu-delete" type="submit">حذف</button>

                                            </form>

                                        </div>

                                    </details>

                                    <div><h3 dir="auto">{{ $task->title }}</h3><span class="check-task-state">{{ $done ? 'مكتملة' : 'غير مكتملة' }}</span></div>

                                    <form class="check-tile-actions" method="POST" action="{{ route('admin.checklists.items.update', [$checklist, $task]) }}">

                                        @csrf @method('PATCH')

                                        <input type="hidden" name="completed" value="{{ $done ? '0' : '1' }}">

                                        <button type="submit" class="check-button">{{ $done ? 'إلغاء الإكمال' : 'تحديد كمكتملة' }}</button>

                                    </form>

                                    <dialog id="edit-task-{{ $task->id }}" class="check-edit-dialog" aria-labelledby="edit-task-{{ $task->id }}-title">

                                        <h2 id="edit-task-{{ $task->id }}-title">تعديل اسم المهمة</h2>

                                        <form method="POST" action="{{ route('admin.checklists.items.rename', [$checklist, $task]) }}">

                                            @csrf @method('PATCH')

                                            <label for="edit-task-{{ $task->id }}-input">الاسم الجديد</label>

                                            <input id="edit-task-{{ $task->id }}-input" class="check-input" name="new_title" type="text" value="{{ $task->title }}" maxlength="200" dir="auto" required autofocus>

                                            <div class="check-edit-actions"><button class="check-button check-primary" type="submit">حفظ</button><button class="check-button" type="button" data-close-edit>إلغاء</button></div>

                                        </form>

                                    </dialog>

                                </li>

                            @endforeach

                        </ul>

                        @error('completed') <p class="check-error" role="alert">{{ $message }}</p> @enderror

                    @endif

                </section>

            @endif

        </div>

    </main>

</div>

<script>

    const checklistToast = document.getElementById('checklist-toast');

    if (checklistToast) window.setTimeout(() => checklistToast.remove(), 4500);

    document.addEventListener('click', (event) => {

        document.querySelectorAll('.check-menu[open]').forEach((menu) => {

            if (!menu.contains(event.target)) menu.open = false;

        });

    });

    document.querySelectorAll('[data-edit-target]').forEach((button) => {

        button.addEventListener('click', () => {

            const menu = button.closest('.check-menu');

            const dialog = document.getElementById(button.dataset.editTarget);

            if (!dialog) return;

            if (menu) menu.open = false;

            dialog.showModal();

            dialog.addEventListener('close', () => menu?.querySelector('summary')?.focus(), {once:true});

        });

    });

    document.querySelectorAll('[data-close-edit]').forEach((button) => {

        button.addEventListener('click', () => button.closest('dialog')?.close());

    });

</script>

</body>

</html>
