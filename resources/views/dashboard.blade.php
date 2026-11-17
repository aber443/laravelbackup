<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرئيسية | TinyGuide</title>

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
    direction: rtl;
}

.dashboard {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 250px;
    background: #FFFFFF;
    padding: 25px 18px;
    border-left: 1px solid #E2EEEE;
    flex-shrink: 0;
}

.logo {
    text-align: center;
    margin-bottom: 28px;
}

.logo h2 {
    color: #64C7C4;
    margin-bottom: 4px;
}

.logo p {
    color: #8AA1AA;
    font-size: 13px;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #2C4A5A;
    padding: 12px 14px;
    margin-bottom: 6px;
    border-radius: 11px;
    transition: 0.3s;
}

.menu a:hover,
.menu a.active {
    background: #64C7C4;
    color: #FFFFFF;
}

.menu a i {
    font-size: 18px;
    line-height: 1;
}

.main-content {
    flex: 1;
    min-width: 0;
    padding: 20px;
}

.header {
    background: #FFFFFF;
    padding: 16px 20px;
    border-radius: 16px;
    margin-bottom: 18px;
    box-shadow: 0 3px 12px rgba(44, 74, 90, 0.05);
}

.header h1 {
    font-size: 23px;
    margin-bottom: 5px;
}

.header p {
    color: #78909A;
    font-size: 13px;
    line-height: 1.6;
}

.cards {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

.card {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 12px 14px;
    box-shadow: 0 3px 12px rgba(44, 74, 90, 0.05);
    min-width: 0;
}

.card-top {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.card-title {
    font-size: 13px;
    color: #78909A;
    font-weight: bold;
}

.card-icon {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 9px;
    background: #E8F7F6;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
}

.card-icon i {
    font-size: 20px;
}

.card-number {
    font-size: 28px;
    font-weight: bold;
    color: #2C4A5A;
}

.card-note {
    margin-top: 6px;
    font-size: 11px;
    color: #8AA1AA;
}

.panel {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 18px;
    margin-bottom: 18px;
    box-shadow: 0 3px 12px rgba(44, 74, 90, 0.05);
    min-width: 0;
}

.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

.panel-header h2,
.collapsible-heading h2 {
    font-size: 17px;
}

.panel-description {
    font-size: 12px;
    color: #8AA1AA;
    margin-top: 5px;
    line-height: 1.6;
}

.period-filter {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: wrap;
}

.period-button {
    border: 1px solid #DCEAEA;
    background: #F8FBFB;
    color: #2C4A5A;
    border-radius: 9px;
    padding: 8px 11px;
    text-decoration: none;
    font-family: inherit;
    font-size: 12px;
    cursor: pointer;
    transition: 0.2s;
}

.period-button:hover,
.period-button.active {
    background: #64C7C4;
    color: #FFFFFF;
    border-color: #64C7C4;
}

.custom-filter {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 15px;
}

.date-input {
    padding: 8px;
    border: 1px solid #DCEAEA;
    border-radius: 9px;
    font-family: inherit;
    color: #2C4A5A;
}

.filter-submit {
    padding: 9px 15px;
    border: none;
    border-radius: 9px;
    background: #64C7C4;
    color: #FFFFFF;
    font-family: inherit;
    cursor: pointer;
}

.date-range {
    margin-bottom: 15px;
    color: #78909A;
    font-size: 12px;
}

.validation-error {
    color: #C75353;
    font-size: 12px;
    margin-bottom: 12px;
}

.activity-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 18px;
}

.activity-box {
    background: #F8FBFB;
    border: 1px solid #EAF2F2;
    padding: 14px;
    border-radius: 12px;
    min-width: 0;
}

.activity-label {
    font-size: 12px;
    color: #78909A;
    margin-bottom: 8px;
}

.activity-value {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 5px;
}

.change {
    font-size: 12px;
    font-weight: bold;
    line-height: 1.6;
}

.change.positive {
    color: #239A70;
}

.change.negative {
    color: #D46A6A;
}

.change.neutral {
    color: #8AA1AA;
}

.collapsible-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 17px 0 13px;
    margin-top: 18px;
    border-top: 1px solid #E5EEEE;
}

.engagement-heading {
    border-top: none;
    margin-top: 0;
    padding-top: 0;
}

.collapse-toggle {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #DCEAEA;
    border-radius: 10px;
    background: #F1F8F8;
    color: #2C4A5A;
    cursor: pointer;
}

