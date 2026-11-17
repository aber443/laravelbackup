<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>أداء النظام | TinyGuide</title>
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

    <style>
        body.tg-performance-page { margin: 0; }
        .performance-layout.dashboard {
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
            min-height: 100vh;
            align-items: start;
        }

        .performance-layout > .sidebar.tg-admin-sidebar {
            position: sticky;
            inset-block-start: 0;
            width: auto;
            max-height: 100vh;
            overflow-y: auto;
            padding: 28px 18px;
            background: #fff;
            border-inline-end: 1px solid #E2EEEE;
        }

        .performance-layout > .tg-performance {
            min-height: 100vh;
            margin: 0;
            box-sizing: border-box;
        }

        @media (max-width: 760px) {
            .performance-layout.dashboard { grid-template-columns: minmax(0, 1fr); }
            .performance-layout > .sidebar.tg-admin-sidebar {
                position: static;
                min-height: 0;
                max-height: none;
                border-inline-end: 0;
                border-block-end: 1px solid #E2EEEE;
            }
        }

        .tg-performance {
            min-width: 0;
            padding: clamp(16px, 3vw, 30px);
            background: #F1F8F8;
            color: #2C4A5A;
            font: 16px/1.6 Arial, sans-serif;
        }

        .tg-performance .performance-header,
        .tg-performance .performance-card {
            box-sizing: border-box;
            border: 1px solid #A8DEDC;
            border-radius: 18px;
            background: #fff;
        }

        .tg-performance .performance-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
            padding: clamp(16px, 3vw, 24px);
        }

        .tg-performance .performance-header h1 {
            margin: 0 0 4px;
            font-size: clamp(23px, 3vw, 31px);
        }

        .tg-performance .performance-note {
            margin: 0;
            color: #486573;
            font-size: 14px;
        }

        .tg-performance .performance-refresh {
            display: inline-block;
            padding: 8px 14px;
            border: 1px solid #A8DEDC;
            border-radius: 10px;
            background: #E8F7F6;
            color: #2C4A5A;
            font-weight: 700;
            text-decoration: none;
        }

        .tg-performance .performance-refresh:hover {
            background: #D8F0EE;
        }

        .tg-performance .performance-refresh:focus-visible {
            outline: 2px solid #297F7B;
            outline-offset: 3px;
        }

        .tg-performance .performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr));
            gap: 14px;
        }

        .tg-performance .performance-card {
            min-width: 0;
            padding: 18px;
        }

        .tg-performance .performance-card h2 {
            margin: 0 0 8px;
            font-size: 17px;
        }

        .tg-performance .performance-value {
            margin: 0 0 8px;
            color: #225F61;
            font-size: 26px;
            font-weight: 700;
        }

        .tg-performance .performance-value.is-unavailable {
            color: #486573;
            font-size: 17px;
        }

        .performance-gauge {
            display: grid;
            place-items: center;
            width: 108px;
            aspect-ratio: 1;
            margin: 14px auto 10px;
            border-radius: 50%;
            background: conic-gradient(#399D9A 0 var(--reading), #E2EFEE var(--reading) 100%);
        }

        .performance-gauge > span {
            display: grid;
            place-items: center;
            width: 80%;
            aspect-ratio: 1;
            border-radius: 50%;
            background: white;
            color: #225F61;
            font-size: 22px;
            font-weight: 700;
        }

        .performance-card { text-align: center; }
        .performance-response-card .performance-value { margin-top: 32px; }
        .performance-charts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 340px), 1fr));
            gap: 14px;
            margin-top: 14px;
        }
        .performance-charts .performance-card { text-align: start; }
        .performance-charts canvas { display: block; width: 100%; height: 190px; }
        .performance-charts canvas[hidden] { display: none; }
        .performance-legend { display: flex; flex-wrap: wrap; gap: 12px; }
        .performance-legend span::before {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin-inline-end: 5px;
            border-radius: 50%;
            background: var(--series);
            content: "";
        }
    </style>
