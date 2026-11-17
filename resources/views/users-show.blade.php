

<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>تفاصيل ولي الأمر | TinyGuide</title>



    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">



    <style>

        * {

            box-sizing: border-box;

        }



        body.tg-parent-page {

            margin: 0;

            background: #F1F8F8;

            color: #2C4A5A;

            font-family: Arial, sans-serif;

        }



        /* تخطيط الصفحة والقائمة الجانبية */

        .dashboard.tg-parent-layout {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            width: 100%;
            min-height: 100vh;
            align-items: stretch;
            direction: rtl;
        }



        .tg-parent-layout > .sidebar.tg-admin-sidebar {

            position: sticky;

            top: 0;

            flex: 0 0 250px;

            width: 250px;

            min-width: 250px;

            height: 100vh;

            max-height: 100vh;

            overflow-y: auto;

            padding: 28px 18px;

            border-inline-end: 1px solid #E2EEEE;

            background: #fff;

        }



        .tg-parent-layout > main.tg-parent-content {
            display: block !important;
            flex: 1 1 auto !important;
            width: calc(100% - 250px) !important;
            max-width: none !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: clamp(18px, 2.5vw, 32px);
            color: #2C4A5A;
            font: 16px/1.6 Arial, sans-serif;
            overflow: visible;
        }



        /* العنوان */

        .tg-parent-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            flex-wrap: wrap;

            gap: 15px;

            margin-bottom: 22px;

            padding: 22px 25px;

            background: #fff;

            border: 1.5px solid #83D2CF;

            border-radius: 18px;

            box-shadow: 0 4px 12px rgba(44,74,90,.06);

        }



        .tg-parent-header h1 {

            margin: 0;

            font-size: 25px;

            color: #2C4A5A;

        }



        .tg-parent-back {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            padding: 10px 17px;

            border: 1px solid #C9E7E5;

            border-radius: 11px;

            background: #E8F7F6;

            color: #2C4A5A;

            text-decoration: none;

            font-size: 14px;

            transition: .2s;

        }



        .tg-parent-back:hover {

            background: #64C7C4;

            color: #fff;

        }



        /* البطاقة الرئيسية */

        .tg-summary-card {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            padding: 22px 25px;

            margin-bottom: 22px;

            border: 1.5px solid #8BCBC8;

            border-radius: 16px;

            background: #EAF7F5;

        }



        .tg-summary-card h2 {

            margin: 0 0 5px;

            font-size: 18px;

        }



        .tg-summary-card p {

            margin: 0;

            color: #78909A;

            font-size: 13px;

        }



        .tg-summary-number {

            min-width: 95px;

            text-align: center;

            font-size: 34px;

            line-height: 1.2;

            font-weight: 700;

            color: #238782;

        }



        /* البطاقات العامة */

        .tg-section {

            padding: 23px;

            margin-bottom: 22px;

            background: #fff;

            border: 1.5px solid #83D2CF;

            border-radius: 18px;

            box-shadow: 0 4px 12px rgba(44,74,90,.05);

            min-width: 0;

        }



        .tg-section-heading {

            display: flex;

            align-items: center;

            justify-content: space-between;

            flex-wrap: wrap;

            gap: 12px;

            margin-bottom: 20px;

        }



        .tg-section-heading h2 {

            margin: 0;

            font-size: 20px;

            color: #2C4A5A;

        }



        .tg-count {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 35px;

            height: 32px;

            padding: 0 10px;

            border-radius: 20px;

            background: #E1F4F3;

            color: #238782;

            font-weight: 700;

        }



        /* بطاقات الأطفال */

        .tg-children-grid {

            display: grid;

            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 15px;

        }



        .tg-child-card {

            min-width: 0;

            padding: 18px;

            border: 1.5px solid #C9E7E5;

            border-top: 3px solid #64C7C4;

            border-radius: 14px;

            background: #F5FAFA;

            transition: .2s;

        }



        .tg-child-card:hover {

            border-color: #64C7C4;

            transform: translateY(-2px);

            box-shadow: 0 4px 12px rgba(44,74,90,.07);

        }



        .tg-child-heading {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 16px;

        }



        .tg-child-icon {

            display: grid;

            place-items: center;

            flex-shrink: 0;

            width: 43px;

            height: 43px;

            border-radius: 13px;

            background: #DDF2EF;

            color: #238782;

            font-size: 22px;

        }



        .tg-child-heading h3 {

            margin: 0;

            font-size: 17px;

            overflow-wrap: anywhere;

        }



        .tg-child-id {

            color: #78909A;

            font-size: 12px;

        }



        .tg-child-info {

            display: grid;

            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 10px;

        }



        .tg-child-info-item {

            min-width: 0;

            padding: 11px;

            border: 1px solid #E0EEEE;

            border-radius: 10px;

            background: #fff;

        }



        .tg-child-info-item span {

            display: block;

            margin-bottom: 5px;

            color: #78909A;

            font-size: 12px;

        }



        .tg-child-info-item strong {

            display: block;

            font-size: 13px;

            overflow-wrap: anywhere;

            text-align: right;

        }

        /* إبقاء التواريخ بصيغة رقمية LTR مع محاذاتها إلى يمين البطاقة */
        .tg-child-info-item strong[dir="ltr"] {

            direction: ltr;

            text-align: right;

            unicode-bidi: isolate;

        }



        .tg-empty {

            padding: 25px;

            border: 1px dashed #B9DCD9;

            border-radius: 12px;

            background: #F5FAFA;

            text-align: center;

            color: #78909A;

        }



        /* قسم الرسوم */

        .tg-charts-grid {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 20px;
            width: 100% !important;
            max-width: none !important;
            align-items: stretch;
        }



        .tg-chart-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            width: 100% !important;
            max-width: none !important;
            min-height: 390px;
            padding: 20px;
            border: 1.5px solid #83D2CF;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(44,74,90,.05);
        }



        .tg-chart-header {

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            flex-wrap: wrap;

            gap: 10px;

            margin-bottom: 8px;

        }



        .tg-chart-header h3 {

            margin: 0;

            font-size: 18px;

        }



        .tg-chart-description {

            margin: 5px 0 15px;

            color: #78909A;

            font-size: 13px;

        }



        .tg-chart-container {
            position: relative;
            display: block;
            width: 100% !important;
            height: 290px !important;
            min-height: 290px;
            flex: 0 0 290px;
        }



        .tg-chart-container canvas {

            display: block;

            width: 100%;

            height: 100%;

        }



        /* أزرار الفترة */

        .tg-periods {

            display: flex;

            flex-wrap: wrap;

            gap: 7px;

        }



        .tg-period-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-width: 75px;

            padding: 7px 12px;

            border: 1px solid #C9E7E5;

            border-radius: 9px;

            background: #fff;

            color: #2C4A5A;

            font: inherit;

            font-size: 13px;

            text-decoration: none;

            transition: .2s;

        }



        .tg-period-btn:hover,

        .tg-period-btn.active {

            border-color: #399D9A;

            background: #64C7C4;

            color: #fff;

        }



        .tg-period-btn:focus-visible,

        .tg-parent-back:focus-visible {

            outline: 2px solid #238782;

            outline-offset: 3px;

        }



        .tg-chart-empty {

            display: grid;

            place-items: center;

            width: 100%;

            min-height: 230px;

            padding: 20px;

            border: 1px dashed #C9E7E5;

            border-radius: 12px;

            background: #F5FAFA;

            color: #78909A;

            text-align: center;

        }



        .tg-period-note {

            margin-top: 12px;

            color: #78909A;

            font-size: 12px;

            text-align: center;

        }



        @media (max-width: 900px) {

            .tg-charts-grid {

                grid-template-columns: minmax(0, 1fr) !important;

            }



            .tg-chart-card {

                min-height: 350px;

            }

        }



        @media (max-width: 760px) {

            .dashboard.tg-parent-layout {

                flex-direction: column;

            }



            .tg-parent-layout > .sidebar.tg-admin-sidebar {

                position: static;

                flex: none;

                width: 100%;

                min-width: 0;

                height: auto;

                max-height: none;

                border-inline-end: 0;

                border-block-end: 1px solid #E2EEEE;

            }



            .tg-parent-layout > main.tg-parent-content {

                width: 100% !important;

                flex: none;

                padding: 15px;

            }



            .tg-children-grid {

                grid-template-columns: minmax(0, 1fr);

            }



            .tg-child-info {

                grid-template-columns: repeat(2, minmax(0, 1fr));

            }



            .tg-summary-card {

                padding: 17px;

            }

        }



        @media (max-width: 450px) {

            .tg-section {

                padding: 15px;

            }



            .tg-child-info {

                grid-template-columns: minmax(0, 1fr);

            }



            .tg-chart-card {

                padding: 13px;

            }



            .tg-chart-container {

                height: 250px;

                min-height: 250px;

            }

        }

    </style>

