
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قوائم التحقق | TinyGuide</title>
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
    <style>
        .tg-checklists {
            min-width: 0;
            padding: 0 0 30px;
            background: #ffffff;
            color: #173d4b;
            font-family: Arial, sans-serif;
        }

        .tg-checklists * {
            box-sizing: border-box;
        }

        .check-content {
            padding: clamp(16px, 2vw, 26px);
        }

        /* رأس الصفحة */
        .check-datebar {
            min-height: 86px;
            padding: 14px 20px;
            margin: 0 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            position: relative;
            overflow: hidden;
            border: 1px solid #d7e9e9;
            border-radius: 15px;
            background: linear-gradient(110deg, #f8fcfc 0%, #edf8f7 100%);
            box-shadow: 0 2px 7px #1a4f5808;
        }

        .check-datebar:before {
            content: "";
            position: absolute;
            inset-block: 0;
            inset-inline-start: 0;
            width: 4px;
            background: #159e9a;
        }

        .date-pill {
            display: inline-flex;
            flex: 0 0 auto;
            gap: 9px;
            align-items: center;
            padding: 9px 14px;
            border: 1px solid #d8e9e9;
            border-radius: 10px;
            background: #fff;
            color: #176d70;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .check-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
            min-width: 0;
        }

        .heading-icon {
            width: 54px;
            height: 54px;
            flex: 0 0 54px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: #159e9a;
            color: white;
        }

        .check-heading h1 {
            margin: 0;
            font-size: clamp(22px, 2vw, 29px);
            color: #173d4b;
        }

        .notice {
            margin: 0 0 16px;
            padding: 12px 15px;
            border: 1px solid #d6e6e8;
            border-radius: 9px;
            background: #fff;
            color: #55717c;
            font-size: 13px;
            line-height: 1.8;
            transition: opacity .4s ease, transform .4s ease;
        }

        .notice.success {
            border-color: #a8dedc;
            background: #eaf8f7;
            color: #267f7c;
        }

        .notice.error {
            border-color: #f0c7c7;
            background: #fff2f2;
            color: #a13b3b;
        }

        .notice.hide {
            opacity: 0;
            transform: translateY(-8px);
        }

        .check-actions {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 18px;
        }

        .run-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 43px;
            padding: 10px 20px;
            border: 0;
            border-radius: 9px;
            background: #159e9a;
            color: #fff;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .run-button:hover {
            background: #117f7c;
            transform: translateY(-1px);
        }

        .run-button svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .run-button:disabled,
        .single-run-button:disabled {
            opacity: .6;
            cursor: wait;
            transform: none;
        }

        /* البطاقات الأربع: متساوية عند الإغلاق وتتمدد عند فتح التفاصيل */
        .check-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            grid-auto-rows: minmax(440px, auto);
            align-items: stretch;
            gap: 16px;
            margin-bottom: 18px;
        }

        .check-card {
            width: 100%;
            height: auto;
            min-width: 0;
            min-height: 440px;
            padding: 18px 14px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 1px solid #c5e2e3;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 2px 5px #1a4f580d;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: grid;
            place-items: center;
            margin-bottom: 6px;
            color: #159e9a;
        }

        .card-icon svg {
            width: 42px;
            height: 42px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .check-card h2 {
            width: 100%;
            min-height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0 12px;
            color: #173d4b;
            font-size: 16px;
            line-height: 1.6;
        }

        .status {
            display: inline-flex;
            min-height: 38px;
            width: min(100%, 180px);
            flex: 0 0 38px;
            align-items: center;
            gap: 6px;
            min-width: 110px;
            justify-content: center;
            margin-top: 0;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
        }

        .status:before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        .status.passed {
            background: #e7f7f6;
            color: #168c89;
        }

        .status.warning {
            background: #fff5df;
            color: #a77a20;
        }

        .status.failed {
            background: #fff0f0;
            color: #c34c4c;
        }

        .status.pending {
            background: #eff3f5;
            color: #778b95;
        }

        /* قائمة الفحوصات الفردية */
        .service-checks-toggle {
            width: 100%;
            margin-top: 12px;
            flex: 0 0 auto !important;
            height: auto !important;
            min-height: 0 !important;
            align-self: stretch;
        }

        .service-checks-toggle summary {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 6px 9px;
            border: 1px solid #dceced;
            border-radius: 8px;
            background: #f8fcfc;
            color: #267f7c;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            list-style: none;
        }

        .service-checks-toggle summary::-webkit-details-marker {
            display: none;
        }

        .service-checks-toggle summary:after {
            content: '＋';
            margin-inline-start: 7px;
        }

        .service-checks-toggle[open] summary:after {
            content: '−';
        }

        .service-checks {
            display: grid;
            gap: 7px;
            width: 100%;
            margin-top: 8px;
            padding: 8px;
            border: 1px solid #dceced;
            border-radius: 10px;
            background: #f8fcfc;
            text-align: right;
        }

        .service-check-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            align-items: stretch;
            gap: 8px;
            min-width: 0;
            padding: 9px;
            border: 1px solid #e3eeee;
            border-radius: 8px;
            background: #fff;
        }

        .service-check-info {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .service-check-name {
            display: block;
            width: 100%;
            color: #365966;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .service-check-state {
            width: fit-content;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .service-check-state.passed {
            background: #e7f7f6;
            color: #168c89;
        }

        .service-check-state.warning {
            background: #fff5df;
            color: #a77a20;
        }

        .service-check-state.failed {
            background: #fff0f0;
            color: #c34c4c;
        }

        .service-check-state.pending {
            background: #eff3f5;
            color: #778b95;
        }

        .single-run-form {
            width: 100%;
            min-width: 0;
            flex: 0 0 auto;
            margin: 0;
        }

        .single-run-button {
            display: block;
            width: 100%;
            min-height: 34px;
            padding: 6px 10px;
            border: 1px solid #9edbd8;
            border-radius: 7px;
            background: #eaf8f7;
            color: #267f7c;
            font-family: inherit;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .single-run-button:hover {
            background: #d8f1ef;
        }

        .single-check-area {
            width: 100%;
            height: auto !important;
            min-height: 0 !important;
            max-height: none !important;
            flex: 0 0 auto !important;
            align-self: stretch;
            margin-top: 12px;
            padding: 9px;
            border: 1px solid #dceced;
            border-radius: 10px;
            background: #f8fcfc;
        }

        .single-check-area .service-check-row {
            height: auto !important;
            min-height: 0 !important;
            flex: 0 0 auto !important;
        }

        .last-check {
            width: 100%;
            flex: 0 0 auto;
            margin-top: auto;
            padding: 8px 7px;
            border-radius: 8px;
            background: #f1f6f7;
            color: #657e88;
            font-size: 11px;
            line-height: 1.8;
        }

        .last-check strong {
            color: #365966;
        }

        .last-check-message {
            margin-top: 4px;
            color: #365966;
        }

        .card-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            margin-top: 9px;
            padding: 6px 14px;
            border: 1px solid #9edbd8;
            border-radius: 8px;
            background: #eaf8f7;
            color: #267f7c;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
        }

        /* الرسومات */
        .analytics-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.35fr);
            align-items: start;
            gap: 16px;
        }

        .analytics-panel {
            min-width: 0;
            min-height: 220px;
            padding: 18px;
            border: 1px solid #d6e6e8;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 2px 5px #1a4f580a;
        }

        .analytics-panel h2 {
            margin: 0 0 18px;
            color: #173d4b;
            font-size: 17px;
        }

        .overall-body {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
            min-height: 145px;
        }

        .donut {
            width: 132px;
            height: 132px;
            flex: 0 0 132px;
            display: grid;
            place-items: center;
            border-radius: 50%;
        }

        .donut span {
            width: 102px;
            height: 102px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #fff;
            color: #173d4b;
            font-size: 23px;
            font-weight: 700;
        }

        .summary-list {
            flex: 1;
            min-width: 0;
            display: grid;
            gap: 9px;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 10px;
            border-radius: 8px;
            background: #f5f8f9;
            color: #496571;
            font-size: 12px;
        }

        .summary-row strong {
            color: #173d4b;
        }

        .chart-area {
            padding: 14px 12px 12px;
            border: 1px solid #e3edef;
            border-radius: 10px;
            background: #fbfdfd;
            overflow: hidden;
        }

        .chart-layout {
            display: grid;
            grid-template-columns: 32px minmax(0, 1fr);
            gap: 8px;
            align-items: start;
        }

        .chart-y-axis {
            height: 144px;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #8aa0a8;
            font-size: 9px;
            text-align: left;
            direction: ltr;
        }

        .chart-columns {
            min-width: 0;
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 7px;
            align-items: end;
        }

        .chart-column {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            text-align: center;
        }

        .chart-value {
            min-height: 15px;
            color: #267f7c;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .chart-track {
            position: relative;
            width: min(100%, 34px);
            height: 124px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            overflow: hidden;
            border-top: 1px dashed #dce9eb;
            border-bottom: 1px solid #cbdfe1;
            background: linear-gradient(
                to top,
                transparent 49.5%,
                #e3edef 49.5%,
                #e3edef 50.5%,
                transparent 50.5%
            );
        }

        .chart-bar {
            position: relative;
            z-index: 1;
            width: 68%;
            min-height: 2px;
            border-radius: 7px 7px 2px 2px;
            background: linear-gradient(180deg, #42bdb7 0%, #159e9a 100%);
            box-shadow: 0 2px 5px #159e9a24;
            transition: height .25s ease;
        }

        .chart-bar.no-data {
            height: 2px !important;
            background: #cbdadd;
            box-shadow: none;
        }

        .chart-day {
            min-height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #607d87;
            font-size: 9px;
            line-height: 1.4;
            white-space: nowrap;
        }

        .chart-count {
            color: #91a4aa;
            font-size: 8px;
            line-height: 1.3;
            white-space: nowrap;
        }

        .chart-empty {
            min-height: 145px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #78909a;
            font-size: 13px;
            line-height: 1.9;
        }

        @media(max-width: 520px) {
            .check-datebar {
                min-height: 0;
                padding: 14px;
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .date-pill {
                align-self: flex-start;
                font-size: 12px;
            }

            .heading-icon {
                width: 48px;
                height: 48px;
                flex-basis: 48px;
            }

            .chart-area {
                padding-inline: 7px;
            }

            .chart-layout {
                grid-template-columns: 27px minmax(0, 1fr);
                gap: 5px;
            }

            .chart-columns {
                gap: 3px;
            }

            .chart-track {
                width: min(100%, 26px);
                height: 112px;
            }

            .chart-day {
                font-size: 8px;
            }

            .chart-count {
                font-size: 7px;
            }
        }

        @media(max-width: 1100px) {
            .check-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width: 760px) {
            .analytics-grid {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 520px) {
            .check-grid {
                grid-template-columns: 1fr;
                grid-auto-rows: auto;
            }

            .check-card {
                height: auto;
                min-height: 0;
            }

            .overall-body {
                flex-direction: column;
            }

            .summary-list {
                width: 100%;
            }

            .check-actions {
                justify-content: stretch;
            }

            .run-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="dashboard">
    @include('partials.admin-sidebar')

    <main class="main-content tg-checklists">
        <div class="check-content">
            <div class="check-datebar">
                <header class="check-heading">
                    <div class="heading-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48" width="38" height="38"
                             fill="none" stroke="currentColor"
                             stroke-width="2.2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <rect x="10" y="7" width="28" height="35" rx="4"/>
                            <path d="M18 7V4h12v3M17 18l3 3 6-7M29 19h4M17 30l3 3 6-7M29 31h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1>قوائم التحقق</h1>
                    </div>
                </header>

                <div class="date-pill">
                    <span aria-hidden="true">▦</span>
                    {{ now('Asia/Aden')->locale('ar')->translatedFormat('l، d F Y') }}
                </div>
            </div>

            @if(session('health_check_success'))
                <div class="notice success auto-hide-notice">
                    {{ session('health_check_success') }}
                </div>
            @endif

            @if(session('checklist_success'))
                <div class="notice success auto-hide-notice">
                    {{ session('checklist_success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="notice error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="check-actions">
                <form method="POST" action="{{ route('admin.checklists.run') }}">
                    @csrf
                    <button type="submit" class="run-button">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 7v5h-5"/>
                            <path d="M4.9 9A8 8 0 0 1 19 6l1 6"/>
                            <path d="M4 17v-5h5"/>
                            <path d="M19.1 15A8 8 0 0 1 5 18l-1-6"/>
                        </svg>
                        تشغيل جميع الفحوصات
                    </button>
                </form>
            </div>

            <section class="check-grid" aria-label="فحوصات النظام">
                @foreach($healthCards as $item)
                    @php
                        $status = $item['status'];
                        $statusLabels = [
                            'passed' => 'ناجح',
                            'warning' => 'يحتاج مراجعة',
                            'failed' => 'فشل الفحص',
                            'pending' => 'بانتظار الفحص',
                        ];

                        $statusLabel = $statusLabels[$status] ?? 'غير معروف';
                        $lastCheck = $item['last_check']
                            ? \Illuminate\Support\Carbon::parse($item['last_check'])
                                ->timezone('Asia/Aden')
                                ->locale('ar')
                                ->translatedFormat('d F Y - h:i A')
                            : 'لم يُنفذ بعد';

                        $checks = collect($item['checks'] ?? []);

                        $subStatusLabels = [
                            'passed' => 'ناجح',
                            'warning' => 'مراجعة',
                            'failed' => 'فشل',
                            'pending' => 'لم يُفحص',
                        ];
                    @endphp

                    <article class="check-card">
                        <div class="card-icon" aria-hidden="true">
                            @if($item['icon'] === 'connection')
                                <svg viewBox="0 0 48 48">
                                    <rect x="7" y="10" width="34" height="25" rx="4"/>
                                    <path d="M17 41h14M24 35v6M14 22h5l3-6 5 13 3-7h4"/>
                                </svg>
                            @elseif($item['icon'] === 'database')
                                <svg viewBox="0 0 48 48">
                                    <ellipse cx="24" cy="10" rx="16" ry="6"/>
                                    <path d="M8 10v27c0 3 7 6 16 6s16-3 16-6V10M8 19c0 3 7 6 16 6s16-3 16-6M8 28c0 3 7 6 16 6s16-3 16-6"/>
                                </svg>
                            @elseif($item['icon'] === 'bell')
                                <svg viewBox="0 0 48 48">
                                    <path d="M11 33h26l-4-6V19a9 9 0 0 0-18 0v8zM20 39h8"/>
                                    <path d="M24 6v4"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 48 48">
                                    <path d="M11 5h19l8 8v30H11z"/>
                                    <path d="M30 5v9h8M17 23h15M17 29h15M17 35h10"/>
                                </svg>
                            @endif
                        </div>

                        <h2>{{ $item['title'] }}</h2>

                        <span class="status {{ $status }}">
                            {{ $statusLabel }}
                        </span>

                        @if($checks->count() > 1)
                            <details class="service-checks-toggle">
                                <summary>عرض الفحوصات الفردية</summary>

                                <div class="service-checks">
                                    @foreach($checks as $check)
                                        @php
                                            $subStatus = $check['status'] ?? 'pending';
                                        @endphp

                                        <div class="service-check-row">
                                            <div class="service-check-info">
                                                <span class="service-check-name">
                                                    {{ $check['label'] }}
                                                </span>

                                                <span class="service-check-state {{ $subStatus }}">
                                                    {{ $subStatusLabels[$subStatus] ?? 'غير معروف' }}
                                                </span>
                                            </div>

                                            <form
                                                class="single-run-form"
                                                method="POST"
                                                action="{{ route('admin.checklists.run.single', $check['key']) }}"
                                            >
                                                @csrf
                                                <button type="submit" class="single-run-button">
                                                    فحص الآن
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </details>

                        @elseif($checks->count() === 1)
                            @php
                                $check = $checks->first();
                                $subStatus = $check['status'] ?? 'pending';
                            @endphp

                            <div class="single-check-area">
                                <div class="service-check-row">
                                    <div class="service-check-info">
                                        <span class="service-check-name">
                                            {{ $check['label'] }}
                                        </span>

                                        <span class="service-check-state {{ $subStatus }}">
                                            {{ $subStatusLabels[$subStatus] ?? 'غير معروف' }}
                                        </span>
                                    </div>

                                    <form
                                        class="single-run-form"
                                        method="POST"
                                        action="{{ route('admin.checklists.run.single', $check['key']) }}"
                                    >
                                        @csrf
                                        <button type="submit" class="single-run-button">
                                            فحص الآن
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <div class="last-check">
                            آخر فحص:
                            <strong>{{ $lastCheck }}</strong>

                            @if($item['message'])
                                <div class="last-check-message">
                                    {{ $item['message'] }}
                                </div>
                            @endif
                        </div>

                        @if($item['record'])
                            <a class="card-link"
                               href="{{ route('admin.checklists.show', $item['record']) }}">
                                عرض التفاصيل
                            </a>
                        @endif
                    </article>
                @endforeach
            </section>

            <section class="analytics-grid">
                <div class="analytics-panel">
                    <h2>نسبة نجاح الفحوصات</h2>

                    <div class="overall-body">
                        @php
                            $percentage = min(100, max(0, (float) $successRate));
                        @endphp

                        <div
                            class="donut"
                            role="img"
                            aria-label="نسبة نجاح الفحوصات {{ $percentage }} بالمئة"
                            style="background:conic-gradient(#20a6a1 0% {{ $percentage }}%,#e6edef {{ $percentage }}% 100%);"
                        >
                            <span>{{ $percentage }}٪</span>
                        </div>

                        <div class="summary-list">
                            <div class="summary-row">
                                <span>فحوصات ناجحة</span>
                                <strong>{{ $passedChecks }}</strong>
                            </div>

                            <div class="summary-row">
                                <span>تحتاج مراجعة</span>
                                <strong>{{ $warningChecks }}</strong>
                            </div>

                            <div class="summary-row">
                                <span>فحوصات فاشلة</span>
                                <strong>{{ $failedChecks }}</strong>
                            </div>

                            <div class="summary-row">
                                <span>بانتظار الفحص</span>
                                <strong>{{ $pendingChecks }}</strong>
                            </div>

                            <div class="summary-row">
                                <span>إجمالي الفحوصات</span>
                                <strong>{{ $totalHealthChecks }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="analytics-panel">
                    <h2>معدل نجاح الفحوصات (آخر 7 أيام)</h2>

                    @if(collect($chartData ?? [])->isNotEmpty())
                        <div class="chart-area">
                            <div class="chart-layout">
                                <div class="chart-y-axis" aria-hidden="true">
                                    <span>100٪</span>
                                    <span>50٪</span>
                                    <span>0٪</span>
                                </div>

                                <div class="chart-columns" role="img" aria-label="معدل نجاح الفحوصات اليومي خلال آخر سبعة أيام">
                                    @foreach(($chartData ?? []) as $day)
                                        @php
                                            $dailyTotal = (int) ($day['total'] ?? 0);
                                            $dailyRate = $dailyTotal > 0
                                                ? min(100, max(0, (float) ($day['success_rate'] ?? 0)))
                                                : 0;

                                            $barHeight = $dailyTotal > 0 ? $dailyRate : 0;
                                        @endphp

                                        <div class="chart-column"
                                             title="{{ $day['label'] ?? '' }}: {{ $dailyTotal > 0 ? $dailyRate . '%' : 'لا توجد فحوصات' }}">
                                            <span class="chart-value">{{ number_format($dailyRate, 0) }}٪</span>

                                            <div class="chart-track">
                                                <div class="chart-bar {{ $dailyTotal === 0 ? 'no-data' : '' }}"
                                                     style="height: {{ $barHeight }}%;"></div>
                                            </div>

                                            <span class="chart-day">{{ $day['label'] ?? '' }}</span>
                                            <span class="chart-count">{{ $dailyTotal }} فحص</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="chart-empty">
                            لا توجد نتائج فحص مسجلة خلال آخر سبعة أيام.
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auto-hide-notice').forEach(function (notice) {
        setTimeout(function () {
            notice.classList.add('hide');

            setTimeout(function () {
                notice.remove();
            }, 450);
        }, 4000);
    });

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const button = form.querySelector('button[type="submit"]');

            if (!button) return;

            button.disabled = true;

            if (button.classList.contains('single-run-button')) {
                button.textContent = 'جارٍ الفحص...';
            } else if (button.classList.contains('run-button')) {
                button.textContent = 'جارٍ تنفيذ الفحوصات...';
            }
        });
    });
});
</script>
</body>
</html>