</head>
<body class="tg-performance-page">
<div class="dashboard performance-layout">
    @include('partials.admin-sidebar')

    <main class="main-content tg-performance" id="performance-page"
        data-sample="{{ json_encode(['at' => $measuredAt->format('H:i:s'), 'cpu' => $metrics['cpu_percent'], 'memory' => $metrics['memory']['percent'] ?? null]) }}">
        @php
            $formatBytes = fn ($bytes) => number_format($bytes / 1073741824, 1) . ' GiB';

            $cards = [
                ['id' => 'cpu', 'title' => 'المعالج', 'percent' => $metrics['cpu_percent'], 'usage' => null],
                ['id' => 'memory', 'title' => 'الذاكرة', 'percent' => $metrics['memory']['percent'] ?? null, 'usage' => $metrics['memory']],
                ['id' => 'storage', 'title' => 'قرص المشروع', 'percent' => $metrics['storage']['percent'] ?? null, 'usage' => $metrics['storage']],
            ];
        @endphp

        <header class="performance-header">
            <div>
                <h1>أداء النظام</h1>
                <p class="performance-note">
                    آخر قراءة للجهاز
                    <time datetime="{{ $measuredAt->toIso8601String() }}">
                        <bdi>{{ $measuredAt->format('Y-m-d H:i:s') }}</bdi>
                    </time>
                </p>
            </div>

            <a class="performance-refresh" href="{{ route('admin.performance.index') }}">تحديث القراءات</a>
        </header>

        <section class="performance-grid" aria-label="مؤشرات أداء النظام">
            @foreach ($cards as $card)
                <article class="performance-card">
                    <h2 id="performance-{{ $card['id'] }}">{{ $card['title'] }}</h2>
                    @if ($card['percent'] !== null)
                        <div class="performance-gauge" style="--reading: {{ $card['percent'] }}%">
                            <span><bdi>{{ number_format($card['percent'], 1) }}٪</bdi></span>
                        </div>
                        @if ($card['usage'] !== null)
                            <p class="performance-note">
                                <bdi>{{ $formatBytes($card['usage']['used_bytes']) }}</bdi>
                                مستخدم من
                                <bdi>{{ $formatBytes($card['usage']['total_bytes']) }}</bdi>
                            </p>
                        @endif
                    @else
                        <p class="performance-value is-unavailable">غير متاح</p>
                    @endif
                </article>
            @endforeach

            <article class="performance-card performance-response-card">
                <h2>سرعة الاستجابة</h2>
                <p id="performance-response" class="performance-value is-unavailable" role="status"
                    data-probe-url="{{ route('admin.performance.probe', [], false) }}">غير متاح</p>
            </article>
        </section>
        <section class="performance-charts" aria-label="رسوم القياسات الأخيرة">
            <article class="performance-card">
                <h2>استخدام المعالج والذاكرة</h2>
                <p class="performance-note performance-legend">
                    <span style="--series: #238782">المعالج</span>
                    <span style="--series: #496BB5">الذاكرة</span>
                </p>
                <canvas id="performance-resources-chart" role="img" aria-label="نسب استخدام المعالج والذاكرة عبر القياسات" hidden></canvas>
                <p id="performance-resources-empty" class="performance-note">يظهر الاتجاه بعد قراءتين متاحتين على الأقل.</p>
            </article>
            <article class="performance-card">
                <h2>زمن الاستجابة</h2>
                <p class="performance-note">بالمللي ثانية</p>
                <canvas id="performance-response-chart" role="img" aria-label="زمن استجابة Laravel عبر القياسات بالمللي ثانية" hidden></canvas>
                <p id="performance-response-empty" class="performance-note">يظهر الاتجاه بعد قراءتين ناجحتين على الأقل.</p>
            </article>
        </section>
    </main>
