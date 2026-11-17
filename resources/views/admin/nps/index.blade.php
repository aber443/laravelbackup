<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>قياس رضا المستخدمين | TinyGuide</title>
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
    <style>
        body.tg-nps-page {
            margin: 0;
            background: #F1F8F8;
        }

        .dashboard.tg-nps-layout {
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
            min-height: 100vh;
            align-items: start;
        }

        .tg-nps-layout > .sidebar.tg-admin-sidebar {
            position: sticky;
            inset-block-start: 0;
            width: auto;
            max-height: 100vh;
            overflow-y: auto;
            padding: 28px 18px;
            border-inline-end: 1px solid #E2EEEE;
            background: #fff;
        }

        .tg-nps-layout > .tg-nps-content {
            box-sizing: border-box;
            width: min(100%, 1120px);
            min-width: 0;
            margin: 0 auto;
            padding: clamp(16px, 3vw, 30px);
            color: #2C4A5A;
            font: 16px/1.6 Arial, sans-serif;
        }

        .tg-nps-content h1,
        .tg-nps-content h2,
        .tg-nps-content p {
            margin: 0;
        }

        .tg-nps-content h1 {
            font-size: clamp(23px, 3vw, 31px);
        }

        .tg-nps-content h2 {
            font-size: 18px;
        }

        .tg-nps-panel {
            padding: clamp(16px, 3vw, 24px);
            border: 1px solid #A8DEDC;
            border-radius: 18px;
            background: #fff;
        }

        .tg-nps-title {
            margin-block-end: 14px;
        }

        .tg-nps-note {
            color: #486573;
            font-size: 13px;
        }

        .tg-nps-filter {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            justify-content: space-between;
            gap: 12px;
            margin-block-end: 14px;
        }

        .tg-nps-date {
            display: grid;
            gap: 4px;
        }

        .tg-nps-date strong {
            display: inline-block;
            width: fit-content;
            padding: 6px 12px;
            border: 1px solid #D1E4E4;
            border-radius: 9px;
            background: #F5F9F9;
            color: #2C4A5A;
            font-size: 14px;
        }

        .tg-nps-periods {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .tg-nps-periods a {
            padding: 6px 16px;
            border: 1px solid #D1E4E4;
            border-radius: 999px;
            background: #EEF3F4;
            color: #2C4A5A;
            text-decoration: none;
            font-size: 14px;
        }

        .tg-nps-periods a[aria-current="true"] {
            border-color: #295867;
            background: #295867;
            color: #fff;
        }

        .tg-nps-periods a:focus-visible {
            outline: 2px solid #297F7B;
            outline-offset: 2px;
        }

        .tg-nps-cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin: 0 0 14px;
            padding: 0;
            list-style: none;
        }

        .tg-nps-card {
            display: grid;
            min-height: 142px;
            justify-items: center;
            align-content: center;
            gap: 5px;
            padding: 14px 8px;
            border: 1px solid #C6DCDD;
            border-radius: 12px;
            background: #F5F8F9;
            text-align: center;
        }

        .tg-nps-promoters { --nps-color: #52983F; }
        .tg-nps-passives { --nps-color: #B87518; }
        .tg-nps-detractors { --nps-color: #C14D57; }

        .tg-nps-card h2 { font-size: 14px; }

        .tg-nps-card strong {
            color: #244A57;
            font-size: 27px;
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }

        .tg-nps-face {
            width: 40px;
            height: 40px;
            color: var(--nps-color);
        }

        .tg-nps-analysis {
            display: grid;
            grid-template-columns: minmax(205px, .9fr) minmax(0, 1.8fr);
            align-items: start;
            gap: clamp(16px, 3vw, 34px);
            direction: ltr;
        }

        .tg-nps-indicator {
            display: grid;
            justify-items: center;
            gap: 12px;
            text-align: center;
        }

        .tg-nps-ring {
            display: grid;
            width: min(100%, 176px);
            aspect-ratio: 1;
            place-items: center;
            border-radius: 50%;
            background: #E7EFEF;
        }

        .tg-nps-ring.has-responses {
            background: conic-gradient(
                #71B755 0 var(--nps-promoters-end),
                #F4B54D var(--nps-promoters-end) var(--nps-passives-end),
                #DF6870 var(--nps-passives-end) 100%
            );
        }

        .tg-nps-ring-center {
            display: grid;
            width: 75%;
            aspect-ratio: 1;
            align-content: center;
            justify-items: center;
            border-radius: 50%;
            background: #fff;
            color: #244A57;
        }

        .tg-nps-ring-center strong {
            font-size: 37px;
            line-height: 1;
        }

        .tg-nps-legend {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 4px 10px;
            margin: 0;
            padding: 0;
            color: #486573;
            list-style: none;
            font-size: 12px;
        }

        .tg-nps-legend li {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .tg-nps-legend li::before {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--nps-color);
            content: "";
        }

        .tg-nps-distribution { min-width: 0; }

        .tg-nps-chart-wrap {
            overflow-x: auto;
            margin-top: 16px;
        }

        .tg-nps-chart {
            display: grid;
            grid-template-columns: 38px minmax(0, 1fr);
            grid-template-rows: 155px auto;
            min-width: 245px;
            color: #486573;
            font-size: 11px;
        }

        .tg-nps-axis {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-inline-end: 5px;
            text-align: end;
            line-height: 1;
        }

        .tg-nps-plot {
            position: relative;
            min-width: 0;
            border-block-end: 1px solid #C8DEDF;
            background: repeating-linear-gradient(
                to bottom, #E2ECEC 0 1px, transparent 1px 25%
            );
        }

        .tg-nps-bars,
        .tg-nps-ticks {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 3px;
            margin: 0;
            padding: 0;
            list-style: none;
            text-align: center;
        }

        .tg-nps-bars { height: 100%; }

        .tg-nps-bars li {
            display: flex;
            justify-content: center;
            align-items: end;
            min-width: 0;
        }

        .tg-nps-bar {
            width: min(72%, 22px);
            height: var(--nps-height);
            border-radius: 4px 4px 0 0;
            background: var(--nps-color);
        }

        .tg-nps-ticks {
            grid-column: 2;
            margin-block-start: 4px;
        }

        .tg-nps-empty-plot {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            pointer-events: none;
        }

        .tg-nps-empty-plot span {
            padding: 5px 10px;
            border-radius: 6px;
            background: #F5F9F9;
            color: #486573;
            font-size: 13px;
        }

        @media (max-width: 1000px) {
            .tg-nps-analysis {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 760px) {
            .dashboard.tg-nps-layout {
                grid-template-columns: minmax(0, 1fr);
            }

            .tg-nps-layout > .sidebar.tg-admin-sidebar {
                position: static;
                min-height: 0;
                max-height: none;
                border-inline-end: 0;
                border-block-end: 1px solid #E2EEEE;
            }
        }

        @media (max-width: 540px) {
            .tg-nps-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
</head>

<body class="tg-nps-page">
<div class="dashboard tg-nps-layout">
    @include('partials.admin-sidebar')

    <main class="main-content tg-nps-content">

        <header class="tg-nps-panel tg-nps-title">
            <h1>قياس رضا المستخدمين</h1>
        </header>

        <div class="tg-nps-panel tg-nps-filter">
            <div class="tg-nps-date">
                <span class="tg-nps-note">الفترة المحددة</span>
                <strong><bdi>{{ $dateRange }}</bdi></strong>
            </div>

            <nav class="tg-nps-periods" aria-label="فترة التقييمات">
                @foreach ($periods as $key => $option)
                    <a href="{{ route('admin.nps.index', ['period' => $key]) }}"
                       @if ($period === $key) aria-current="true" @endif>
                        {{ $option['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        @php
            $faces = [
                [
                    'key' => 'promoters',
                    'label' => 'الراضون',
                    'count' => $promoters,
                    'mouth' => 'M7 13a5 5 0 0 0 10 0',
                ],
                [
                    'key' => 'detractors',
                    'label' => 'غير الراضين',
                    'count' => $detractors,
                    'mouth' => 'M7 17a5 5 0 0 1 10 0',
                ],
            ];

            $promotersEnd = $total > 0
                ? $promoters / $total * 100
                : 0;

            $passivesEnd = $total > 0
                ? ($promoters + $passives) / $total * 100
                : 0;
        @endphp

        <ul class="tg-nps-cards" aria-label="ملخص التقييمات">

            @foreach ($faces as $face)
                <li class="tg-nps-card tg-nps-{{ $face['key'] }}">
                    <svg class="tg-nps-face"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.6"
                         stroke-linecap="round"
                         aria-hidden="true"
                         focusable="false">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="8.5" cy="9" r="0.9"
                                fill="currentColor" stroke="none"/>
                        <circle cx="15.5" cy="9" r="0.9"
                                fill="currentColor" stroke="none"/>
                        <path d="{{ $face['mouth'] }}"/>
                    </svg>

                    <strong>{{ number_format($face['count']) }}</strong>
                    <h2>{{ $face['label'] }}</h2>
                </li>
            @endforeach

            <li class="tg-nps-card">
                <strong>{{ number_format($total) }}</strong>
                <h2>إجمالي التقييمات</h2>
            </li>

            <li class="tg-nps-card">
                <strong>
                    <bdi>
                        {{ $participation === null
                            ? '—'
                            : number_format($participation, 1) . '٪' }}
                    </bdi>
                </strong>
                <h2>نسبة المشاركة</h2>
            </li>
        </ul>

        <div class="tg-nps-panel tg-nps-analysis">

            <section class="tg-nps-indicator"
                     dir="rtl"
                     aria-labelledby="tg-nps-indicator-title">

                <h2 id="tg-nps-indicator-title">متوسط تقييم التطبيق</h2>

                <div @class([
                    'tg-nps-ring',
                    'has-responses' => $total > 0
                ])
                     style="--nps-promoters-end: {{ $promotersEnd }}%; --nps-passives-end: {{ $passivesEnd }}%;">

                    <div class="tg-nps-ring-center">
                        <span>من 5 نجوم</span>

                        <strong>
                            <bdi>
                                {{ $averageRating === null
                                    ? '—'
                                    : number_format($averageRating, 1) }}
                            </bdi>
                        </strong>
                    </div>
                </div>

                <ul class="tg-nps-legend" aria-label="فئات التقييم">
                    <li class="tg-nps-promoters">الراضون (4–5)</li>
                    <li class="tg-nps-passives">المحايدون (3)</li>
                    <li class="tg-nps-detractors">غير الراضين (1–2)</li>
                </ul>

                <p class="tg-nps-note">
                    نسبة الرضا:
                    <strong>
                        {{ $satisfactionRate === null
                            ? '—'
                            : number_format($satisfactionRate, 1) . '٪' }}
                    </strong>
                </p>
            </section>

            <section class="tg-nps-distribution"
                     dir="rtl"
                     aria-labelledby="tg-nps-scores-title">

                <h2 id="tg-nps-scores-title">توزيع تقييمات التطبيق</h2>

                <p class="tg-nps-note">
                    نسبة كل درجة من تقييمات الفترة المختارة
                </p>

                <div class="tg-nps-chart-wrap">
                    <div class="tg-nps-chart" dir="ltr">

                        <div class="tg-nps-axis" aria-hidden="true">
                            <span>100%</span>
                            <span>75%</span>
                            <span>50%</span>
                            <span>25%</span>
                            <span>0%</span>
                        </div>

                        <div class="tg-nps-plot">
                            <ol class="tg-nps-bars"
                                aria-labelledby="tg-nps-scores-title">

                                @foreach ($scores as $score => $count)
                                    @php
                                        $group = $score >= 4
                                            ? 'promoters'
                                            : ($score === 3
                                                ? 'passives'
                                                : 'detractors');

                                        $share = $total > 0
                                            ? round($count / $total * 100, 2)
                                            : 0;
                                    @endphp

                                    <li class="tg-nps-{{ $group }}"
                                        aria-label="التقييم {{ $score }} نجوم، العدد {{ $count }}، النسبة {{ $share }}٪">

                                        <span class="tg-nps-bar"
                                              style="--nps-height: {{ $share }}%"
                                              title="{{ $score }} نجوم · {{ $count }} تقييم">
                                        </span>
                                    </li>
                                @endforeach
                            </ol>

                            @if ($total === 0)
                                <p class="tg-nps-empty-plot" dir="rtl">
                                    <span>لا توجد تقييمات في هذه الفترة</span>
                                </p>
                            @endif
                        </div>

                        <ol class="tg-nps-ticks" aria-hidden="true">
                            @foreach (array_keys($scores) as $score)
                                <li>{{ $score }} ★</li>
                            @endforeach
                        </ol>

                    </div>
                </div>
            </section>
        </div>

    </main>
</div>
</body>
</html>