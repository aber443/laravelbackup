<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>قراءات السكر | TinyGuide</title>

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

        .header {
            background: #FFFFFF;
            padding: 20px 25px;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.05);
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 7px;
        }

        .header p {
            color: #78909A;
            font-size: 14px;
        }

        /* Statistics */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.05);
            border-top: 4px solid #64C7C4;
        }

        .stat-title {
            color: #78909A;
            font-size: 14px;
            margin-bottom: 14px;
        }

        .stat-value {
            font-size: 27px;
            font-weight: bold;
            color: #2C4A5A;
            overflow-wrap: anywhere;
        }

        .stat-unit {
            font-size: 13px;
            color: #78909A;
            font-weight: normal;
        }

        /* Filters */

        .filter-card {
            background: #FFFFFF;
            padding: 22px;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.05);
        }

        .filter-card h2 {
            font-size: 17px;
            margin-bottom: 18px;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 170px;
        }

        .form-group label {
            font-size: 13px;
            color: #78909A;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #DCEAEA;
            border-radius: 10px;
            background: #FFFFFF;
            color: #2C4A5A;
            font-size: 14px;
            outline: none;
        }

        .form-control:focus {
            border-color: #64C7C4;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 18px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
        }

        .btn-primary {
            background: #64C7C4;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background: #4DB5B2;
        }

        .btn-secondary {
            background: #EAF7F7;
            color: #2C4A5A;
        }

        /* Table */

        .table-card {
            background: #FFFFFF;
            padding: 24px;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        .table-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .table-heading h2 {
            font-size: 18px;
        }

        .results-count {
            background: #EAF7F7;
            color: #2C7775;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: bold;
        }

        .table-wrapper {
        width: 100%;
        max-height: 430px;
    overflow-y: auto;
    overflow-x: hidden;
    border: 1px solid #EDF3F3;
    border-radius: 12px;
}

.table-wrapper table {
    width: 100%;
    min-width: 0;
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 0;
}

.table-wrapper th,
.table-wrapper td {
    padding: 12px 6px;
    font-size: 12px;
    overflow-wrap: anywhere;
    white-space: normal;
}

.table-wrapper th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #EAF7F7;
}

.table-wrapper th:first-child,
.table-wrapper td:first-child {
    width: 5%;
    text-align: center;
}

.table-wrapper th:nth-child(2),
.table-wrapper td:nth-child(2) {
    width: 15%;
}

.table-wrapper th:nth-child(3),
.table-wrapper td:nth-child(3) {
    width: 15%;
}

.table-wrapper th:nth-child(4),
.table-wrapper td:nth-child(4) {
    width: 18%;
}

.table-wrapper th:nth-child(5),
.table-wrapper td:nth-child(5) {
    width: 16%;
}

.table-wrapper th:nth-child(6),
.table-wrapper td:nth-child(6) {
    width: 16%;
}

.table-wrapper th:nth-child(7),
.table-wrapper td:nth-child(7) {
    width: 15%;
}

.table-wrapper .reading-value,
.table-wrapper .date-time {
    white-space: normal;
}

.table-wrapper::-webkit-scrollbar {
    width: 8px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: #64C7C4;
    border-radius: 10px;
}

.table-wrapper::-webkit-scrollbar-track {
    background: #F1F8F8;
}

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th,
        td {
            padding: 15px 12px;
            text-align: right;
            border-bottom: 1px solid #EDF3F3;
        }

        th {
            background: #EAF7F7;
            font-size: 14px;
            color: #2C4A5A;
            white-space: nowrap;
        }

        td {
            font-size: 14px;
        }

        tbody tr:hover {
            background: #F8FCFC;
        }

        .reading-value {
            display: inline-block;
            background: #EAF7F7;
            padding: 6px 10px;
            border-radius: 10px;
            font-weight: bold;
            color: #2C4A5A;
            white-space: nowrap;
        }

        .child-link {
            color: #2C9995;
            text-decoration: none;
            font-weight: bold;
        }

        .child-link:hover {
            text-decoration: underline;
        }

        .date-time {
            display: flex;
            flex-direction: column;
            gap: 4px;
            white-space: nowrap;
        }

        .date-time .date {
            font-weight: bold;
        }

        .date-time .time {
            color: #78909A;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            padding: 40px 20px;
            color: #78909A;
        }

        /* Responsive */

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

            .header,
            .table-card,
            .filter-card {
                padding: 18px;
            }

            .stats-grid {
                gap: 12px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-value {
                font-size: 23px;
            }
        }

        @media (max-width: 420px) {
            .menu {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }


        /* =====================================
   TinyGuide - Glucose Charts
===================================== */

.charts-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 25px;
    margin-top: 35px;
}

.chart-card {
    background: #FFFFFF;
    padding: 24px;
    border-radius: 18px;
    box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
    min-width: 0;
}