.collapse-toggle:hover {
    background: #E2F4F3;
}

.collapse-arrow {
    display: inline-block;
    width: 9px;
    height: 9px;
    border-right: 2px solid currentColor;
    border-bottom: 2px solid currentColor;
    transform: rotate(225deg);
    transition: transform 0.3s ease;
}

.collapse-toggle.is-closed .collapse-arrow {
    transform: rotate(45deg);
}

.collapsible-content {
    display: grid;
    grid-template-rows: minmax(0, 1fr);
    opacity: 1;
    visibility: visible;
    transition: grid-template-rows 0.35s ease, opacity 0.25s ease;
}

.collapsible-content.is-closed {
    grid-template-rows: minmax(0, 0fr);
    opacity: 0;
    visibility: hidden;
}

.collapsible-inner {
    min-height: 0;
    overflow: hidden;
}

.chart-container {
    width: 100%;
    overflow-x: auto;
    padding-top: 5px;
}

.chart-svg {
    width: 100%;
    min-width: 560px;
    height: 260px;
    display: block;
}

.chart-grid-line {
    stroke: #E7EFEF;
    stroke-width: 1;
}

.chart-label {
    fill: #8AA1AA;
    font-size: 12px;
}

.chart-line-users {
    fill: none;
    stroke: #64C7C4;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.chart-line-children {
    fill: none;
    stroke: #F0B878;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.chart-point-users {
    fill: #FFFFFF;
    stroke: #64C7C4;
    stroke-width: 3;
}

.chart-point-children {
    fill: #FFFFFF;
    stroke: #F0B878;
    stroke-width: 3;
}

.chart-legend {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    margin-top: 10px;
    font-size: 12px;
    color: #78909A;
}

.legend-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-left: 6px;
}

.chart-empty {
    padding: 25px 15px;
    text-align: center;
    color: #8AA1AA;
    font-size: 13px;
    background: #F8FBFB;
    border-radius: 11px;
}

.engagement-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-top: 5px;
}

.engagement-item {
    display: grid;
    grid-template-columns: 170px minmax(0, 1fr) 100px;
    align-items: center;
    gap: 14px;
    padding: 10px 0;
    border-bottom: 1px solid #F0F4F4;
}

.engagement-item:last-child {
    border-bottom: none;
}

.engagement-name {
    font-size: 13px;
    font-weight: bold;
}

.engagement-bar {
    height: 11px;
    border-radius: 20px;
    background: #EAF2F2;
    overflow: hidden;
}

.engagement-fill {
    height: 100%;
    background: #64C7C4;
    border-radius: 20px;
}

.engagement-value {
    text-align: left;
    font-size: 12px;
    font-weight: bold;
}

.engagement-value span {
    display: block;
    color: #8AA1AA;
    font-size: 11px;
    font-weight: normal;
    margin-top: 3px;
}

