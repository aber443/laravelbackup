<!DOCTYPE html>

<html lang="ar" dir="rtl">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <title>سجل أنشطة النظام | TinyGuide</title>



    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">



    <style>

        * {

            box-sizing: border-box;

        }



        body {

            font-family: Arial, sans-serif;

            background: #F1F8F8;

            color: #2C4A5A;

        }



        /* رأس الصفحة */



        .logs-page-header,

        .logs-filter-card,

        .logs-table-card {

            background: #fff;

            border: 1px solid #D8E9E8;

            border-radius: 15px;

            box-shadow: 0 3px 12px rgba(44, 74, 90, 0.04);

        }



        .logs-page-header {

            padding: 23px 25px;

            margin-bottom: 20px;

        }



        .logs-page-header h1 {

            font-size: 22px;

            margin-bottom: 9px;

        }



        .logs-page-header p {

            color: #78909A;

            font-size: 13px;

            line-height: 1.8;

        }



        /* البطاقات الإحصائية */



        .logs-summary {

            display: grid;

            grid-template-columns: repeat(3, minmax(0, 1fr));

            gap: 14px;

            margin-bottom: 22px;

        }



        .logs-summary-card {

            background: #fff;

            border: 1.5px solid #64C7C4;

            border-radius: 14px;

            padding: 17px 15px;

            min-width: 0;

            box-shadow: 0 3px 12px rgba(44, 74, 90, 0.05);

            transition: 0.2s ease;

        }



        .logs-summary-card:hover {

            transform: translateY(-3px);

            box-shadow: 0 5px 15px rgba(44, 74, 90, 0.09);

        }



        .summary-total {

            border-color: #64C7C4;

        }



        .summary-api {

            border-color: #568EC2;

        }



        .summary-success {

            border-color: #239A70;

        }



        .summary-failed {

            border-color: #D46A6A;

        }



        .summary-security {

            border-color: #D6A34E;

        }



        .summary-icon {

            font-size: 21px;

            margin-bottom: 10px;

        }



        .summary-label {

            color: #607D86;

            font-size: 12px;

            font-weight: bold;

            margin-bottom: 9px;

        }



        .summary-value {

            font-size: 26px;

            font-weight: bold;

            color: #2C4A5A;

        }



        .summary-description {

            color: #8AA1AA;

            font-size: 10px;

            margin-top: 7px;

            line-height: 1.6;

        }



        /* البحث */



        .logs-filter-card {

            padding: 20px;

            margin-bottom: 22px;

        }



        .logs-section-title {

            font-size: 17px;

            font-weight: bold;

            margin-bottom: 16px;

        }



        .logs-search-form {

            display: flex;

            align-items: center;

            gap: 12px;

        }



        .logs-search-input {

            flex: 1;

            min-width: 0;

            padding: 13px 15px;

            border: 1px solid #DCEAEA;

            border-radius: 9px;

            background: #fff;

            color: #2C4A5A;

            font-family: Arial, sans-serif;

            font-size: 13px;

            outline: none;

        }



        .logs-search-input:focus {

            border-color: #64C7C4;

            box-shadow: 0 0 0 3px rgba(100, 199, 196, 0.12);

        }



        .logs-search-input::placeholder {

            color: #A0B1B7;

        }



        .logs-btn {

            display: inline-flex;

            justify-content: center;

            align-items: center;

            gap: 7px;

            padding: 12px 22px;

            border: none;

            border-radius: 9px;

            background: #64C7C4;

            color: white;

            font-family: Arial, sans-serif;

            font-size: 13px;

            cursor: pointer;

            transition: 0.2s;

            white-space: nowrap;

        }



        .logs-btn:hover {

            background: #48B4B0;

        }



        /* جدول السجلات */



        .logs-table-card {

            padding: 22px;

            margin-bottom: 25px;

        }



        .logs-table-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 12px;

            flex-wrap: wrap;

            margin-bottom: 17px;

        }



        .logs-table-header h2 {

            font-size: 17px;

            margin-bottom: 5px;

        }



        .logs-table-header p {

            font-size: 11px;

            color: #8AA1AA;

        }



        .logs-results-count {

            background: #E8F7F6;

            color: #359C98;

            padding: 8px 13px;

            border-radius: 9px;

            font-size: 12px;

            font-weight: bold;

        }



        .logs-table-wrapper {

            width: 100%;

            overflow-x: auto;

            border: 1px solid #E4EEEE;

            border-radius: 10px;

        }



        .logs-table {

            width: 100%;

            min-width: 650px;

            border-collapse: collapse;

        }



        .logs-table th {

            background: #E8F7F6;

            color: #2C4A5A;

            padding: 14px 12px;

            font-size: 12px;

            text-align: right;

            white-space: nowrap;

        }



        .logs-table td {

            padding: 15px 12px;

            border-bottom: 1px solid #EDF3F3;

            font-size: 12px;

            vertical-align: middle;

        }



        .logs-table tbody tr:last-child td {

            border-bottom: none;

        }



        .logs-table tbody tr:hover {

            background: #F8FBFB;

        }



        .log-id {

            color: #8AA1AA;

            font-weight: bold;

        }



        .log-type,

        .log-status {

            display: inline-block;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 10px;

            white-space: nowrap;

            font-weight: bold;

        }



        /* أنواع الأحداث */



        .type-api {

            background: #E8F1FA;

            color: #4779A9;

        }



        .type-admin {

            background: #E8F7F6;

            color: #359C98;

        }



        .type-user {

            background: #F0ECFA;

            color: #8066B4;

        }



        .type-security {

            background: #FFF4DF;

            color: #AD7925;

        }



        .type-error {

            background: #FCE8E8;

            color: #C74747;

        }



        .type-backup {

            background: #E7F4EA;

            color: #39845A;

        }



        /* حالة العملية */



        .status-success {

            background: #E1F6EA;

            color: #238A56;

        }



        .status-failed {

            background: #FCE8E8;

            color: #C74747;

        }



        .status-warning {

            background: #FFF4DF;

            color: #AD7925;

        }



        .status-recorded {

            background: #E8F7F6;

            color: #359C98;

        }



        .status-redirect {

            background: #EDF2F2;

            color: #607D86;

        }



        /* المستخدم */



        .log-user {

            font-weight: bold;

            color: #2C4A5A;

        }



        .log-user-system {

            color: #78909A;

            font-weight: normal;

        }



        /* التاريخ */



        .log-date {

            color: #2C4A5A;

            white-space: nowrap;

        }



        .log-time {

            display: block;

            margin-top: 5px;

            color: #8AA1AA;

            font-size: 10px;

        }



        /* لا توجد نتائج */



        .logs-empty {

            text-align: center;

            padding: 40px 15px !important;

            color: #8AA1AA;

            line-height: 1.8;

        }



        .logs-empty-icon {

            font-size: 30px;

            display: block;

            margin-bottom: 10px;

        }



        /* ترقيم الصفحات */



        .logs-pagination {

            margin-top: 20px;

        }



        .logs-pagination {

            display: flex;

            justify-content: center;

            flex-wrap: wrap;

            gap: 10px;

        }



        .logs-page-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            direction: rtl;

            padding: 8px 16px;

            border: 1px solid #A8DEDC;

            border-radius: 9px;

            background: #E8F7F6;

            color: #2C4A5A;

            text-decoration: none;

        }



        .logs-page-link[aria-disabled="true"] {

            opacity: .5;

        }



        /* رسائل النظام */



        .logs-message {

            padding: 13px 16px;

            border-radius: 10px;

            font-size: 12px;

            margin-bottom: 16px;

            border: 1px solid transparent;

        }



        .logs-message-success {

            background: #E1F6EA;

            color: #238A56;

            border-color: #C7E9D3;

        }



        .logs-message-error {

            background: #FCE8E8;

            color: #C74747;

            border-color: #F1D0D0;

        }



        /* الشاشات الصغيرة */



        @media (max-width: 1150px) {

            .logs-summary {

                grid-template-columns: repeat(3, minmax(0, 1fr));

            }

        }



        @media (max-width: 700px) {

            .logs-summary {

                grid-template-columns: repeat(2, minmax(0, 1fr));

            }



            .logs-page-header,

            .logs-filter-card,

            .logs-table-card {

                padding: 16px;

            }



            .logs-search-form {

                flex-direction: column;

                align-items: stretch;

            }



            .logs-btn {

                width: 100%;

            }

        }



        @media (max-width: 420px) {

            .logs-summary {

                grid-template-columns: 1fr;

            }

        }

        .logs-filter-card .logs-search-form {

            display: grid;

            grid-template-columns: minmax(0, 290px) auto;

            justify-content: start;

            align-items: end;

        }



        .logs-search-form label, .logs-export-dialog label {

            display: grid;

            gap: 6px;

            font-size: 14px;

        }



        .logs-export-dialog {

            width: min(440px, calc(100% - 32px));

            box-sizing: border-box;

            margin: auto;

            padding: 24px;

            border: 1px solid #A8DEDC;

            border-radius: 18px;

            background: #fff;

            color: #2C4A5A;

        }



        .logs-export-dialog::backdrop {

            background: rgb(44 74 90 / 40%);

        }



        .logs-export-dialog p {

            margin: 12px 0;

        }



        .logs-export-actions {

            display: flex;

            flex-wrap: wrap;

            gap: 10px;

            margin-top: 16px;

        }



        .logs-export-actions .logs-cancel {

            background: #E8F7F6;

            color: #2C4A5A;

        }



        .logs-table-header {

            margin-bottom: 24px;

        }



        .logs-request-breakdown {

            display: flex;

            flex-wrap: wrap;

            gap: 5px 12px;

        }



        @media (max-width: 520px) {

            .logs-filter-card .logs-search-form {

                grid-template-columns: 1fr;

            }

        }

    </style>