.chart-card h2 {
    font-size: 17px;
    color: #2C4A5A;
    margin-bottom: 10px;
}

.chart-card p {
    color: #78909A;
    font-size: 13px;
    margin-bottom: 20px;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 280px;
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

/* Tablet and Mobile */

@media (max-width: 1100px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {
    .chart-card {
        padding: 18px;
    }

    .chart-container {
        height: 250px;
    }
}




/* Pagination - TinyGuide */

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 20px;
    margin-bottom: 35px;
    padding: 15px 20px;
    background: #ffffff;
    border: 1px solid #e1eeee;
    border-radius: 12px;
}

.pagination-info {
    color: #607d83;
    font-size: 13px;
    font-weight: 500;
}

.pagination-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 6px;
}

.page-btn {
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid #d6e8e8;
    background: #ffffff;
    color: #397d84;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.page-btn:hover {
    background: #e7f7f7;
    border-color: #76c9ca;
}

.page-btn.active {
    background: #46b7b9;
    color: #ffffff;
    border-color: #46b7b9;
}

.page-btn.disabled {
    color: #aebfc1;
    background: #f5f8f8;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .pagination-container {
        flex-direction: column;
        text-align: center;
    }
}



/* عنوان الصفحة وزر العودة في نفس السطر */
.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 7px;
}

.header-top h1 {
    margin-bottom: 0;
}

/* زر العودة للسجلات الصحية */
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

@media (max-width: 500px) {
    .header-top {
        flex-wrap: wrap;
    }
}

    </style>
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        <!-- Header -->
<div class="header">

    <div class="header-top">

        <h1>🩸 قراءات السكر</h1>

        <a href="{{ url('/records') }}" class="back-to-records">
             العودة للسجلات الصحية
        </a>

    </div>

    <p>إحصائيات عامة ومتابعة قراءات السكر المسجلة للأطفال في TinyGuide.</p>

</div>
        <!-- Statistics -->

        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-title">إجمالي قراءات السكر</div>
                <div class="stat-value">
                    {{ number_format($totalReadings) }}
                </div>
            </div>

           <div class="stat-card">
    <div class="stat-title">
        قراءات اليوم ({{ \Carbon\Carbon::today()->format('Y/m/d') }})
    </div>

    <div class="stat-value">
        {{ number_format($todayReadings) }}
    </div>
</div>

            <div class="stat-card">
                <div class="stat-title">الأطفال الذين لديهم قراءات</div>
                <div class="stat-value">
                    {{ number_format($childrenWithReadings) }}
                </div>
            </div>

           <a href="{{ url('/children?without_readings=1') }}"
   class="stat-card"
   style="display: flex; flex-direction: column; text-decoration: none; color: inherit; cursor: pointer;">

    <div class="stat-title">
        الأطفال بدون قراءات
    </div>

    <div class="stat-value">
        {{ number_format($childrenWithoutReadings) }}
    </div>

    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">

        <span style="
            background: #e7f7f7;
            color: #258b91;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        ">
            عرض الأطفال ←
        </span>

    </div>