@media (max-width: 1100px) {
    .activity-grid {
        grid-template-columns: 1fr;
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
        padding: 18px;
    }

    .logo {
        margin-bottom: 18px;
    }

    .menu {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 7px;
    }

    .menu a {
        margin-bottom: 0;
        justify-content: center;
        padding: 10px 7px;
        font-size: 13px;
    }

    .main-content {
        padding: 14px;
    }

    .cards {
        grid-template-columns: 1fr;
    }

    .panel {
        padding: 15px;
    }

    .engagement-item {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .engagement-value {
        text-align: right;
    }
}

@media (max-width: 420px) {
    .menu {
        grid-template-columns: 1fr;
    }

    .main-content {
        padding: 11px;
    }

    .card {
        padding: 14px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .collapsible-content,
    .collapse-arrow {
        transition: none;
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

        <header class="header">
            <h1>الرئيسية</h1>
            <p>نظرة عامة على أداء تطبيق TinyGuide وتفاعل المستخدمين معه.</p>
        </header>

        <!-- الإحصائيات العامة: بطاقتان فقط -->

        <section class="cards">

            <div class="card">
                <div class="card-top">
                    <span class="card-title">إجمالي المستخدمين</span>
                    <span class="card-icon">👥</span>
                </div>

                <div class="card-number">
                    {{ number_format($usersCount) }}
                </div>

                <div class="card-note">
                    أولياء الأمور المسجلون في التطبيق
                </div>
            </div>

            <div class="card">
                <div class="card-top">
                    <span class="card-title">إجمالي الأطفال</span>
                    <span class="card-icon">👶</span>
                </div>

                <div class="card-number">
                    {{ number_format($childrenCount) }}
                </div>

                <div class="card-note">
                    الأطفال المسجلون في التطبيق
                </div>
            </div>

        </section>

        <!-- نشاط التطبيق -->

        <section class="panel">

            <div class="panel-header">

                <div>
                    <h2>📊 نشاط التطبيق</h2>
                    <p class="panel-description">
                        متابعة التسجيلات وتفاعل المستخدمين خلال الفترة المحددة.
                    </p>
                </div>

                <div class="period-filter">

                    <a
                        href="{{ url('/dashboard') }}?period=7days"
                        class="period-button {{ $period === '7days' ? 'active' : '' }}"
                    >
                        7 أيام
                    </a>

                    <a
                        href="{{ url('/dashboard') }}?period=30days"
                        class="period-button {{ $period === '30days' ? 'active' : '' }}"
                    >
                        30 يومًا
                    </a>

                    <a
                        href="{{ url('/dashboard') }}?period=12months"
                        class="period-button {{ $period === '12months' ? 'active' : '' }}"
                    >
                        12 شهرًا
                    </a>

                    <button
                        type="button"
                        class="period-button {{ $period === 'custom' ? 'active' : '' }}"
                        onclick="toggleCustomDates()"
                        aria-controls="customDatesForm"aria-expanded="{{ $period === 'custom' ? 'true' : 'false' }}"
                        id="customDatesToggle"
                    >
                        فترة مخصصة
                    </button>

                </div>
            </div>

            <!-- اختيار فترة مخصصة -->

            <form
                id="customDatesForm"
                class="custom-filter"
                method="GET"
                action="{{ url('/dashboard') }}"
                style="{{ $period === 'custom' ? '' : 'display:none;' }}"
            >
                <input type="hidden" name="period" value="custom">

                <label for="start_date">من</label>

                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    class="date-input"
                    value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                    max="{{ now()->format('Y-m-d') }}"
                    required
                >

                <label for="end_date">إلى</label>

                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    class="date-input"
                    value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                    max="{{ now()->format('Y-m-d') }}"
                    required
                >

                <button type="submit" class="filter-submit">
                    تطبيق
                </button>
            </form>

            @if($errors->any())
                <div class="validation-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="date-range">
                الفترة:
                {{ $startDate->format('Y-m-d') }}
                إلى
                {{ $endDate->format('Y-m-d') }}
            </div>

            <!-- إحصائيات نشاط التطبيق -->

            <div class="activity-grid">

                <!-- المستخدمون الجدد -->

                <div class="activity-box">
                    <div class="activity-label">المستخدمون الجدد</div>

                    <div class="activity-value">
                        {{ number_format($newUsersCount) }}
                    </div>

                    @if($usersGrowth === null)
                        <div class="change neutral">
                            لا توجد تسجيلات في الفترة السابقة للمقارنة
                        </div>
                    @elseif($usersGrowth > 0)
                        <div class="change positive">
                            ↑ {{ $usersGrowth }}% مقارنة بالفترة السابقة
                        </div>
                    @elseif($usersGrowth < 0)
                        <div class="change negative">
                            ↓ {{ abs($usersGrowth) }}% مقارنة بالفترة السابقة
                        </div>
                    @else
                        <div class="change neutral">
                            دون تغيير مقارنة بالفترة السابقة
                        </div>
                    @endif
                </div>

                <!-- الأطفال الجدد -->

                <div class="activity-box">
                    <div class="activity-label">الأطفال الجدد</div>

                    <div class="activity-value">
                        {{ number_format($newChildrenCount) }}
                    </div>

                    @if($childrenGrowth === null)
                        <div class="change neutral">
                            لا توجد إضافات في الفترة السابقة للمقارنة
                        </div>
                    @elseif($childrenGrowth > 0)
                        <div class="change positive">
                            ↑ {{ $childrenGrowth }}% مقارنة بالفترة السابقة
                        </div>
                    @elseif($childrenGrowth < 0)
                        <div class="change negative">
                            ↓ {{ abs($childrenGrowth) }}% مقارنة بالفترة السابقة
                        </div>
                        @else
                        <div class="change neutral">
                            دون تغيير مقارنة بالفترة السابقة
                        </div>
                    @endif
                </div>

                <!-- المستخدمون النشطون -->

                <div class="activity-box">
                    <div class="activity-label">المستخدمون النشطون</div>

                    <div class="activity-value">
                        {{ $activeUsersCount === null ? '—' : number_format($activeUsersCount) }}
                    </div>

                    <div class="change neutral">
                        {{ $activeUsersCount === null
                            ? 'البيانات غير متاحة حاليًا'
                            : 'خلال الفترة المحددة' }}
                    </div>
                </div>

            </div>

            <!-- نمو التسجيلات: قابل للفتح والإغلاق -->

            <div class="collapsible-heading">

                <div>
                    <h2>📈 نمو التسجيلات</h2>
                    <p class="panel-description">
                        عدد أولياء الأمور والأطفال المضافين خلال الفترة.
                    </p>
                </div>

                <button
                    type="button"
                    class="collapse-toggle"
                    aria-label="إظهار أو إخفاء الرسم البياني"
                    aria-expanded="true"
                    aria-controls="growthChartContent"
                    onclick="toggleSection('growthChartContent', this)"
                >
                    <span class="collapse-arrow"></span>
                </button>

            </div>

            <div
                id="growthChartContent"
                class="collapsible-content"
            >
                <div class="collapsible-inner">

                    @php
                        $chartWidth = 900;
                        $chartHeight = 290;

                        $paddingLeft = 55;
                        $paddingRight = 25;
                        $paddingTop = 25;
                        $paddingBottom = 55;

                        $usableWidth = $chartWidth - $paddingLeft - $paddingRight;
                        $usableHeight = $chartHeight - $paddingTop - $paddingBottom;

                        $chartItems = collect($chartData);

                        $values = $chartItems->flatMap(function ($item) {
                            return [
                                (int) $item['users'],
                                (int) $item['children']
                            ];
                        });

                        $maxValue = max(1, (int) $values->max());

                        $chartCount = $chartItems->count();

                        $usersPoints = [];
                        $childrenPoints = [];

                        foreach ($chartItems as $index => $item) {

                            $x = $chartCount > 1
                                ? $paddingLeft + (($usableWidth / ($chartCount - 1)) * $index)
                                : $paddingLeft + ($usableWidth / 2);

                            $userY = $paddingTop
                                + $usableHeight
                                - (((int) $item['users'] / $maxValue) * $usableHeight);

                            $childY = $paddingTop
                                + $usableHeight
                                - (((int) $item['children'] / $maxValue) * $usableHeight);

                            $usersPoints[] = [
                                'x' => $x,
                                'y' => $userY,
                                'value' => $item['users'],
                                'label' => $item['label']
                            ];

                            $childrenPoints[] = [
                                'x' => $x,
                                'y' => $childY,
                                'value' => $item['children'],
                                'label' => $item['label']
                            ];
                        }$usersPolyline = collect($usersPoints)
                            ->map(fn ($point) => $point['x'] . ',' . $point['y'])
                            ->implode(' ');

                        $childrenPolyline = collect($childrenPoints)
                            ->map(fn ($point) => $point['x'] . ',' . $point['y'])
                            ->implode(' ');

                        $hasRegistrationData = $values->contains(
                            fn ($value) => $value > 0
                        );

                        $labelStep = max(1, (int) ceil($chartCount / 8));
                    @endphp

                    @if($hasRegistrationData)

                        <div class="chart-container">

                            <svg
                                viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}"
                                class="chart-svg"
                                role="img"
                                aria-label="رسم بياني لنمو تسجيل المستخدمين والأطفال"
                            >

                                <!-- خطوط الخلفية -->

                                @for($i = 0; $i <= 4; $i++)

                                    @php
                                        $gridY = $paddingTop + (($usableHeight / 4) * $i);

                                        $gridValue = round(
                                            $maxValue - (($maxValue / 4) * $i)
                                        );
                                    @endphp

                                    <line
                                        x1="{{ $paddingLeft }}"
                                        y1="{{ $gridY }}"
                                        x2="{{ $chartWidth - $paddingRight }}"
                                        y2="{{ $gridY }}"
                                        class="chart-grid-line"
                                    />

                                    <text
                                        x="8"
                                        y="{{ $gridY + 5 }}"
                                        class="chart-label"
                                    >
                                        {{ $gridValue }}
                                    </text>

                                @endfor

                                <!-- خط المستخدمين -->

                                <polyline
                                    points="{{ $usersPolyline }}"
                                    class="chart-line-users"
                                />

                                <!-- خط الأطفال -->

                                <polyline
                                    points="{{ $childrenPolyline }}"
                                    class="chart-line-children"
                                />

                                <!-- نقاط المستخدمين -->

                                @foreach($usersPoints as $point)
                                    <circle
                                        cx="{{ $point['x'] }}"
                                        cy="{{ $point['y'] }}"
                                        r="4"
                                        class="chart-point-users"
                                    >
                                        <title>{{ $point['label'] }}: {{ $point['value'] }} مستخدم</title>
                                    </circle>
                                @endforeach

                                <!-- نقاط الأطفال -->

                                @foreach($childrenPoints as $point)
                                    <circle
                                        cx="{{ $point['x'] }}"
                                        cy="{{ $point['y'] }}"
                                        r="4"
                                        class="chart-point-children"
                                    >
                                        <title>{{ $point['label'] }}: {{ $point['value'] }} طفل</title></circle>
                                @endforeach

                                <!-- تسميات التواريخ -->

                                @foreach($usersPoints as $index => $point)

                                    @if($chartCount <= 12 || $index % $labelStep === 0)

                                        <text
                                            x="{{ $point['x'] }}"
                                            y="{{ $chartHeight - 14 }}"
                                            text-anchor="middle"
                                            class="chart-label"
                                        >
                                            {{ $point['label'] }}
                                        </text>

                                    @endif

                                @endforeach

                            </svg>

                        </div>

                        <div class="chart-legend">

                            <div>
                                <span
                                    class="legend-dot"
                                    style="background:#64C7C4;"
                                ></span>
                                مستخدمون جدد
                            </div>

                            <div>
                                <span
                                    class="legend-dot"
                                    style="background:#F0B878;"
                                ></span>
                                أطفال جدد
                            </div>

                        </div>

                    @else

                        <div class="chart-empty">
                            لا توجد تسجيلات جديدة خلال الفترة المحددة.
                        </div>

                    @endif

                </div>
            </div>

        </section>

        <!-- التفاعل مع وظائف التطبيق: قابل للفتح والإغلاق -->

        <section class="panel">

            <div class="collapsible-heading engagement-heading">

                <div>
                    <h2>⭐️ التفاعل مع وظائف التطبيق</h2>

                    <p class="panel-description">
                        نسبة أولياء الأمور الذين سجّلوا بيانات فعلية
                        في كل قسم خلال الفترة المحددة.
                    </p>
                </div>

                <button
                    type="button"
                    class="collapse-toggle"
                    aria-label="إظهار أو إخفاء تفاصيل التفاعل"
                    aria-expanded="true"
                    aria-controls="engagementContent"
                    onclick="toggleSection('engagementContent', this)"
                >
                    <span class="collapse-arrow"></span>
                </button>

            </div>

            <div
                id="engagementContent"
                class="collapsible-content"
            >
                <div class="collapsible-inner">

                    <div class="engagement-list">

                        @foreach($featureEngagement as $feature)

                            <div class="engagement-item">

                                <div class="engagement-name">
                                    {{ $feature['icon'] }}
                                    {{ $feature['name'] }}
                                </div>

                                <div class="engagement-bar">
                                    <div
                                        class="engagement-fill"
                                        style="width: {{ max(0, min(100, (float) ($feature['percentage'] ?? 0))) }}%;"
                                    ></div>
                                </div>

                                <div class="engagement-value">

                                    @if($feature['percentage'] === null)

                                        —
                                        <span>غير متاح حاليًا</span>

                                    @else
                                    {{ $feature['percentage'] }}%
                                        <span>
                                            {{ $feature['users'] }} مستخدم
                                        </span>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>
            </div>

        </section>

    </main>

</div>

<script>
    // إظهار وإخفاء الفترة المخصصة

    function toggleCustomDates() {
        const form = document.getElementById('customDatesForm');
        const button = document.getElementById('customDatesToggle');

        if (!form || !button) {
            return;
        }

        const isHidden = window.getComputedStyle(form).display === 'none';

        form.style.display = isHidden ? 'flex' : 'none';

        button.setAttribute(
            'aria-expanded',
            isHidden ? 'true' : 'false'
        );
    }

    // فتح وإغلاق الرسم البياني وقسم التفاعل بشكل مستقل

    function toggleSection(sectionId, button) {
        const section = document.getElementById(sectionId);

        if (!section) {
            return;
        }

        const isClosed = section.classList.toggle('is-closed');

        button.classList.toggle('is-closed', isClosed);

        button.setAttribute(
            'aria-expanded',
            isClosed ? 'false' : 'true'
        );
    }
</script>

</body>
</html>