</div>

    <script>
        async function measurePerformanceResponse() {
            const output = document.getElementById('performance-response');
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 8000);

            output.textContent = 'جارٍ القياس…';

            try {
                const startedAt = performance.now();
                const response = await fetch(output.dataset.probeUrl, {
                    cache: 'no-store',
                    credentials: 'same-origin',
                    redirect: 'error',
                    headers: { Accept: 'application/json' },
                    signal: controller.signal,
                });
                const elapsed = Math.round(performance.now() - startedAt);

                if (!response.ok) {
                    throw new Error('Response check failed');
                }

                const result = await response.json();
                if (result?.ok !== true) {
                    throw new Error('Invalid response');
                }

                output.textContent = new Intl.NumberFormat('ar').format(elapsed) + ' مللي ثانية';
                output.classList.remove('is-unavailable');
                return elapsed;
            } catch {
                output.textContent = 'غير متاح';
                return null;
            } finally {
                clearTimeout(timeout);
            }
        }

        function drawPerformanceChart(canvasId, emptyId, samples, series, max, unit) {
            const canvas = document.getElementById(canvasId);
            const hasTrend = series.some(({ key }) =>
                samples.filter(sample => Number.isFinite(sample[key])).length >= 2
            );
            canvas.hidden = !hasTrend;
            document.getElementById(emptyId).hidden = hasTrend;
            if (!hasTrend) return;

            const ratio = window.devicePixelRatio || 1;
            const width = canvas.clientWidth;
            const height = canvas.clientHeight;
            if (!width || !height) return;

            canvas.width = width * ratio;
            canvas.height = height * ratio;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            ctx.scale(ratio, ratio);
            ctx.font = '11px Arial';
            ctx.textBaseline = 'middle';
            ctx.direction = 'ltr';
            const left = 44;
            const right = width - 20;
            const top = 14;
            const bottom = height - 27;
            const x = index => left + index * (right - left) / (samples.length - 1);
            const y = value => bottom - value / max * (bottom - top);

            for (let step = 0; step <= 4; step++) {
                const value = max * step / 4;
                ctx.beginPath();
                ctx.moveTo(left, y(value));
                ctx.lineTo(right, y(value));
                ctx.strokeStyle = '#E2EFEE';
                ctx.lineWidth = 1;
                ctx.stroke();
                ctx.fillStyle = '#486573';
                ctx.textAlign = 'right';
                ctx.fillText(Math.round(value) + unit, left - 6, y(value));
            }

            ctx.textAlign = 'left';
            ctx.fillText(samples[0].at, left, height - 10);
            ctx.textAlign = 'right';
            ctx.fillText(samples[samples.length - 1].at, right, height - 10);

            for (const { key, color } of series) {
                ctx.strokeStyle = color;
                ctx.fillStyle = color;
                ctx.lineWidth = 2;
                ctx.beginPath();
                let connected = false;
                samples.forEach((sample, index) => {
                    const value = sample[key];
                    if (!Number.isFinite(value)) {
                        connected = false;
                        return;
                    }
                    if (connected) ctx.lineTo(x(index), y(value));
                    else ctx.moveTo(x(index), y(value));
                    connected = true;
                });
                ctx.stroke();
                samples.forEach((sample, index) => {
                    if (!Number.isFinite(sample[key])) return;
                    ctx.beginPath();
                    ctx.arc(x(index), y(sample[key]), 2.7, 0, Math.PI * 2);
                    ctx.fill();
                });
            }
        }

        async function updatePerformanceCharts() {
            const sample = JSON.parse(document.getElementById('performance-page').dataset.sample);
            const storageKey = 'tinyguide:performance-samples:v1';
            let samples = [];

            try {
                const stored = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
                if (Array.isArray(stored)) samples = stored;
            } catch {
                // The current reading still works when browser storage is unavailable.
            }

            sample.response = await measurePerformanceResponse();
            samples = [...samples, sample].slice(-30);

            try {
                sessionStorage.setItem(storageKey, JSON.stringify(samples));
            } catch {
                // Keep the current display when browser storage is unavailable.
            }

            const maxResponse = Math.max(100, ...samples.map(entry => entry.response ?? 0));
            const render = () => {
                drawPerformanceChart('performance-resources-chart', 'performance-resources-empty', samples, [
                    { key: 'cpu', color: '#238782' },
                    { key: 'memory', color: '#496BB5' },
                ], 100, '%');
                drawPerformanceChart('performance-response-chart', 'performance-response-empty', samples, [
                    { key: 'response', color: '#238782' },
                ], Math.ceil(maxResponse / 100) * 100, '');
            };

            render();
            window.addEventListener('resize', render);
        }

        updatePerformanceCharts();
    </script>
</body>
</html>