</a>

        </div>

        <!-- Search & Filters -->

        <div class="filter-card">

            <h2>🔎 البحث والتصفية</h2>

            <form
                method="GET"
                action="{{ url('/glucose-readings') }}"
                class="filter-form"
            >

                <div class="form-group">
                    <label for="search">اسم الطفل أو ولي الأمر</label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="ابحث باسم الطفل أو ولي الأمر..."
                        value="{{ $search ?? '' }}"
                    >
                </div>

                <div class="form-group">
                    <label for="date_from">من تاريخ</label>

                    <input
                        type="date"
                        id="date_from"
                        name="date_from"
                        class="form-control"
                        value="{{ $dateFrom ?? '' }}"
                    >
                </div>

                <div class="form-group">
                    <label for="date_to">إلى تاريخ</label>

                    <input
                        type="date"
                        id="date_to"
                        name="date_to"
                        class="form-control"
                        value="{{ $dateTo ?? '' }}"
                    >
                </div>

                <div class="filter-actions">

                    <button type="submit" class="btn btn-primary">
                        بحث
                    </button>

                    <a
                        href="{{ url('/glucose-readings') }}"
                        class="btn btn-secondary"
                    >
                        عرض الكل
                    </a>

                </div>

            </form>

        </div>

    
        <!-- Readings Table -->

        <div class="table-card">

            <div class="table-heading">
                <h2>📋 سجل قراءات السكر</h2>

                <span class="results-count">
                    عدد النتائج: {{ $readings->count() }}
                </span>
            </div>

            @if($readings->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الطفل</th>
                                <th>ولي الأمر</th>
                                <th>مستوى السكر</th>
                                <th>نوع القراءة</th>
                                <th>الوجبة المرتبطة</th>
                                <th>التاريخ والوقت</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($readings as $reading)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    @if($reading->child)
                                        <a
                                           href="{{ route('children.show', $reading->child->child_id) }}"
                                            class="child-link"
                                        >
                                            {{ $reading->child->name }}
                                        </a>
                                    @else
                                        غير محدد
                                    @endif
                                </td>

                                <td>
                                    {{ $reading->child?->user?->name ?? 'غير محدد' }}
                                </td>

                                <td>
                                    <span class="reading-value">
                                        {{ $reading->glucose_level }}
                                        {{ $reading->unit ?: 'وحدة غير محددة' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $reading->reading_type ?: '—' }}
                                </td>

                                <td>
                                    {{ $reading->meal?->meal_type ?? '—' }}
                                </td>

                                <td>
                                    <div class="date-time">

                                        @if($reading->reading_date)
                                            <span class="date">
                                                {{ \Carbon\Carbon::parse($reading->reading_date)->format('Y-m-d') }}
                                            </span>

                                            <span class="time">
                                                {{ $reading->reading_time
                                                    ? \Carbon\Carbon::parse($reading->reading_time)->format('H:i')
                                                    : '—' }}
                                            </span>
                                        @else
                                            —
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty">
                    @if(!empty($search) || !empty($dateFrom) || !empty($dateTo))
                        لا توجد قراءات مطابقة لمعايير البحث.
                    @else
                        لا توجد قراءات سكر مسجلة حاليًا.
                    @endif
                </div>

            @endif

        </div>



        @if ($readings->hasPages())
    <div class="pagination-container">

        <div class="pagination-info">
            عرض {{ $readings->firstItem() }}
            إلى {{ $readings->lastItem() }}
            من أصل {{ $readings->total() }} قراءة
        </div>

        <div class="pagination-buttons">

            @if ($readings->onFirstPage())
                <span class="page-btn disabled">السابق</span>
            @else
                <a href="{{ $readings->previousPageUrl() }}"
                   class="page-btn">
                    السابق
                </a>
            @endif

            @foreach ($readings->getUrlRange(1, $readings->lastPage()) as $page => $url)

                <a href="{{ $url }}"
                   class="page-btn {{ $page == $readings->currentPage() ? 'active' : '' }}">
                    {{ $page }}
                </a>

            @endforeach

            @if ($readings->hasMorePages())
                <a href="{{ $readings->nextPageUrl() }}"
                   class="page-btn">
                    التالي
                </a>
            @else
                <span class="page-btn disabled">التالي</span>
            @endif

        </div>

    </div>
@endif

         <!-- Charts Section -->
<div class="charts-grid">

    <!-- Last 7 Days Chart -->
    <div class="chart-card">
        <h2>📈 قراءات السكر خلال آخر 7 أيام</h2>
        <p>عدد القياسات المسجلة يوميًا لجميع الأطفال</p>

        <div class="chart-container">
            <canvas id="readingsChart"></canvas>
        </div>
    </div>

    <!-- Measurement Types Chart -->
    <div class="chart-card">
        <h2> توزيع القراءات حسب نوع القياس</h2>
        <p>توزيع القياسات المسجلة حسب نوعها</p>

        <div class="chart-container">
            <canvas id="typesChart"></canvas>
        </div>
    </div>

</div>

    </main>

</div>
<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // بيانات آخر 7 أيام من Laravel
    const chartLabels = @json($chartLabels);
    const chartValues = @json($chartValues);

    // أنواع القياسات من MySQL
    const measurementTypes = @json($measurementTypes);

    const readingsCanvas = document.getElementById('readingsChart');
    const typesCanvas = document.getElementById('typesChart');

    if (typeof Chart === 'undefined') {
        console.error('Chart.js failed to load.');
        return;
    }

    // الرسم الأول: عدد القراءات خلال آخر 7 أيام
    if (readingsCanvas) {
        new Chart(readingsCanvas, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'عدد القراءات',
                    data: chartValues,
                    borderColor: '#64C7C4',
                    backgroundColor: 'rgba(100, 199, 196, 0.15)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 5,
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

    // الرسم الثاني: توزيع أنواع القياسات
    if (typesCanvas) {

        const typeLabels = measurementTypes.map(item => item.measurement_type);
        const typeValues = measurementTypes.map(item => Number(item.total));

        new Chart(typesCanvas, {
            type: 'doughnut',
            data: {
                labels: typeLabels,
                datasets: [{
                    data: typeValues,
                    backgroundColor: [
                        '#64C7C4',
                        '#89A8E8',
                        '#FFD08A',
                        '#F5A6B8',
                        '#A6D8C4',
                        '#C5B5E8'
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

});
</script>
</body>
</html>