</head>



<body>



<div class="dashboard">



    @include('partials.admin-sidebar')



    <main class="main-content">



        <!-- رأس الصفحة -->



        <div class="logs-page-header">



            <h1>📋 سجل أنشطة النظام</h1>



            <p>

                متابعة العمليات والأنشطة التي تحدث داخل نظام TinyGuide.

            </p>



        </div>



        <!-- رسائل النظام -->



        @if(session('success'))



            <div class="logs-message logs-message-success">

                {{ session('success') }}

            </div>



        @endif



        @if($errors->any())



            <div class="logs-message logs-message-error">



                @foreach($errors->all() as $error)

                    <div>{{ $error }}</div>

                @endforeach



            </div>



        @endif



        <!-- تجهيز المسميات -->



        @php



            $eventTypeLabels = $eventTypeLabels ?? [

                'api_request' => 'طلب من التطبيق',

                'admin_activity' => 'نشاط إداري',

                'user_activity' => 'نشاط مستخدم',

                'security' => 'حدث أمني',

                'error' => 'خطأ تقني',

                'backup' => 'نسخ احتياطي',

            ];



            $eventTypeLabels['api_request'] = 'طلب من التطبيق';



            $actionLabels = array_replace($actionLabels ?? [], [

                'api_request_success' => 'نجاح طلب التطبيق',

                'api_request_failed' => 'فشل طلب التطبيق',

            ]);



            $eventClasses = [

                'api_request' => 'type-api',

                'admin_activity' => 'type-admin',

                'user_activity' => 'type-user',

                'security' => 'type-security',

                'error' => 'type-error',

                'backup' => 'type-backup',

            ];



        @endphp



        <!-- البطاقات الإحصائية -->



        <section class="logs-summary">



            <div class="logs-summary-card summary-total">



                <div class="summary-icon">📋</div>



                <div class="summary-label">

                    إجمالي السجلات

                </div>



                <div class="summary-value">

                    {{ number_format($totalLogs) }}

                </div>



                <div class="summary-description">

                    جميع العمليات المسجلة

                </div>



            </div>



            <div class="logs-summary-card summary-api">

                <div class="summary-icon">🔄</div>

                <div class="summary-label">طلبات التطبيق</div>

                <div class="summary-value">{{ number_format($totalApiRequests) }}</div>

                <div class="summary-description logs-request-breakdown">

                    <span>{{ number_format($successfulApiRequests) }} ناجحة</span>

                    <span>{{ number_format($failedApiRequests) }} فاشلة</span>

                </div>

            </div>



            <div class="logs-summary-card summary-security">



                <div class="summary-icon">🛡️</div>



                <div class="summary-label">

                    التنبيهات الأمنية

                </div>



                <div class="summary-value">

                    {{ number_format($securityAlerts) }}

                </div>



                <div class="summary-description">

                    الأحداث الأمنية المسجلة

                </div>



            </div>



        </section>



        <!-- البحث -->



        <section class="logs-filter-card">



            <h2 class="logs-section-title">

                تصفية السجلات

            </h2>



            <form

                method="GET"

                action="{{ route('system-logs.index') }}"

                class="logs-search-form"

            >



                <label>

                    نوع الحدث

                    <select name="event_type" class="logs-search-input">

                        <option value="">جميع الأنواع</option>

                        @foreach ($eventTypeLabels as $value => $label)

                            <option value="{{ $value }}" @selected(($filters['event_type'] ?? '') === $value)>

                                {{ $label }}

                            </option>

                        @endforeach

                    </select>

                </label>



                <button type="submit" class="logs-btn">عرض النتائج</button>



            </form>



        </section>



        <!-- جدول السجلات -->



        <section class="logs-table-card">



            <div class="logs-table-header">



                <div>



                    <h2>سجل العمليات</h2>



                    <p>

                        أحدث العمليات المسجلة تظهر أولاً.

                    </p>



                </div>



                @if ($logs->total() > 0)

                    <button type="button" class="logs-btn" id="logs-open-export">

                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"

                             stroke="currentColor" stroke-width="2" stroke-linecap="round"

                             stroke-linejoin="round" aria-hidden="true" focusable="false">

                            <path d="M12 3v12m-5-5 5 5 5-5M4 16v4h16v-4"/>

                        </svg>

                        تجهيز التصدير

                    </button>

                @endif

            </div>



            <div class="logs-table-wrapper">



                <table class="logs-table">



                    <thead>



                        <tr>



                            <th>#</th>



                            <th>نوع الحدث</th>



                            <th>العملية</th>



                            <th>المستخدم</th>



                            <th>المستوى</th>



                            <th>التاريخ والوقت</th>



                        </tr>



                    </thead>



                    <tbody>



                        @forelse($logs as $log)



                            @php



                                // نوع الحدث



                                $eventType = $log->event_type;



                                $eventLabel = $eventTypeLabels[$eventType]

                                    ?? $eventType;



                                $eventClass = $eventClasses[$eventType]

                                    ?? 'type-user';



                                // اسم العملية



                                $actionKey = strtolower(

                                    str_replace(

                                        [' ', '-'],

                                        '_',

                                        trim((string) $log->action)

                                    )

                                );



                                $actionLabel = $actionLabels[$actionKey]

                                    ?? $actionLabels[$log->action]

                                    ?? $log->action

                                    ?? 'عملية غير محددة';



                                $levelLabel = $severityLabels[$log->severity] ?? $log->severity;

                                $statusClass = match ($log->severity) {

                                    'warning' => 'status-warning',

                                    'error', 'critical' => 'status-failed',

                                    default => 'status-recorded',

                                };



                            @endphp



                            <tr>



                                <!-- رقم السجل -->



                                <td class="log-id">

                                    #{{ $log->system_log_id }}

                                </td>



                                <!-- نوع الحدث -->



                                <td>



                                    <span class="log-type {{ $eventClass }}">

                                        {{ $eventLabel }}

                                    </span>



                                </td>



                                <!-- العملية -->



                                <td>

                                    <strong>{{ $actionLabel }}</strong>

                                </td>



                                <!-- المستخدم -->



                                <td>



                                    @if($log->user)



                                        <span class="log-user">

                                            {{ $log->user->name }}

                                        </span>



                                    @elseif($log->user_id)



                                        <span class="log-user log-user-system">

                                            مستخدم غير متاح

                                        </span>



                                    @else



                                        <span class="log-user log-user-system">

                                            النظام

                                        </span>



                                    @endif



                                </td>



                                <!-- مستوى الحدث -->



                                <td>



                                    <span class="log-status {{ $statusClass }}">

                                        {{ $levelLabel }}

                                    </span>



                                </td>



                                <!-- التاريخ والوقت -->



                                <td class="log-date">



                                    {{ $log->occurred_at?->format('Y-m-d') ?? '—' }}



                                    <span class="log-time">

                                        {{ $log->occurred_at?->format('H:i:s') ?? '—' }}

                                    </span>



                                </td>



                            </tr>



                        @empty



                            <tr>



                                <td colspan="6" class="logs-empty">



                                    <span class="logs-empty-icon">

                                        📂

                                    </span>



                                    لا توجد سجلات مطابقة للنوع المحدد.



                                </td>



                            </tr>



                        @endforelse



                    </tbody>



                </table>



            </div>



            @if ($logs->hasPages())

                <nav class="logs-pagination" aria-label="التنقل بين صفحات السجل">

                    @if ($logs->previousPageUrl())

                        <a class="logs-page-link" rel="prev" href="{{ $logs->previousPageUrl() }}">

                            السابق <span aria-hidden="true">›</span>

                        </a>

                    @else

                        <span class="logs-page-link" aria-disabled="true">

                            السابق <span aria-hidden="true">›</span>

                        </span>

                    @endif



                    @if ($logs->nextPageUrl())

                        <a class="logs-page-link" rel="next" href="{{ $logs->nextPageUrl() }}">

                            التالي <span aria-hidden="true">›</span>

                        </a>

                    @else

                        <span class="logs-page-link" aria-disabled="true">

                            التالي <span aria-hidden="true">›</span>

                        </span>

                    @endif

                </nav>

            @endif



        </section>



        @if ($logs->total() > 0)

            <dialog class="logs-export-dialog" id="logs-export-dialog"

                    aria-labelledby="logs-export-title"

                    data-open="{{ request()->boolean('prepare_export') || $errors->has('filename') ? '1' : '0' }}">

                <h2 id="logs-export-title">تصدير نتائج سجل النظام</h2>

                <p>سيضم الملف {{ number_format($logs->total()) }} سجلًا مطابقًا للفلاتر المطبّقة على الجدول، بما فيها نتائج الصفحات الأخرى.</p>



                <form method="GET" action="{{ route('system-logs.export') }}">

                    @foreach ($filters as $field => $value)

                        @if ($value !== null && $value !== '')

                            <input type="hidden" name="{{ $field }}" value="{{ $value }}">

                        @endif

                    @endforeach



                    <label for="logs-filename">

                        اسم الملف

                        <input type="text" name="filename" id="logs-filename"

                               class="logs-search-input" dir="auto" required maxlength="80"

                               value="{{ old('filename', 'سجل النظام - ' . now()->toDateString()) }}">

                    </label>

                    @error('filename')

                        <p role="alert">{{ $message }}</p>

                    @enderror



                    <div class="logs-export-actions">

                        <button type="submit" class="logs-btn">تنزيل ملف إكسل</button>

                        <button type="button" class="logs-btn logs-cancel" id="logs-cancel-export">

                            إلغاء

                        </button>

                    </div>

                </form>

            </dialog>

        @endif



    </main>



</div>



<script>

    (() => {

        const dialog = document.getElementById('logs-export-dialog');



        if (!dialog) {

            return;

        }



        document.getElementById('logs-open-export').addEventListener('click', () => dialog.showModal());

        document.getElementById('logs-cancel-export').addEventListener('click', () => dialog.close());



        dialog.querySelector('form').addEventListener('submit', () => {

            dialog.close();

        });



        if (dialog.dataset.open === '1') {

            dialog.showModal();

        }

    })();

</script>

</body>

</html>
