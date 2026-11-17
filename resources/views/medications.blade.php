<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>سجلات الأدوية | TinyGuide</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #F1F8F8;
            color: #2C4A5A;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            flex-shrink: 0;
            background: #FFFFFF;
            padding: 30px 20px;
            border-left: 1px solid #E2EEEE;
        }

        .logo {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo h2 {
            color: #64C7C4;
            margin-bottom: 5px;
        }

        .logo p {
            color: #8AA1AA;
            font-size: 14px;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #2C4A5A;
            padding: 14px 16px;
            margin-bottom: 8px;
            border-radius: 12px;
        }

        .menu a:hover,
        .menu a.active {
            background: #64C7C4;
            color: #FFFFFF;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        /* رأس الصفحة */
        .header {
            background: #FFFFFF;
            padding: 22px 26px;
            border: 1px solid #9ADFDB;
            border-radius: 18px;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, .05);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .header p {
            color: #78909A;
            font-size: 14px;
        }

        .back-to-records {
            display: inline-block;
            background: #EAF7F7;
            color: #2C4A5A;
            text-decoration: none;
            padding: 11px 17px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap;
        }

        .back-to-records:hover {
            background: #64C7C4;
            color: #FFFFFF;
        }

        /* البطاقات الإحصائية */
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .overview-card {
            background: #FFFFFF;
            border-radius: 18px;
            padding: 24px;
            border: 1px solid #9ADFDB;
            box-shadow: 0 4px 15px rgba(44, 74, 90, .05);
            border-top: 4px solid #64C7C4;
        }

        /* البطاقات القابلة للضغط */
        .overview-card-link {
            display: block;
            color: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
        }

        .overview-card-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(44, 74, 90, .09);
        }

        .overview-card:nth-child(2) {
            border-top-color: #9BCBC9;
        }

        .overview-card:nth-child(3) {
            border-top-color: #79AFC1;
        }

        .card-title {
            color: #78909A;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .card-value {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .card-description {
            color: #8AA1AA;
            font-size: 13px;
            line-height: 1.8;
        }

        .card-hint {
            display: block;
            margin-top: 12px;
            color: #2C8F8C;
            font-size: 12px;
        }

        .change {
            display: inline-block;
            margin-top: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .change.positive {
            color: #269C7B;
        }

        .change.negative {
            color: #D16B6B;
        }

        .change.neutral {
            color: #78909A;
        }

        /* بطاقة تفاصيل البيانات */
        .details-card {
            background: #FFFFFF;
            border: 1px solid #9ADFDB;
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, .05);
        }

        .details-card h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .details-description {
            color: #78909A;
            font-size: 13px;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .details-back {
            display: inline-block;
            margin-bottom: 18px;
            color: #2C8F8C;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }

        .details-back:hover {
            text-decoration: underline;
        }

        .details-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .details-table {
            width: 100%;
            min-width: 680px;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            padding: 13px 15px;
            text-align: right;
            border-bottom: 1px solid #E5F0F0;
            font-size: 14px;
            white-space: nowrap;
        }

        .details-table th {
            background: #EAF7F7;
            color: #2C4A5A;
            font-weight: bold;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .empty-details {
            padding: 25px;
            text-align: center;
            color: #78909A;
            background: #F7FBFB;
            border-radius: 12px;
        }

        /* الرسوم البيانية */
        .analytics-grid {
            display: grid;
            grid-template-columns: minmax(280px, 0.9fr) minmax(0, 1.5fr);
            gap: 20px;
            align-items: stretch;
        }

        .analytics-card {
            background: #FFFFFF;
            border: 1px solid #9ADFDB;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, .05);
            min-width: 0;
        }

        .analytics-card h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .analytics-card p {
            color: #78909A;
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 20px;
        }

        .donut-container {
            position: relative;
            width: 100%;
            max-width: 290px;
            height: 270px;
            margin: 0 auto;
        }

        .donut-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }

        .donut-center strong {
            display: block;
            font-size: 28px;
        }

        .donut-center span {
            display: block;
            font-size: 12px;
            color: #78909A;
            margin-top: 5px;
        }

        .donut-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }

        .donut-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 13px 15px;
            border-radius: 12px;
            background: #F5FAFA;
        }

        .detail-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dot.used {
            background: #64C7C4;
        }

        .dot.unused {
            background: #DCEAEA;
        }

        .detail-value {
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .line-chart-container {
            position: relative;
            width: 100%;
            height: 350px;
        }

        .empty-chart {
            text-align: center;
            font-size: 13px;
            color: #78909A;
            padding-top: 12px;
        }

        /* الشاشات المتوسطة */
        @media (max-width: 1100px) {
            .overview-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .analytics-grid {
                grid-template-columns: 1fr;
            }
        }

        /* الهواتف */
        @media (max-width: 700px) {
            .dashboard {
                display: block;
            }

            .sidebar {
                width: 100%;
                border-left: none;
                border-bottom: 1px solid #E2EEEE;
            }

            .main-content {
                padding: 15px;
            }

            .header-top {
                flex-direction: column;
                align-items: flex-start;
            }

            .overview-grid {
                grid-template-columns: 1fr;
            }

            .analytics-card {
                padding: 18px;
            }

            .line-chart-container {
                height: 280px;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        <!-- رأس الصفحة -->
        <div class="header">
            <div class="header-top">
                <div>
                    <h1>💊 سجلات الأدوية</h1>
                    <p>متابعة استخدام ميزة الأدوية في تطبيق TinyGuide.</p>
                </div>

                <a href="{{ url('/records') }}" class="back-to-records">
                    العودة للسجلات الصحية
                </a>
            </div>
        </div>

        <!-- البطاقات الإحصائية -->
        <div class="overview-grid">

            <!-- البطاقة الأولى -->
            <a href="{{ url('/medications') }}?view=all_medications"
               class="overview-card overview-card-link">

                <div class="card-title">💊 إجمالي سجلات الأدوية</div>

                <div class="card-value">
                    {{ number_format($totalMedications) }}
                </div>

               

               
            </a>

            <!-- البطاقة الثانية -->
            <a href="{{ url('/medications') }}?view=children_with_medications"
               class="overview-card overview-card-link">

                <div class="card-title">👶 الأطفال الذين لديهم أدوية مسجلة</div>

                <div class="card-value">
                    {{ number_format($childrenWithMedications) }}
                </div>


                

            </a>

            <!-- البطاقة الثالثة -->
            <a href="{{ url('/medications') }}?view=active_users"
               class="overview-card overview-card-link">

                <div class="card-title">📈 المستخدمون النشطون هذا الشهر</div>

                <div class="card-value">
                    {{ number_format($currentMonthActiveUsers) }}
                </div>

                

                @if($previousMonthActiveUsers > 0)

                    @if($monthlyActivityChange > 0)
                        <span class="change positive">
                            ↑ {{ abs($monthlyActivityChange) }}% مقارنة بالشهر السابق
                        </span>

                    @elseif($monthlyActivityChange < 0)
                        <span class="change negative">
                            ↓ {{ abs($monthlyActivityChange) }}% مقارنة بالشهر السابق
                        </span>

                    @else
                        <span class="change neutral">
                            لا يوجد تغير عن الشهر السابق
                        </span>
                    @endif

                @elseif($currentMonthActiveUsers > 0)
                    <span class="change positive">
                        نشاط جديد مقارنة بالشهر السابق
                    </span>

                @else
                    <span class="change neutral">
                        لا يوجد نشاط مسجل هذا الشهر
                    </span>
                @endif

                

            </a>

        </div>

        <!-- تفاصيل البطاقة المحددة -->
        @if($view)

            <section class="details-card" id="medication-details">

                <a href="{{ url('/medications') }}" class="details-back">
                    ← العودة إلى إحصائيات الأدوية
                </a>

                <!-- جميع سجلات الأدوية -->
                @if($view === 'all_medications')

                    <h2>💊 جميع سجلات الأدوية</h2>

                    <p class="details-description">
                        جميع الأدوية المسجلة في التطبيق مع بيانات الطفل وولي الأمر.
                    </p>

                    @if($detailsMedications->isNotEmpty())

                        <div class="details-table-wrapper">

                            <table class="details-table">

                                <thead>
                                    <tr>
                                        <th>رقم الدواء</th>
                                        <th>اسم الطفل</th>
                                        <th>ولي الأمر</th>
                                        <th>اسم الدواء</th>
                                        <th>نوع الإنسولين</th>
                                        <th>الجرعة</th>
                                        <th>التكرار</th>
                                        <th>تاريخ البدء</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($detailsMedications as $medication)

                                        <tr>
                                            <td>{{ $medication->medication_id }}</td>

                                            <td>
                                                {{ $medication->child?->name ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $medication->child?->user?->name ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $medication->medication_name ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $medication->insulin_type ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $medication->dosage ?? '—' }}
                                                {{ $medication->dosage_unit ?? '' }}
                                            </td>

                                            <td>
                                                {{ $medication->frequency ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $medication->start_date?->format('Y-m-d') ?? '—' }}
                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-details">
                            لا توجد سجلات أدوية حاليًا.
                        </div>

                    @endif

                <!-- الأطفال الذين لديهم أدوية -->
                @elseif($view === 'children_with_medications')

                    <h2>👶 الأطفال الذين لديهم أدوية مسجلة</h2>

                    <p class="details-description">
                        الأطفال الذين لديهم دواء واحد على الأقل مسجل في التطبيق.
                    </p>

                    @if($detailsChildren->isNotEmpty())

                        <div class="details-table-wrapper">

                            <table class="details-table">

                                <thead>
                                    <tr>
                                        <th>رقم الطفل</th>
                                        <th>اسم الطفل</th>
                                        <th>ولي الأمر</th>
                                        <th>عدد سجلات الأدوية</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($detailsChildren as $child)

                                        <tr>
                                            <td>{{ $child->child_id }}</td>

                                            <td>{{ $child->name }}</td>

                                            <td>
                                                {{ $child->user?->name ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $child->medications_count }}
                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-details">
                            لا يوجد أطفال لديهم أدوية مسجلة حاليًا.
                        </div>

                    @endif

                <!-- المستخدمون النشطون هذا الشهر -->
                @elseif($view === 'active_users')

                    <h2>📈 المستخدمون النشطون هذا الشهر</h2>

                    <p class="details-description">
                        المستخدمون الذين نفّذوا عملية واحدة على الأقل مرتبطة بميزة الأدوية خلال الشهر الحالي.
                        يُحسب كل مستخدم مرة واحدة في الإحصائية، ويظهر هنا عدد عملياته.
                    </p>

                    @if($detailsActiveUsers->isNotEmpty())

                        <div class="details-table-wrapper">

                            <table class="details-table">

                                <thead>
                                    <tr>
                                        <th>رقم المستخدم</th>
                                        <th>اسم المستخدم</th>
                                        <th>رقم الهاتف</th>
                                        <th>عدد العمليات</th>
                                        <th>آخر نشاط</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($detailsActiveUsers as $item)

                                        <tr>
                                            <td>
                                                {{ $item['user']->user_id }}
                                            </td>

                                            <td>
                                                {{ $item['user']->name ?? '—' }}
                                            </td>

                                            <td dir="ltr">
                                                {{ $item['user']->phone ?? '—' }}
                                            </td>

                                            <td>
                                                {{ $item['activities_count'] }}
                                            </td>

                                            <td>
                                                {{ $item['last_activity']
                                                    ? \Carbon\Carbon::parse($item['last_activity'])->format('Y-m-d H:i')
                                                    : '—' }}
                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="empty-details">
                            لا يوجد مستخدمون نفّذوا عمليات مرتبطة بالأدوية خلال الشهر الحالي.
                        </div>

                    @endif

                @endif

            </section>

        @endif

        <!-- الرسوم البيانية -->
        <div class="analytics-grid">

            <!-- الرسم الدائري -->
            <section class="analytics-card">

                <h2>انتشار ميزة الأدوية</h2>

                <p>
                    نسبة الأطفال الذين لديهم سجلات أدوية
                    مقارنة بمن ليس لديهم سجلات.
                </p>

                <div class="donut-container">

                    <canvas id="medicationUsageChart"></canvas>

                    <div class="donut-center">
                        <strong>{{ $medicationUsagePercentage }}%</strong>
                        <span>لديهم أدوية مسجلة</span>
                    </div>

                </div>

                <div class="donut-details">

                    <div class="donut-detail">

                        <div class="detail-label">
                            <span class="dot used"></span>
                            لديهم أدوية مسجلة
                        </div>

                        <div class="detail-value">
                            {{ number_format($childrenWithMedications) }}
                            ({{ $medicationUsagePercentage }}%)
                        </div>

                    </div>

                    <div class="donut-detail">

                        <div class="detail-label">
                            <span class="dot unused"></span>
                            ليس لديهم أدوية مسجلة
                        </div>

                        <div class="detail-value">
                            {{ number_format($childrenWithoutMedications) }}
                            ({{ $totalChildren > 0
                                ? round(($childrenWithoutMedications / $totalChildren) * 100, 1)
                                : 0 }}%)
                        </div>

                    </div>

                </div>

            </section>

            <!-- الرسم الخطي -->
            <section class="analytics-card">

                <h2>تطور استخدام ميزة الأدوية</h2>

                <p>
                    عدد المستخدمين الذين تفاعلوا فعليًا
                    مع ميزة الأدوية خلال آخر 6 أشهر.
                </p>

                <div class="line-chart-container">
                    <canvas id="medicationActivityChart"></canvas>
                </div>

                @if(array_sum($monthlyActiveUsers) === 0)

                    <div class="empty-chart">
                        لا توجد بيانات نشاط مسجلة خلال آخر 6 أشهر.
                    </div>

                @endif

            </section>

        </div>

    </main>

</div>

<script>

    // بيانات انتشار ميزة الأدوية
    const totalChildren = @json($totalChildren);

    const childrenWithMedications = @json($childrenWithMedications);

    const childrenWithoutMedications = @json($childrenWithoutMedications);

    const donutCanvas = document.getElementById('medicationUsageChart');

    if (donutCanvas && typeof Chart !== 'undefined') {

        new Chart(donutCanvas, {

            type: 'doughnut',

            data: {

                labels: totalChildren > 0
                    ? ['لديهم أدوية مسجلة', 'ليس لديهم أدوية مسجلة']
                    : ['لا توجد بيانات'],

                datasets: [{

                    data: totalChildren > 0
                        ? [childrenWithMedications, childrenWithoutMedications]
                        : [1],

                    backgroundColor: totalChildren > 0
                        ? ['#64C7C4', '#DCEAEA']
                        : ['#E8F0F0'],

                    borderColor: '#FFFFFF',

                    borderWidth: 4,

                    hoverOffset: totalChildren > 0 ? 8 : 0

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '76%',

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        enabled: totalChildren > 0,

                        callbacks: {

                            label: function(context) {

                                const count = context.parsed;

                                const percentage = totalChildren > 0
                                    ? ((count / totalChildren) * 100).toFixed(1)
                                    : '0.0';

                                return context.label + ': ' +
                                    count + ' طفل (' + percentage + '%)';

                            }

                        }

                    }

                }

            }

        });

    }

    // بيانات نشاط المستخدمين خلال آخر 6 أشهر
    const activityMonths = @json($activityMonths);

    const monthlyActiveUsers = @json($monthlyActiveUsers);

    const activityCanvas = document.getElementById('medicationActivityChart');

    if (activityCanvas && typeof Chart !== 'undefined') {

        new Chart(activityCanvas, {

            type: 'line',

            data: {

                labels: activityMonths,

                datasets: [{

                    label: 'المستخدمون النشطون',

                    data: monthlyActiveUsers,

                    borderColor: '#64C7C4',

                    backgroundColor: 'rgba(100, 199, 196, 0.12)',

                    fill: true,

                    tension: 0.35,

                    borderWidth: 3,

                    pointRadius: 5,

                    pointHoverRadius: 7,

                    pointBackgroundColor: '#64C7C4'

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                return 'المستخدمون النشطون: ' + context.parsed.y;

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0,

                            stepSize: 1

                        },

                        title: {

                            display: true,

                            text: 'عدد المستخدمين'

                        }

                    },

                    x: {

                        grid: {

                            display: false

                        }

                    }

                }

            }

        });

    }

</script>

</body>
</html>