</head>



<body class="tg-parent-page">



<div class="dashboard tg-parent-layout">



    @include('partials.admin-sidebar')



    <main class="main-content tg-parent-content">



        @php

            $children = $children ?? $user->children;



            $formatDate = static function ($value): string {

                if ($value instanceof \DateTimeInterface) {

                    return $value->format('Y-m-d');

                }



                if (!is_string($value) || trim($value) === '') {

                    return '—';

                }



                try {

                    return \Carbon\Carbon::parse($value)->format('Y-m-d');

                } catch (\Throwable $e) {

                    return '—';

                }

            };



            $genderLabel = static function ($gender): string {

                return match (strtolower(trim((string) $gender))) {

                    'male', 'boy', 'ذكر', 'ولد' => 'ولد',

                    'female', 'girl', 'أنثى', 'بنت' => 'بنت',

                    default => '—',

                };

            };



            $activityPeriod = $activityPeriod ?? request('activity_period', '30days');

            $periodLabels = [

                '7days' => 'آخر أسبوع',

                '30days' => 'آخر شهر',

            ];

        @endphp



        {{-- العنوان --}}

        <header class="tg-parent-header">

            <h1>تفاصيل ولي الأمر</h1>



            <a href="{{ route('users.index') }}" class="tg-parent-back">

                <span>العودة إلى المستخدمين</span>

                <span aria-hidden="true">←</span>

            </a>

        </header>



        {{-- إجمالي التفاعلات --}}

        <section class="tg-summary-card">

            <div>

                <h2>إجمالي تفاعلات الحساب</h2>

                <p>

                    مجموع التفاعلات المسجلة لحساب ولي الأمر خلال

                    {{ $periodLabels[$activityPeriod] ?? 'آخر شهر' }}.

                </p>

            </div>



            <div class="tg-summary-number">

                {{ number_format((int) ($totalInteractions ?? 0)) }}

            </div>

        </section>



        {{-- الأطفال المرتبطون --}}

        <section class="tg-section">



            <div class="tg-section-heading">

                <h2>الأطفال المرتبطون بالحساب</h2>



                <span class="tg-count">

                    {{ $children->count() }}

                </span>

            </div>



            @if ($children->isEmpty())



                <div class="tg-empty">

                    لا يوجد أطفال مرتبطون بهذا الحساب حاليًا.

                </div>



            @else



                <div class="tg-children-grid">



                    @foreach ($children as $child)



                        <article class="tg-child-card">



                            <div class="tg-child-heading">



                                <div class="tg-child-icon" aria-hidden="true">

                                    <svg width="25" height="25" viewBox="0 0 24 24"

                                         fill="none" stroke="currentColor"

                                         stroke-width="1.8" stroke-linecap="round"

                                         stroke-linejoin="round">

                                        <circle cx="12" cy="8" r="4"/>

                                        <path d="M4 21c0-4.2 3.2-7 8-7s8 2.8 8 7"/>

                                    </svg>

                                </div>



                                <div>

                                    <h3>{{ $child->name }}</h3>

                                    <span class="tg-child-id">

                                        رقم الطفل:

                                        <bdi>{{ $child->getKey() }}</bdi>

                                    </span>

                                </div>



                            </div>



                            <div class="tg-child-info">



                                <div class="tg-child-info-item">

                                    <span>الجنس</span>

                                    <strong>{{ $genderLabel($child->gender) }}</strong>

                                </div>



                                <div class="tg-child-info-item">

                                    <span>تاريخ الميلاد</span>

                                    <strong dir="ltr">

                                        {{ $formatDate($child->birth_date) }}

                                    </strong>

                                </div>



                                <div class="tg-child-info-item">

                                    <span>تاريخ التشخيص</span>

                                    <strong dir="ltr">

                                        {{ $formatDate($child->diagnosis_date) }}

                                    </strong>

                                </div>



                                <div class="tg-child-info-item">

                                    <span>نوع السكري</span>

                                    <strong>

                                        {{ $child->diabetes_type ?: '—' }}

                                    </strong>

                                </div>



                            </div>



                        </article>



                    @endforeach



                </div>



            @endif



        </section>



        {{-- الرسوم البيانية --}}

        <section class="tg-section">



            <div class="tg-section-heading">

                <h2>تحليل تفاعل المستخدم مع النظام</h2>



                <nav class="tg-periods" aria-label="تحديد فترة التفاعل">



                    @foreach ($periodLabels as $period => $label)



                        <a

                            href="{{ route('users.show', ['user' => $user->getRouteKey(), 'activity_period' => $period]) }}"

                            class="tg-period-btn {{ $activityPeriod === $period ? 'active' : '' }}"

                            @if ($activityPeriod === $period)

                                aria-current="page"

                            @endif

                        >

                            {{ $label }}

                        </a>



                    @endforeach



                </nav>

            </div>



            <div class="tg-charts-grid">



                {{-- الرسم الأول --}}

                <article class="tg-chart-card">



                    <div class="tg-chart-header">

                        <h3>معدل تفاعل المستخدم مع النظام</h3>

                    </div>



                    <p class="tg-chart-description">

                        يوضح عدد التفاعلات المسجلة يوميًا خلال الفترة المحددة.

                    </p>



                    @if (count($activityChartData ?? []) > 0)



                        <div class="tg-chart-container">

                            <canvas

                                id="activityChart"

                                role="img"

                                aria-label="مخطط معدل التفاعل اليومي"

                                data-chart='@json($activityChartData)'

                            ></canvas>

                        </div>



                    @else



                        <div class="tg-chart-empty">

                            لا توجد بيانات كافية لعرض التفاعل خلال هذه الفترة.

                        </div>



                    @endif



                </article>



                {{-- الرسم الثاني --}}

                <article class="tg-chart-card">



                    <div class="tg-chart-header">

                        <h3>توزيع استخدام أقسام التطبيق</h3>

                    </div>



                    <p class="tg-chart-description">

                        يوضح عدد مرات استخدام العمليات المسجلة في كل قسم.

                    </p>



                    @if (count($featureUsageData ?? []) > 0)



                        <div class="tg-chart-container">

                            <canvas

                                id="featureChart"

                                role="img"

                                aria-label="مخطط توزيع استخدام أقسام التطبيق"

                                data-chart='@json($featureUsageData)'

                            ></canvas>

                        </div>



                    @else



                        <div class="tg-chart-empty">

                            لا توجد تفاعلات مسجلة لعرض توزيع الأقسام خلال هذه الفترة.

                        </div>



                    @endif



                </article>



            </div>







        </section>



    </main>



