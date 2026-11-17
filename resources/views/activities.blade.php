<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>إحصائيات النشاط البدني | TinyGuide</title>

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
            background: white;
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
            color: white;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }
        .header {
    background: white;
    padding: 20px 25px;
    border: 1px solid #9ADFDB;
    border-radius: 18px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(44, 74, 90, 0.05);
}

        

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 7px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 0;
        }

        .header p {
            color: #78909A;
            font-size: 14px;
        }

        .back-to-records {
            display: inline-block;
            flex-shrink: 0;
            position: relative;
            top: 14px;
            padding: 8px 14px;
            background: #EAF7F7;
            color: #2C8F8C;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            transition: 0.2s;
        }

        .back-to-records:hover {
            background: #64C7C4;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 24px 18px;
            border: 1px solid #9ADFDB;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        .stat-link {
            display: block;
            color: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }

        .stat-link:hover {
            transform: translateY(-3px);
            border-color: #83D2CF;
            box-shadow: 0 7px 18px rgba(44, 74, 90, 0.09);
        }

        .card-hint {
            display: block;
            margin-top: 12px;
            color: #2C8F8C;
            font-size: 12px;
        }

        .stat-icon {
            font-size: 27px;
            margin-bottom: 12px;
        }

        .stat-card h3 {
            font-size: 14px;
            font-weight: normal;
            color: #78909A;
            margin-bottom: 12px;
        }

        .stat-card .number {
            font-size: 30px;
            font-weight: bold;
            color: #2C4A5A;
        }

        /* البوردر الموحد لجميع بطاقات الأقسام */
        .section-card {
            background: white;
            padding: 25px;
            border: 1px solid #9ADFDB;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        .section-card h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .section-description {
            color: #78909A;
            font-size: 13px;
            margin-bottom: 22px;
            line-height: 1.8;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .usage-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .usage-item {
            background: #F1F8F8;
            padding: 22px;
            border-radius: 14px;
        }

        .usage-item h3 {
            font-size: 14px;
            color: #78909A;
            font-weight: normal;
            margin-bottom: 12px;
        }

        .usage-item .value {
            font-size: 30px;
            font-weight: bold;
            color: #2C4A5A;
        }

        .usage-item .hint {
            font-size: 12px;
            color: #78909A;
            margin-top: 10px;
            line-height: 1.7;
        }

        .comparison-details {
            margin-top: 12px;
            font-size: 13px;
            color: #78909A;
            line-height: 1.8;
        }

        .details-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .details-table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            padding: 13px 15px;
            text-align: right;
            border-bottom: 1px solid #E5F0F0;
            font-size: 14px;
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

        .positive {
            color: #27866C !important;
        }

        .negative {
            color: #C65D5D !important;
        }

        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {
            .dashboard {
                display: block;
            }

            .sidebar {
                width: 100%;
                border-left: none;
                border-bottom: 1px solid #E2EEEE;
            }

            .menu {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .menu a {
                margin: 0;
                text-align: center;
            }

            .main-content {
                padding: 15px;
            }

            .stats-grid,
            .usage-grid {
                grid-template-columns: 1fr;
            }

            .section-card {
                padding: 18px;
            }

            .header {
                padding: 18px;
            }
        }

        @media (max-width: 500px) {
            .header-top {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 420px) {
            .menu {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        <!-- عنوان الصفحة -->
        <div class="header">

            <div class="header-top">

                <h1>🏃 إحصائيات النشاط البدني</h1>

                <a href="{{ url('/records') }}" class="back-to-records">
                    العودة للسجلات الصحية
                </a>

            </div>

            <p>متابعة نشاط واستخدام ميزة تسجيل النشاط البدني في تطبيق TinyGuide.</p>

        </div>

        <!-- الإحصائيات العامة -->
        <div class="stats-grid">

            <a href="{{ url('/activities') }}?view=all_activities" class="stat-card stat-link">
                <div class="stat-icon">🏃</div>
                <h3>إجمالي الأنشطة المسجلة</h3>
                <div class="number">
                    {{ number_format($totalActivities) }}
                </div>
                
            </a>

            <a href="{{ url('/activities') }}?view=today_activities" class="stat-card stat-link">
                <div class="stat-icon">📅</div>
                <h3>الأنشطة المسجلة اليوم</h3>
                <div class="number">
                    {{ number_format($todayActivities) }}
                </div>
               
            </a>

            <a href="{{ url('/activities') }}?view=children_with_activities" class="stat-card stat-link">
                <div class="stat-icon">👶</div>
                <h3>الأطفال الذين استخدموا الميزة</h3>
                <div class="number">
                    {{ number_format($childrenWithActivities) }}
                </div>
               
            </a>

            <a href="{{ url('/activities') }}?view=active_children" class="stat-card stat-link">
                <div class="stat-icon">🔄</div>
                <h3>الأطفال النشطون خلال آخر 7 أيام</h3>
                <div class="number">
                    {{ number_format($activeChildrenLast7Days) }}
                </div>
                
            </a>

        </div>

        <!-- تفاصيل البطاقة المحددة -->
        @if($view)
            <div class="section-card" id="activity-details">

                <a href="{{ url('/activities') }}" class="details-back">
                    ← العودة إلى إحصائيات النشاط
                </a>

                @if($view === 'all_activities')
                    <h2>🏃 جميع الأنشطة المسجلة</h2>
                    <p class="section-description">عرض جميع سجلات النشاط المسجلة في النظام.</p>

                    @if($detailsActivities->isNotEmpty())
                        <div class="details-table-wrapper">
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>اسم الطفل</th>
                                        <th>ولي الأمر</th>
                                        <th>اسم النشاط</th>
                                        <th>المدة</th>
                                        <th>الشدة</th>
                                        <th>تاريخ النشاط</th>
                                        <th>وقت النشاط</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailsActivities as $activity)
                                        <tr>
                                            <td>{{ $activity->child?->name ?? '—' }}</td>
                                            <td>{{ $activity->child?->user?->name ?? '—' }}</td>
                                            <td>{{ $activity->activity_name ?? '—' }}</td>
                                            <td>{{ $activity->duration ?? '—' }}</td>
                                            <td>{{ $activity->intensity ?? '—' }}</td>
                                            <td>{{ $activity->activity_date?->format('Y-m-d') ?? '—' }}</td>
                                            <td>{{ $activity->activity_time ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-details">لا توجد أنشطة مسجلة حاليًا.</div>
                    @endif

                @elseif($view === 'today_activities')
                    <h2>📅 الأنشطة المسجلة اليوم</h2>
                    <p class="section-description">عرض سجلات النشاط التي يوافق تاريخها تاريخ اليوم.</p>

                    @if($detailsActivities->isNotEmpty())
                        <div class="details-table-wrapper">
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>اسم الطفل</th>
                                        <th>ولي الأمر</th>
                                        <th>اسم النشاط</th>
                                        <th>المدة</th>
                                        <th>الشدة</th>
                                        <th>وقت النشاط</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailsActivities as $activity)
                                        <tr>
                                            <td>{{ $activity->child?->name ?? '—' }}</td>
                                            <td>{{ $activity->child?->user?->name ?? '—' }}</td>
                                            <td>{{ $activity->activity_name ?? '—' }}</td>
                                            <td>{{ $activity->duration ?? '—' }}</td>
                                            <td>{{ $activity->intensity ?? '—' }}</td>
                                            <td>{{ $activity->activity_time ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-details">لا توجد أنشطة مسجلة اليوم.</div>
                    @endif

                @elseif($view === 'children_with_activities')
                    <h2>👶 الأطفال الذين استخدموا الميزة</h2>
                    <p class="section-description">الأطفال الذين لديهم نشاط واحد على الأقل منذ استخدام النظام.</p>

                    @if($detailsChildren->isNotEmpty())
                        <div class="details-table-wrapper">
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>رقم الطفل</th>
                                        <th>اسم الطفل</th>
                                        <th>ولي الأمر</th>
                                        <th>عدد الأنشطة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailsChildren as $child)
                                        <tr>
                                            <td>{{ $child->child_id }}</td>
                                            <td>{{ $child->name }}</td>
                                            <td>{{ $child->user?->name ?? '—' }}</td>
                                            <td>{{ $child->activities_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-details">لا يوجد أطفال لديهم أنشطة مسجلة.</div>
                    @endif

                @elseif($view === 'active_children')
                    <h2>🔄 الأطفال النشطون خلال آخر 7 أيام</h2>
                    <p class="section-description">الأطفال الذين سُجل لهم نشاط واحد على الأقل خلال آخر 7 أيام، بما فيها اليوم.</p>

                    @if($detailsChildren->isNotEmpty())
                        <div class="details-table-wrapper">
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>رقم الطفل</th>
                                        <th>اسم الطفل</th>
                                        <th>ولي الأمر</th>
                                        <th>عدد الأنشطة خلال آخر 7 أيام</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailsChildren as $child)
                                        <tr>
                                            <td>{{ $child->child_id }}</td>
                                            <td>{{ $child->name }}</td>
                                            <td>{{ $child->user?->name ?? '—' }}</td>
                                            <td>{{ $child->activities_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-details">لا يوجد أطفال نشطون خلال آخر 7 أيام.</div>
                    @endif
                @endif

            </div>
        @endif

        <!-- الرسم البياني -->
        <div class="section-card">

            <h2>📈 نشاط تسجيل الأنشطة خلال آخر 7 أيام</h2>

            <p class="section-description">
                يوضح الرسم عدد سجلات النشاط التي أُضيفت يوميًا،
                لمتابعة مستوى استخدام الميزة داخل التطبيق.
            </p>

            <div class="chart-container">
                <canvas id="activitiesChart"></canvas>
            </div>

        </div>

        <!-- مؤشرات الاستخدام -->
        <div class="section-card">

            <h2>📊 مؤشرات استخدام ميزة النشاط البدني</h2>

            <p class="section-description">
                تساعد هذه المؤشرات على تقييم استمرارية استخدام الميزة
                ومقارنة نشاط التسجيل بالفترة السابقة.
            </p>

            <div class="usage-grid">

                <!-- استمرارية الاستخدام -->
                <div class="usage-item">

                    <h3>🔁 الأطفال الذين كرروا تسجيل النشاط</h3>

                    <div class="value">
                        {{ number_format($childrenWithRepeatedActivity) }}
                    </div>

                    <p class="hint">
                        عدد الأطفال الذين سُجل لهم نشاط في يومين مختلفين
                        على الأقل خلال آخر 7 أيام.
                    </p>

                </div>

                <!-- المقارنة بالأسبوع السابق -->
                <div class="usage-item">

                    <h3>📊 التغير مقارنة بالأيام السبعة السابقة</h3>

                    @if($weeklyChangePercentage === null)

                        <div class="value">—</div>

                        <p class="hint">
                            لا تتوفر نسبة مقارنة؛ لعدم وجود أنشطة مسجلة
                            في الفترة السابقة.
                        </p>

                    @else

                        <div class="value
                            {{ $weeklyChangePercentage > 0 ? 'positive' : '' }}
                            {{ $weeklyChangePercentage < 0 ? 'negative' : '' }}">

                            @if($weeklyChangePercentage > 0)
                                +
                            @endif

                            {{ $weeklyChangePercentage }}%

                        </div>

                        <p class="hint">
                            نسبة التغير في عدد سجلات النشاط مقارنة
                            بالأيام السبعة السابقة.
                        </p>

                    @endif

                    <div class="comparison-details">
                        آخر 7 أيام:
                        <strong>{{ number_format($currentWeekActivities) }}</strong>
                        سجل
                        <br>

                        الأيام السبعة السابقة:
                        <strong>{{ number_format($previousWeekActivities) }}</strong>
                        سجل
                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

<script>
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);

    const chartCanvas = document.getElementById('activitiesChart');

    if (chartCanvas && typeof Chart !== 'undefined') {
        new Chart(chartCanvas, {
            type: 'line',

            data: {
                labels: chartLabels,

                datasets: [{
                    label: 'عدد الأنشطة المسجلة',
                    data: chartData,
                    borderColor: '#64C7C4',
                    backgroundColor: 'rgba(100, 199, 196, 0.15)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#64C7C4'
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
</script>

</body>
</html>