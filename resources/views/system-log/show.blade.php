<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تفاصيل سجل النظام | TinyGuide</title>

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

        /* ===== رأس الصفحة ===== */

        .details-header {
            background: white;
            border: 1px solid #D8E9E8;
            padding: 22px 25px;
            border-radius: 14px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .details-header h1 {
            font-size: 21px;
            margin-bottom: 8px;
        }

        .details-header p {
            font-size: 12px;
            color: #78909A;
            line-height: 1.8;
        }

        .back-btn {
            background: #E8F7F6;
            color: #2C8F8C;
            border: 1px solid #CBE9E7;
            text-decoration: none;
            padding: 11px 17px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
            transition: .2s;
        }

        .back-btn:hover {
            background: #64C7C4;
            color: white;
        }

        /* ===== ملخص الحدث ===== */

        .details-status-card {
            background: white;
            border: 1px solid #D8E9E8;
            border-right: 4px solid #64C7C4;
            padding: 22px;
            border-radius: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .status-main {
            min-width: 0;
        }

        .status-main h2 {
            font-size: 18px;
            margin-bottom: 9px;
            line-height: 1.7;
        }

        .status-main p {
            font-size: 12px;
            color: #78909A;
        }

        .status-meta {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .event-badge,
        .severity {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
        }

        .event-api {
            background: #E8F1FA;
            color: #4779A9;
        }

        .event-admin {
            background: #E8F7F6;
            color: #359C98;
        }

        .event-user {
            background: #F0ECFA;
            color: #8066B4;
        }

        .event-security {
            background: #FFF4DF;
            color: #AD7925;
        }

        .event-error {
            background: #FCE8E8;
            color: #C74747;
        }

        .event-backup {
            background: #E7F4EA;
            color: #39845A;
        }

        .severity-info {
            background: #E8F7F6;
            color: #359C98;
        }

        .severity-warning {
            background: #FFF4DF;
            color: #AD7925;
        }

        .severity-error {
            background: #FCE8E8;
            color: #C74747;
        }

        .severity-critical {
            background: #C74747;
            color: white;
        }

        /* ===== توزيع البطاقات ===== */

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .details-card {
            background: white;
            border: 1px solid #D8E9E8;
            padding: 21px;
            border-radius: 13px;
            min-width: 0;
        }

        .details-card.full-width {
            grid-column: 1 / -1;
        }

        .details-card h3 {
            font-size: 15px;
            margin-bottom: 17px;
            padding-bottom: 13px;
            border-bottom: 1px solid #E8F0F0;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .section-icon {
            color: #48AAA6;
            font-size: 17px;
        }

        /* ===== صفوف المعلومات ===== */

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #F0F5F5;
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-label {
            color: #78909A;
            font-size: 12px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #2C4A5A;
            font-size: 12px;
            font-weight: bold;
            text-align: left;
            overflow-wrap: anywhere;
            line-height: 1.8;
        }

        .detail-value.ltr {
            direction: ltr;
            unicode-bidi: isolate;
        }

        .detail-muted {
            color: #9AADB3;
            font-weight: normal;
        }

        /* ===== المستخدم ===== */

        .user-profile {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 16px;
            padding: 13px;
            background: #F5FAFA;
            border: 1px solid #E4EEEE;
            border-radius: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #DDF3F1;
            color: #359C98;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 17px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .user-profile-name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .user-profile-role {
            color: #78909A;
            font-size: 11px;
        }

        /* ===== حالة استجابة API ===== */

        .response-badge {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: bold;
        }

        .response-success {
            background: #E1F6EA;
            color: #238A56;
        }

        .response-redirect {
            background: #EDF2F2;
            color: #607D86;
        }

        .response-failed {
            background: #FCE8E8;
            color: #C74747;
        }

        .response-server-error {
            background: #C74747;
            color: white;
        }

        .method-badge {
            display: inline-block;
            direction: ltr;
            padding: 6px 10px;
            background: #E8F1FA;
            color: #4779A9;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
        }

        .api-explanation {
            color: #78909A;
            font-size: 11px;
            margin-top: 6px;
            line-height: 1.7;
            font-weight: normal;
        }

        /* ===== وصف العملية ===== */

        .description-box {
            background: #F5FAFA;
            border: 1px solid #E4EEEE;
            border-radius: 10px;
            padding: 17px;
            color: #607D86;
            font-size: 13px;
            line-height: 2;
            overflow-wrap: anywhere;
        }

        /* ===== معلومات الاتصال ===== */

        .connection-note {
            color: #78909A;
            font-size: 11px;
            margin-bottom: 15px;
            line-height: 1.8;
        }

        .ip-value {
            direction: ltr;
            unicode-bidi: isolate;
            font-family: Consolas, monospace;
        }

        .technical-details {
            border: 1px solid #E4EEEE;
            border-radius: 9px;
            margin-top: 13px;
            overflow: hidden;
        }

        .technical-details summary {
            cursor: pointer;
            padding: 13px;
            font-size: 12px;
            font-weight: bold;
            color: #359C98;
            background: #F5FAFA;
        }

        .technical-details pre {
            padding: 14px;
            direction: ltr;
            text-align: left;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            font-size: 11px;
            line-height: 1.8;
            color: #607D86;
            background: white;
            margin: 0;
        }

        /* ===== البيانات الإضافية ===== */

        .metadata-details {
            border: 1px solid #D8E9E8;
            border-radius: 10px;
            overflow: hidden;
        }

        .metadata-details summary {
            padding: 15px;
            cursor: pointer;
            background: #F5FAFA;
            color: #359C98;
            font-size: 12px;
            font-weight: bold;
        }

        .metadata-details pre {
            padding: 16px;
            background: white;
            direction: ltr;
            text-align: left;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            font-family: Consolas, monospace;
            font-size: 11px;
            line-height: 1.9;
            color: #45616D;
            margin: 0;
        }

        /* ===== الشاشات الصغيرة ===== */

        @media (max-width: 800px) {
            .details-grid {
                grid-template-columns: 1fr;
            }

            .details-card.full-width {
                grid-column: auto;
            }
        }

        @media (max-width: 500px) {
            .details-header,
            .details-status-card,
            .details-card {
                padding: 16px;
            }

            .detail-row {
                flex-direction: column;
                gap: 6px;
            }

            .detail-value {
                text-align: right;
            }

            .details-status-card {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        @php

            /*
            |--------------------------------------------------------------------------
            | تجهيز بيانات الحدث
            |--------------------------------------------------------------------------
            */

            $eventLabels = $eventTypeLabels ?? [
                'api_request' => 'طلب API',
                'admin_activity' => 'نشاط إداري',
                'user_activity' => 'نشاط مستخدم',
                'security' => 'حدث أمني',
                'error' => 'خطأ تقني',
                'backup' => 'نسخ احتياطي',
            ];

            $severityLabels = $severityLabels ?? [
                'info' => 'معلومات',
                'warning' => 'تحذير',
                'error' => 'خطأ',
                'critical' => 'حرج',
            ];

            $sourceLabels = [
                'flutter' => 'تطبيق Flutter',
                'admin_panel' => 'لوحة الإدارة',
                'laravel' => 'خادم Laravel',
                'system' => 'النظام',
                'api' => 'واجهة API',
            ];

            $eventClasses = [
                'api_request' => 'event-api',
                'admin_activity' => 'event-admin',
                'user_activity' => 'event-user',
                'security' => 'event-security',
                'error' => 'event-error',
                'backup' => 'event-backup',
            ];

            $actionLabels = $actionLabels ?? [
                'admin_login_success' => 'تسجيل دخول ناجح',
                'admin_login_failed' => 'محاولة تسجيل دخول فاشلة',
                'admin_logout' => 'تسجيل خروج',
                'api_request_success' => 'نجاح طلب API',
                'api_request_failed' => 'فشل طلب API',
            ];

            $actionKey = strtolower(
                str_replace(
                    [' ', '-'],
                    '_',
                    trim((string) $log->action)
                )
            );

            $actionLabel = $actionLabels[$actionKey]
                ?? $actionLabels[$log->action]
                ?? str_replace('_', ' ', $log->action);

            $eventType = $log->event_type;

            $eventLabel = $eventLabels[$eventType] ?? $eventType;

            $eventClass = $eventClasses[$eventType] ?? 'event-user';

            $severityLabel = $severityLabels[$log->severity]
                ?? $log->severity;

            $severityClass = 'severity-' . $log->severity;

            $sourceLabel = $sourceLabels[$log->source]
                ?? ($log->source ?: 'غير محدد');

            /*
            |--------------------------------------------------------------------------
            | ترجمة دور المستخدم
            |--------------------------------------------------------------------------
            */

            $roleLabels = [
                'admin' => 'مسؤول النظام',
                'parent' => 'ولي أمر',
            ];

            /*
            |--------------------------------------------------------------------------
            | شرح طريقة طلب API
            |--------------------------------------------------------------------------
            */

            $methodLabels = [
                'GET' => 'جلب أو عرض البيانات',
                'POST' => 'إرسال بيانات أو إنشاء عملية',
                'PUT' => 'تحديث البيانات',
                'PATCH' => 'تعديل جزء من البيانات',
                'DELETE' => 'حذف بيانات',
            ];

            $method = strtoupper((string) $log->method);

            /*
            |--------------------------------------------------------------------------
            | شرح رموز استجابة HTTP
            |--------------------------------------------------------------------------
            */

            $statusCode = $log->status_code;

            $statusDescriptions = [
                200 => 'تم تنفيذ الطلب بنجاح',
                201 => 'تم إنشاء البيانات بنجاح',
                204 => 'تم تنفيذ الطلب دون محتوى في الاستجابة',

                301 => 'تم نقل الرابط بشكل دائم',
                302 => 'تم تحويل الطلب إلى صفحة أخرى',
                304 => 'لم تتغير البيانات',

                400 => 'الطلب غير صحيح',
                401 => 'يتطلب الطلب تسجيل الدخول',
                403 => 'لا توجد صلاحية لتنفيذ العملية',
                404 => 'المورد المطلوب غير موجود',
                405 => 'طريقة الطلب غير مسموح بها',
                409 => 'يوجد تعارض في البيانات',
                422 => 'البيانات المرسلة غير صالحة',
                429 => 'تم تجاوز عدد الطلبات المسموح بها',

                500 => 'حدث خطأ داخلي في الخادم',
                502 => 'استجابة غير صالحة من الخادم الوسيط',
                503 => 'الخدمة غير متاحة حاليًا',
                504 => 'انتهت مهلة انتظار استجابة الخادم',
            ];

            $statusDescription = $statusDescriptions[$statusCode]
                ?? 'رمز استجابة HTTP';

            $statusClass = 'response-redirect';

            if ($statusCode !== null) {

                if ($statusCode >= 200 && $statusCode < 300) {
                    $statusClass = 'response-success';
                } elseif ($statusCode >= 300 && $statusCode < 400) {
                    $statusClass = 'response-redirect';
                } elseif ($statusCode >= 400 && $statusCode < 500) {
                    $statusClass = 'response-failed';
                } elseif ($statusCode >= 500 && $statusCode < 600) {
                    $statusClass = 'response-server-error';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | تحديد البيانات المتوفرة
            |--------------------------------------------------------------------------
            */

            $hasUser = $log->user !== null;

            $hasConnectionInfo = !empty($log->ip_address)
                || !empty($log->user_agent);

            $hasMetadata = !empty($log->metadata);

            $hasDescription = !empty($log->description);

            $hasApiInfo = $eventType === 'api_request';

        @endphp


        <!-- رأس الصفحة -->

        <div class="details-header">

            <div>

                <h1>📋 تفاصيل سجل النظام</h1>

                <p>
                    تفاصيل العملية المسجلة في نظام TinyGuide.
                    تظهر هنا المعلومات المرتبطة بهذا الحدث فقط.
                </p>

            </div>

            <a
                href="{{ route('system-logs.index') }}"
                class="back-btn"
            >
                ← العودة إلى سجل النظام
            </a>

        </div>


        <!-- ملخص الحدث -->

        <div class="details-status-card">

            <div class="status-main">

                <h2>
                    {{ $actionLabel }}
                </h2>

                <p>
                    رقم السجل:
                    #{{ $log->system_log_id }}
                </p>

                <div class="status-meta">

                    <span class="event-badge {{ $eventClass }}">
                        {{ $eventLabel }}
                    </span>

                    <span class="severity {{ $severityClass }}">
                        {{ $severityLabel }}
                    </span>

                </div>

            </div>

        </div>


        <div class="details-grid">


            <!-- تفاصيل العملية -->

            <section class="details-card">

                <h3>
                    <span class="section-icon">📌</span>
                    تفاصيل العملية
                </h3>

                <div class="detail-row">

                    <span class="detail-label">
                        مصدر العملية
                    </span>

                    <span class="detail-value">
                        {{ $sourceLabel }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        التاريخ والوقت
                    </span>

                    <span class="detail-value">
                        {{ $log->occurred_at?->format('Y-m-d h:i:s A') ?? 'غير متوفر' }}
                    </span>

                </div>

            </section>


            <!-- المستخدم المرتبط -->

            <section class="details-card">

                <h3>
                    <span class="section-icon">👤</span>
                    المستخدم المرتبط
                </h3>

                @if($hasUser)

                    <div class="user-profile">

                        <div class="user-avatar">
                            {{ mb_substr($log->user->name, 0, 1) }}
                        </div>

                        <div>

                            <div class="user-profile-name">
                                {{ $log->user->name }}
                            </div>

                            <div class="user-profile-role">
                                {{ $roleLabels[$log->user->role] ?? 'حساب مستخدم' }}
                            </div>

                        </div>

                    </div>

                    <div class="detail-row">

                        <span class="detail-label">
                            معرّف الحساب
                        </span>

                        <span class="detail-value">
                            #{{ $log->user_id }}
                        </span>

                    </div>

                    @if(!empty($log->user->phone))

                        <div class="detail-row">

                            <span class="detail-label">
                                رقم الهاتف
                            </span>

                            <span class="detail-value">
                                {{ $log->user->phone }}
                            </span>

                        </div>

                    @endif

                    @if(!empty($log->user->email))

                        <div class="detail-row">

                            <span class="detail-label">
                                البريد الإلكتروني
                            </span>

                            <span class="detail-value">
                                {{ $log->user->email }}
                            </span>

                        </div>

                    @endif

                @else

                    <div class="description-box">
                        هذا الحدث صادر عن النظام أو لا يرتبط بحساب مستخدم محدد.
                    </div>

                @endif

            </section>


            <!-- معلومات طلب API: تظهر فقط لطلبات API -->

            @if($hasApiInfo)

                <section class="details-card full-width">

                    <h3>
                        <span class="section-icon">🌐</span>
                        تفاصيل طلب API
                    </h3>

                    <div class="detail-row">

                        <span class="detail-label">
                            طريقة الطلب
                        </span>

                        <span class="detail-value">

                            @if($log->method)

                                <span class="method-badge">
                                    {{ $method }}
                                </span>

                                <div class="api-explanation">
                                    {{ $methodLabels[$method] ?? 'طريقة طلب HTTP' }}
                                </div>

                            @else

                                غير متوفر

                            @endif

                        </span>

                    </div>


                    @if($log->url)

                        <div class="detail-row">

                            <span class="detail-label">
                                رابط الطلب
                            </span>

                            <span class="detail-value ltr">
                                {{ $log->url }}
                            </span>

                        </div>

                    @endif


                    <div class="detail-row">

                        <span class="detail-label">
                            نتيجة الطلب
                        </span>

                        <span class="detail-value">

                            @if($statusCode !== null)

                                <span class="response-badge {{ $statusClass }}">
                                    {{ $statusCode }}
                                </span>

                                <div class="api-explanation">
                                    {{ $statusDescription }}
                                </div>

                            @else

                                غير متوفر

                            @endif

                        </span>

                    </div>


                    @if($log->duration_ms !== null)

                        <div class="detail-row">

                            <span class="detail-label">
                                مدة تنفيذ الطلب
                            </span>

                            <span class="detail-value">
                                {{ number_format($log->duration_ms) }} مللي ثانية
                            </span>

                        </div>

                    @endif

                </section>

            @endif


            <!-- وصف العملية -->

            @if($hasDescription)

                <section class="details-card full-width">

                    <h3>
                        <span class="section-icon">📝</span>
                        وصف العملية
                    </h3>

                    <div class="description-box">
                        {{ $log->description }}
                    </div>

                </section>

            @endif


            <!-- معلومات الاتصال -->

            @if($hasConnectionInfo)

                <section class="details-card full-width">

                    <h3>
                        <span class="section-icon">💻</span>
                        معلومات الاتصال
                    </h3>

                    <p class="connection-note">
                        معلومات الاتصال التي استقبلها الخادم أثناء تنفيذ العملية.
                    </p>

                    @if($log->ip_address)

                        <div class="detail-row">

                            <span class="detail-label">
                                عنوان IP
                            </span>

                            <span class="detail-value ip-value">
                                {{ $log->ip_address }}
                            </span>

                        </div>

                    @endif


                    @if($log->user_agent)

                        <details class="technical-details">

                            <summary>
                                عرض بيانات الجهاز والمتصفح
                            </summary>

                            <pre>{{ $log->user_agent }}</pre>

                        </details>

                    @endif

                </section>

            @endif


            <!-- البيانات الإضافية: تظهر فقط عند وجودها -->

            @if($hasMetadata)

                <section class="details-card full-width">

                    <h3>
                        <span class="section-icon">🔍</span>
                        تفاصيل إضافية
                    </h3>

                    <details class="metadata-details">

                        <summary>
                            عرض البيانات التقنية الإضافية
                        </summary>

                        <pre>{{ json_encode(
                            $log->metadata,
                            JSON_PRETTY_PRINT |
                            JSON_UNESCAPED_UNICODE |
                            JSON_UNESCAPED_SLASHES
                        ) }}</pre>

                    </details>

                </section>

            @endif


        </div>

    </main>

</div>

</body>
</html>