</div>



<script>

(() => {



    const COLORS = [

        '#64C7C4',

        '#399D9A',

        '#83B8D4',

        '#C49A61',

        '#8AA8A0',

        '#7D9CC0',

        '#B6A0C8'

    ];



    function setupCanvas(canvas) {

        const width = canvas.clientWidth;

        const height = canvas.clientHeight;



        if (!width || !height) return null;



        const ratio = window.devicePixelRatio || 1;



        canvas.width = Math.round(width * ratio);

        canvas.height = Math.round(height * ratio);



        const ctx = canvas.getContext('2d');



        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

        ctx.clearRect(0, 0, width, height);



        ctx.font = '12px Arial';

        ctx.textBaseline = 'middle';

        ctx.direction = 'rtl';



        return { ctx, width, height };

    }



    function readData(canvas) {

        try {

            return JSON.parse(canvas.dataset.chart || '[]');

        } catch (error) {

            return [];

        }

    }



    /* الرسم الأول: التفاعل اليومي */

    function drawActivityChart() {



        const canvas = document.getElementById('activityChart');



        if (!canvas) return;



        const points = readData(canvas);

        const setup = setupCanvas(canvas);



        if (!setup || !points.length) return;



        const { ctx, width, height } = setup;



        const right = width - 15;

        const left = 48;

        const top = 20;

        const bottom = height - 40;



        const maxValue = Math.max(

            1,

            ...points.map(item => Number(item.total) || 0)

        );



        const step = Math.max(1, Math.ceil(maxValue / 4));

        const maxY = Math.ceil(maxValue / step) * step;



        const x = index => points.length === 1

            ? (left + right) / 2

            : left + index * (right - left) / (points.length - 1);



        const y = value =>

            bottom - (Number(value) / maxY) * (bottom - top);



        /* خطوط الشبكة */

        ctx.strokeStyle = '#E1EEEE';

        ctx.lineWidth = 1;

        ctx.fillStyle = '#78909A';

        ctx.textAlign = 'right';



        for (let value = 0; value <= maxY; value += step) {



            ctx.beginPath();

            ctx.moveTo(left, y(value));

            ctx.lineTo(right, y(value));

            ctx.stroke();



            ctx.fillText(String(value), left - 9, y(value));

        }



        /* تواريخ المحور الأفقي */

        ctx.textAlign = 'center';



        const stride = Math.max(

            1,

            Math.ceil(points.length / 7)

        );



        points.forEach((point, index) => {



            if (

                index % stride === 0 ||

                index === points.length - 1

            ) {

                ctx.fillStyle = '#486573';

                ctx.fillText(

                    point.label,

                    x(index),

                    height - 17

                );

            }



        });



        /* المساحة أسفل الخط */

        if (points.length > 1) {



            const gradient = ctx.createLinearGradient(

                0, top, 0, bottom

            );



            gradient.addColorStop(

                0,

                'rgba(100,199,196,.28)'

            );



            gradient.addColorStop(

                1,

                'rgba(100,199,196,.02)'

            );



            ctx.beginPath();

            ctx.moveTo(x(0), bottom);



            points.forEach((point, index) => {

                ctx.lineTo(x(index), y(point.total));

            });



            ctx.lineTo(x(points.length - 1), bottom);

            ctx.closePath();



            ctx.fillStyle = gradient;

            ctx.fill();

        }



        /* خط التفاعل */

        ctx.beginPath();



        points.forEach((point, index) => {



            if (index === 0) {

                ctx.moveTo(x(index), y(point.total));

            } else {

                ctx.lineTo(x(index), y(point.total));

            }



        });



        ctx.strokeStyle = '#238782';

        ctx.lineWidth = 2.7;

        ctx.lineJoin = 'round';

        ctx.lineCap = 'round';

        ctx.stroke();



        /* النقاط */

        points.forEach((point, index) => {



            ctx.beginPath();



            ctx.arc(

                x(index),

                y(point.total),

                4,

                0,

                Math.PI * 2

            );



            ctx.fillStyle = '#238782';

            ctx.fill();



            ctx.strokeStyle = '#fff';

            ctx.lineWidth = 2;

            ctx.stroke();



        });



        /* التفاعل عند المرور */

        canvas.onpointermove = event => {



            const rect = canvas.getBoundingClientRect();



            const mouseX = event.clientX - rect.left;



            const index = Math.max(

                0,

                Math.min(

                    points.length - 1,

                    Math.round(

                        ((mouseX - left) / (right - left)) *

                        (points.length - 1)

                    )

                )

            );



            const point = points[index];



            drawActivityChartBase(canvas, points, index);



        };



        canvas.onpointerleave = () => {

            drawActivityChart();

        };



    }



    function drawActivityChartBase(canvas, points, selected) {



        const setup = setupCanvas(canvas);



        if (!setup) return;



        const { ctx, width, height } = setup;



        const right = width - 15;

        const left = 48;

        const top = 20;

        const bottom = height - 40;



        const maxValue = Math.max(

            1,

            ...points.map(item => Number(item.total) || 0)

        );



        const step = Math.max(1, Math.ceil(maxValue / 4));

        const maxY = Math.ceil(maxValue / step) * step;



        const x = index => points.length === 1

            ? (left + right) / 2

            : left + index * (right - left) / (points.length - 1);



        const y = value =>

            bottom - (Number(value) / maxY) * (bottom - top);



        ctx.strokeStyle = '#E1EEEE';

        ctx.fillStyle = '#78909A';

        ctx.textAlign = 'right';



        for (let value = 0; value <= maxY; value += step) {



            ctx.beginPath();

            ctx.moveTo(left, y(value));

            ctx.lineTo(right, y(value));

            ctx.stroke();



            ctx.fillText(String(value), left - 9, y(value));

        }



        ctx.textAlign = 'center';



        const stride = Math.max(1, Math.ceil(points.length / 7));



        points.forEach((point, index) => {

            if (index % stride === 0 || index === points.length - 1) {

                ctx.fillStyle = '#486573';

                ctx.fillText(point.label, x(index), height - 17);

            }

        });



        ctx.beginPath();



        points.forEach((point, index) => {

            if (index === 0) ctx.moveTo(x(index), y(point.total));

            else ctx.lineTo(x(index), y(point.total));

        });



        ctx.strokeStyle = '#238782';

        ctx.lineWidth = 2.7;

        ctx.stroke();



        points.forEach((point, index) => {

            ctx.beginPath();

            ctx.arc(x(index), y(point.total), selected === index ? 6 : 3, 0, Math.PI * 2);

            ctx.fillStyle = '#238782';

            ctx.fill();

        });



        const point = points[selected];

        const label = point.label + '  |  ' + point.total;



        ctx.font = 'bold 12px Arial';



        const boxWidth = ctx.measureText(label).width + 20;

        const boxX = Math.max(

            3,

            Math.min(width - boxWidth - 3, x(selected) - boxWidth / 2)

        );



        const boxY = Math.max(2, y(point.total) - 35);



        ctx.fillStyle = '#2C4A5A';

        ctx.beginPath();

        ctx.roundRect(boxX, boxY, boxWidth, 25, 6);

        ctx.fill();



        ctx.fillStyle = '#fff';

        ctx.textAlign = 'center';

        ctx.fillText(label, boxX + boxWidth / 2, boxY + 13);

    }



    /* الرسم الثاني: توزيع استخدام أقسام التطبيق بالدائرة المجوفة */
    function drawFeatureChart(selectedIndex = -1, pointer = null) {

        const canvas = document.getElementById('featureChart');
        if (!canvas) return;

        const items = readData(canvas).filter(item => Number(item.count) > 0);
        const setup = setupCanvas(canvas);
        if (!setup) return;

        const { ctx, width, height } = setup;
        if (!items.length) return;

        const total = items.reduce((sum, item) => sum + (Number(item.count) || 0), 0);
        if (total <= 0) return;

        const compact = width < 440;
        const centerX = compact ? width / 2 : Math.min(width * 0.34, 205);
        const centerY = compact ? 105 : height / 2;
        const outerRadius = compact ? 78 : Math.min(92, height * 0.34);
        const innerRadius = outerRadius * 0.64;
        const legendX = compact ? width / 2 : width - 12;
        const legendStartY = compact ? 215 : Math.max(34, centerY - (items.length * 31) / 2);
        const legendGap = compact ? 22 : 31;
        const colors = items.map((item, index) => COLORS[index % COLORS.length]);

        let angle = -Math.PI / 2;
        const slices = [];

        items.forEach((item, index) => {
            const value = Number(item.count) || 0;
            const sweep = (value / total) * Math.PI * 2;
            const start = angle;
            const end = angle + sweep;
            slices.push({ start, end, value, index });

            ctx.beginPath();
            ctx.arc(centerX, centerY, outerRadius, start, end);
            ctx.arc(centerX, centerY, innerRadius, end, start, true);
            ctx.closePath();
            ctx.fillStyle = colors[index];
            ctx.fill();

            if (selectedIndex === index) {
                ctx.save();
                ctx.shadowColor = 'rgba(44,74,90,.22)';
                ctx.shadowBlur = 12;
                ctx.lineWidth = 3;
                ctx.strokeStyle = '#FFFFFF';
                ctx.stroke();
                ctx.restore();
            }

            angle = end;
        });

        // إجمالي العمليات في وسط الدائرة.
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillStyle = '#78909A';
        ctx.font = '12px Arial';
        ctx.fillText('إجمالي التفاعلات', centerX, centerY - 13);
        ctx.fillStyle = '#2C4A5A';
        ctx.font = 'bold 25px Arial';
        ctx.fillText(String(total), centerX, centerY + 15);

        // مفتاح الألوان مع العدد والنسبة لكل قسم.
        items.forEach((item, index) => {
            const y = legendStartY + index * legendGap;
            const value = Number(item.count) || 0;
            const percentage = Math.round((value / total) * 100);
            const label = String(item.name || 'عمليات أخرى');

            // ترتيب المفتاح من اليمين إلى اليسار: مربع اللون، اسم القسم، ثم العدد والنسبة.
            ctx.font = selectedIndex === index ? 'bold 12px Arial' : '12px Arial';
            ctx.fillStyle = '#2C4A5A';
            ctx.textAlign = 'right';

            let swatchLeft;
            let labelRight;
            let valueRight;

            if (compact) {
                // في العرض الضيق: العدد يسارًا، ثم الاسم، ثم المربع أقصى اليمين.
                swatchLeft = width - 22;
                labelRight = swatchLeft - 12;
                valueRight = 95;
            } else {
                // في العرض الواسع: أعمدة مستقلة تمنع تداخل النصوص مهما اختلف طول الاسم.
                swatchLeft = width - 22;
                labelRight = swatchLeft - 14;
                valueRight = width - 178;
            }

            ctx.fillText(label, labelRight, y);

            ctx.fillStyle = colors[index];
            ctx.beginPath();
            ctx.roundRect(swatchLeft, y - 5, 10, 10, 2);
            ctx.fill();

            ctx.font = 'bold 11px Arial';
            ctx.fillStyle = '#486573';
            ctx.textAlign = 'right';
            ctx.fillText(`${value}  (${percentage}%)`, valueRight, y);
        });

        // تفاعل عند المرور على أي جزء من الدائرة.
        if (pointer && selectedIndex >= 0) {
            const item = items[selectedIndex];
            const value = Number(item.count) || 0;
            const percentage = ((value / total) * 100).toFixed(1);
            const tooltip = `${item.name}: ${value} (${percentage}%)`;
            ctx.font = 'bold 12px Arial';
            const boxWidth = Math.min(width - 12, ctx.measureText(tooltip).width + 22);
            const boxX = Math.max(6, Math.min(width - boxWidth - 6, pointer.x - boxWidth / 2));
            const boxY = Math.max(6, pointer.y - 38);
            ctx.fillStyle = '#2C4A5A';
            ctx.beginPath();
            ctx.roundRect(boxX, boxY, boxWidth, 27, 7);
            ctx.fill();
            ctx.fillStyle = '#FFFFFF';
            ctx.textAlign = 'center';
            ctx.fillText(tooltip, boxX + boxWidth / 2, boxY + 14);
        }

        canvas.onpointermove = event => {
            const rect = canvas.getBoundingClientRect();
            const px = event.clientX - rect.left;
            const py = event.clientY - rect.top;
            const dx = px - centerX;
            const dy = py - centerY;
            const radius = Math.sqrt(dx * dx + dy * dy);
            let found = -1;

            if (radius >= innerRadius && radius <= outerRadius) {
                let pointerAngle = Math.atan2(dy, dx);
                if (pointerAngle < -Math.PI / 2) pointerAngle += Math.PI * 2;
                found = slices.findIndex(slice => pointerAngle >= slice.start && pointerAngle <= slice.end);
            }

            if (found !== selectedIndex) {
                drawFeatureChart(found, found >= 0 ? { x: px, y: py } : null);
            } else if (found >= 0) {
                drawFeatureChart(found, { x: px, y: py });
            }
        };

        canvas.onpointerleave = () => drawFeatureChart();
    }

    function renderCharts() {

        drawActivityChart();

        drawFeatureChart();

    }



    let resizeFrame = null;



    window.addEventListener('resize', () => {

        cancelAnimationFrame(resizeFrame);

        resizeFrame = requestAnimationFrame(renderCharts);

    });



    if (document.readyState === 'loading') {

        document.addEventListener('DOMContentLoaded', renderCharts);

    } else {

        renderCharts();

    }



})();

</script>



</body>

</html>
