<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>التقارير | TinyGuide</title>



    <style>

       * {

    box-sizing: border-box;

}



html, body {

    margin: 0;

    padding: 0;

}



.sidebar .logo h2,

.sidebar .logo p {

    margin-top: 0;

}



.sidebar .menu a {

    margin-top: 0;

}

        body {

            margin: 0;

            font-family: Tahoma, Arial, sans-serif;

            background: #F1F8F8;

            color: #2C4A5A;

        }



        .dashboard {

            display: flex;

            min-height: 100vh;

        }



        .sidebar {

            width: 250px;

            flex-shrink: 0;

            background: white;

            min-height: 100vh;

        }







        .menu a {

            display: block;

            padding: 13px 20px;

            text-decoration: none;

            color: #2C4A5A;

            transition: 0.2s;

        }



        .menu a:hover,

        .menu a.active {

            background: #64C7C4;

            color: white;

        }



        .main {

            flex: 1;

            min-width: 0;

            padding: 30px;

        }



        .page-header {

            margin-bottom: 24px;

        }



        .page-header h1 {

            margin: 0 0 8px;

            font-size: 27px;

        }



        .subtitle {

            color: #718A95;

            font-size: 14px;

        }



        .card {

            background: white;

            padding: 24px;

            border: 1.5px solid #83D2CF;

            border-radius: 16px;

            margin-bottom: 20px;

            box-shadow: 0 4px 18px rgba(44, 74, 90, 0.06);

        }



        .card h2 {

            margin: 0 0 18px;

            font-size: 19px;

        }



        /* ==============================

           اختيار الأقسام

        ============================== */



        .dropdown {

            position: relative;

            width: 100%;

            max-width: 350px;

        }



        .dropdown-button {

            width: 100%;

            padding: 13px 15px;

            border: 1px solid #D8E6E6;

            border-radius: 10px;

            background: #F8FBFB;

            color: #2C4A5A;

            font-family: inherit;

            font-size: 14px;

            cursor: pointer;

            display: flex;

            justify-content: space-between;

            align-items: center;

        }



        .dropdown-menu {

            display: none;

            position: absolute;

            top: calc(100% + 5px);

            right: 0;

            width: 100%;

            background: white;

            border: 1px solid #D8E6E6;

            border-radius: 10px;

            box-shadow: 0 8px 24px rgba(44, 74, 90, 0.12);

            padding: 10px;

            z-index: 20;

            max-height: 320px;

            overflow-y: auto;

        }



        .dropdown.open .dropdown-menu {

            display: block;

        }



        .dropdown-option {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 10px;

            cursor: pointer;

            border-radius: 7px;

            font-size: 14px;

        }



        .dropdown-option:hover {

            background: #F1F8F8;

        }



        input[type="checkbox"] {

            accent-color: #64C7C4;

            width: 16px;

            height: 16px;

        }



        /* ==============================

           الفلاتر

        ============================== */



        .filters {

            display: flex;

            gap: 15px;

            flex-wrap: wrap;

            align-items: flex-end;

            margin-top: 20px;

        }



        .field {

            display: flex;

            flex-direction: column;

            gap: 8px;

            min-width: 175px;

        }



        .field label {

            font-size: 13px;

            font-weight: bold;

        }



        .field input,

        .field select {

            padding: 12px;

            border: 1px solid #D8E6E6;

            border-radius: 9px;

            color: #2C4A5A;

            font-family: inherit;

            background: white;

        }



        .btn {

            border: none;

            border-radius: 9px;

            padding: 12px 22px;

            cursor: pointer;

            font-family: inherit;

            font-size: 14px;

            font-weight: bold;

        }



        .btn-primary {

            background: #64C7C4;

            color: #173B46;

        }



        .btn-secondary {

            background: #2C4A5A;

            color: white;

        }



        /* ==============================

           معاينة التقرير

        ============================== */



        .report-heading {

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 15px;

        }



        .report-heading h2 {

            margin: 0 0 8px;

        }



        .report-period {

            font-size: 13px;

            color: #718A95;

        }



        /* ==============================

           أقسام التقرير

        ============================== */



        .report-section {

            padding: 0;

            overflow: hidden;

        }



        .section-toggle {

            width: 100%;

            border: none;

            background: white;

            padding: 22px 24px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            text-align: right;

            cursor: pointer;

            color: #2C4A5A;

            font-family: inherit;

        }



        .section-toggle:hover {

            background: #F8FBFB;

        }



        .section-title {

            font-size: 18px;

            font-weight: bold;

        }



        .section-mini-stats {

            display: flex;

            align-items: center;

            gap: 20px;

            flex-wrap: wrap;

            font-size: 13px;

            color: #718A95;

        }



        .section-mini-stats strong {

            color: #2C4A5A;

        }



        .arrow {

            font-size: 18px;

            transition: transform 0.2s;

        }



        .report-section.open .arrow {

            transform: rotate(180deg);

        }



        .section-content {

            display: none;

            padding: 0 24px 24px;

            border-top: 1px solid #EDF3F3;

        }



        .report-section.open .section-content {

            display: block;

        }



        /* ==============================

           بطاقات الإحصائيات

        ============================== */



        .stats-grid {

            display: grid;

            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));

            gap: 15px;

            margin-top: 20px;

        }



        .stat-box {

            padding: 18px;

            border-radius: 12px;

            background: #F1F8F8;

            border: 1px solid #E0EEEE;

        }



        .stat-label {

            color: #718A95;

            font-size: 13px;

            margin-bottom: 12px;

        }



        .stat-value {

            font-size: 25px;

            font-weight: bold;

        }



        .positive {

            color: #18866D;

        }



        .negative {

            color: #D65B5B;

        }



        .neutral {

            color: #718A95;

        }



        /* ==============================

           الرسومات البيانية

        ============================== */



        .chart-container {

            position: relative;

            width: 100%;

            height: 280px;

            margin-top: 25px;

            overflow: hidden;

        }



        .chart-container canvas {

            display: block;

            width: 100% !important;

            height: 100% !important;

        }



        /*

         * مهم جدًا:

         * صورة الرسم المخصصة للطباعة

         * تكون مخفية تمامًا أثناء عرض الصفحة.

         *

         * هذا يمنع ظهور رسم مكرر

         * فوق بطاقات التفاصيل.

         */



        .print-chart-image {

            display: none !important;

        }



        /* ==============================

        تفاصيل التقرير

        ============================== */



        .details-grid {

            display: grid;

            grid-template-columns: repeat(

                auto-fit,

                minmax(200px, 1fr)

            );

            gap: 12px;

            margin-top: 20px;

        }



        .detail-item {

            background: #F8FBFB;

            padding: 15px;

            border-radius: 10px;

            border: 1px solid #E4EEEE;

        }



        .detail-item span {

            display: block;

            font-size: 12px;

            color: #718A95;

            margin-bottom: 9px;

        }



        .detail-item strong {

            font-size: 22px;

        }



        .empty-state {

            padding: 25px;

            text-align: center;

            color: #718A95;

        }



        /* ==============================

           الطباعة PDF

        ============================== */



        @media print {



            @page {

                size: A4;

                margin: 12mm;

            }



            html,

            body {

                width: 100%;

                margin: 0;

                padding: 0;

                background: white !important;



                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;

            }



            .dashboard {

                display: block !important;

                min-height: 0 !important;

            }



            .sidebar,

            .page-header,

            .filters-card,

            .print-hide,

            .arrow {

                display: none !important;

            }



            .main {

                display: block !important;

                width: 100% !important;

                padding: 0 !important;

                margin: 0 !important;

            }



            .card {

                box-shadow: none !important;

                border: 1px solid #DCE8E8;

                border-radius: 10px;

                margin-bottom: 12px;

                padding: 15px;



                break-inside: avoid;

                page-break-inside: avoid;

            }



            .report-section {

                overflow: visible !important;

                height: auto !important;

                break-inside: avoid;

                page-break-inside: avoid;

            }



            .report-section .section-content {

                display: block !important;

                height: auto !important;

                overflow: visible !important;

                padding: 0 15px 15px !important;

            }



            .section-toggle {

                padding: 15px !important;

                cursor: default;

            }



            .section-title {

                font-size: 16px;

            }



            .section-mini-stats {

                font-size: 11px;

            }



            .stats-grid {

                display: grid !important;

                grid-template-columns: repeat(3, minmax(0, 1fr));

                gap: 10px;

                margin-top: 12px;

            }



            .stat-box {

                padding: 12px;

                break-inside: avoid;

            }



            .stat-label {

                font-size: 11px;

            }



            .stat-value {

                font-size: 21px;

            }



            /*

             * أهم إصلاح:

             *

             * أثناء الطباعة نخفي Canvas

             * ونعرض صورة الرسم فقط.

             *

             * صورة واحدة داخل مساحة ثابتة،

             * بدون تكرار أو تداخل.

             */



            .chart-container {

                display: block !important;



                position: relative !important;



                width: 100% !important;



                height: 195px !important;



                min-height: 195px !important;

                max-height: 195px !important;



                margin: 15px 0 20px !important;



                padding: 0 !important;



                overflow: hidden !important;



                clear: both !important;

            }



            .chart-container canvas {display: none !important;

            }



            .chart-container .print-chart-image {

                display: block !important;



                position: static !important;



                width: 100% !important;



                height: 190px !important;



                max-height: 190px !important;



                object-fit: contain !important;



                margin: 0 auto !important;



                padding: 0 !important;

            }



            .details-grid {

                display: grid !important;

                grid-template-columns: repeat(2, minmax(0, 1fr));



                gap: 10px !important;



                margin-top: 15px !important;



                clear: both !important;



                position: relative !important;

            }



            .detail-item {

                padding: 12px;

                break-inside: avoid;

            }



            .detail-item span {

                font-size: 11px;

            }



            .detail-item strong {

                font-size: 19px;

            }



            .empty-state {

                padding: 15px !important;

                font-size: 13px;

            }



        }



        /* ==============================

           الشاشات الصغيرة

        ============================== */



        @media (max-width: 900px) {



            .dashboard {

                flex-direction: column;

            }



            .sidebar {

                width: 100%;

                min-height: auto;

            }



            .main {

                padding: 18px;

            }



            .section-toggle {

                align-items: flex-start;

                flex-direction: column;

            }



        }



    </style>

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

</head>



<body>



<div class="dashboard">



    {{-- القائمة الجانبية الأصلية --}}

    @include('partials.admin-sidebar')



    <main class="main-content main">



        <!-- ==========================

             عنوان الصفحة

        =========================== -->



        <div class="page-header">



            <h1>التقارير الشاملة</h1>



            <div class="subtitle">

                إحصائيات استخدام التطبيق خلال الفترة المحددة

            </div>



        </div>



        <!-- ==========================

             إعداد التقرير

        =========================== -->



        <div class="card filters-card">



            <h2>إعداد التقرير</h2>



            <form

                method="GET"

                action="{{ url('/reports') }}"

                id="reportForm"

            >



                <div class="field">



                    <label>الأقسام المطلوبة</label>



                    <div

                        class="dropdown"

                        id="sectionsDropdown"

                    >



                        <button

                            type="button"

                            class="dropdown-button"

                            id="dropdownButton"

                            aria-expanded="false"

                        >



                            <span id="selectedSectionsText">

                                جميع الأقسام

                            </span>



                            <span>▼</span>



                        </button>



                        <div

                            class="dropdown-menu"

                            id="dropdownMenu"

                        >



                            <label class="dropdown-option">



                                <input

                                    type="checkbox"

                                    id="selectAll"

                                >



                                <strong>

                                    تحديد جميع الأقسام

                                </strong>



                            </label>



                            <hr

                                style="

                                    border: 0;

                                    border-top: 1px solid #EDF3F3;

                                "

                            >@foreach($availableSections as $key => $title)



                                <label class="dropdown-option">



                                    <input

                                        type="checkbox"

                                        name="sections[]"

                                        value="{{ $key }}"

                                        class="section-checkbox"

                                        {{ in_array($key, $selectedSections) ? 'checked' : '' }}

                                    >



                                    {{ $title }}



                                </label>



                            @endforeach



                        </div>



                    </div>



                </div>



                <!-- الفترة الزمنية -->



                <div class="filters">



                    <div class="field">



                        <label for="period">

                            الفترة الزمنية

                        </label>



                        <select name="period" id="period">



                            <option

                                value="today"

                                {{ $period == 'today' ? 'selected' : '' }}

                            >

                                اليوم

                            </option>



                            <option

                                value="7days"

                                {{ $period == '7days' ? 'selected' : '' }}

                            >

                                آخر 7 أيام

                            </option>



                            <option

                                value="30days"

                                {{ $period == '30days' ? 'selected' : '' }}

                            >

                                آخر 30 يومًا

                            </option>



                            <option

                                value="3months"

                                {{ $period == '3months' ? 'selected' : '' }}

                            >

                                آخر 3 أشهر

                            </option>



                            <option

                                value="6months"

                                {{ $period == '6months' ? 'selected' : '' }}

                            >

                                آخر 6 أشهر

                            </option>



                            <option

                                value="year"

                                {{ $period == 'year' ? 'selected' : '' }}

                            >

                                آخر سنة

                            </option>



                            <option

                                value="custom"

                                {{ $period == 'custom' ? 'selected' : '' }}

                            >

                                فترة مخصصة

                            </option>



                        </select>



                    </div>



                    <div class="field custom-date">



                        <label for="from">

                            من تاريخ

                        </label>



                        <input

                            type="date"

                            name="from"

                            id="from"

                            value="{{ $from }}"

                        >



                    </div>



                    <div class="field custom-date">



                        <label for="to">

                            إلى تاريخ

                        </label>



                        <input

                            type="date"

                            name="to"

                            id="to"

                            value="{{ $to }}"

                        >



                    </div>



                    <button

                        type="submit"

                        class="btn btn-primary"

                    >

                        إنشاء التقرير

                    </button>



                </div>

                </form>



        </div>



        <!-- ==========================

             معاينة التقرير

        =========================== -->



        <div class="card">



            <div class="report-heading">



                <div>



                    <h2>معاينة التقرير</h2>



                    <div class="report-period">



                        من {{ $report['period']['start'] }}



                        إلى {{ $report['period']['end'] }}



                    </div>



                </div>



                <button

                    type="button"

                    class="btn btn-secondary print-hide"

                    onclick="printReport()"

                >

                    تصدير PDF

                </button>



            </div>



        </div>



        <!-- ==========================

             أقسام التقرير

        =========================== -->



        @forelse(collect($report['sections'])->only($selectedSections) as $key => $section)



            <div

                class="card report-section"

                id="section-{{ $key }}"

            >



                <button

                    type="button"

                    class="section-toggle"

                    onclick="toggleSection('{{ $key }}')"

                    aria-expanded="false"

                >



                    <span class="section-title">



                        {{ $section['title'] }}



                    </span>



                    <span class="section-mini-stats">



                        <span>



                            السجلات الجديدة:



                            <strong>

                                {{ number_format($section['current']) }}

                            </strong>



                        </span>



                        <span>



                            نسبة التغيير:



                            <strong>



                                @if($section['change'] === null)



                                    لا يوجد



                                @elseif($section['change'] > 0)



                                    +{{ $section['change'] }}%



                                @else



                                    {{ $section['change'] }}%



                                @endif



                            </strong>



                        </span>



                    </span>



                    <span class="arrow">▼</span>



                </button>



                <div class="section-content">



                    <!-- بطاقات الإحصائيات -->



                    <div class="stats-grid">



                        <div class="stat-box">



                            <div class="stat-label">

                                السجلات الجديدة خلال الفترة

                            </div>



                            <div class="stat-value">

                                {{ number_format($section['current']) }}

                            </div>



                        </div>



                        <div class="stat-box">



                            <div class="stat-label">

                                الفترة السابقة

                            </div>



                            <div class="stat-value">

                                {{ number_format($section['previous']) }}

                            </div>



                        </div>



                        <div class="stat-box">



                            <div class="stat-label">

                                نسبة التغيير

                            </div>



                            <div class="stat-value

                                {{ $section['change'] === null

                                    ? 'neutral'

                                    : ($section['change'] > 0

                                        ? 'positive'

                                        : ($section['change'] < 0

                                            ? 'negative'

                                            : 'neutral')) }}

                            ">



                                @if($section['change'] === null)



                                    لا يوجد

@elseif($section['change'] > 0)



                                    +{{ $section['change'] }}%



                                @else



                                    {{ $section['change'] }}%



                                @endif



                            </div>



                        </div>



                    </div>



                    <!-- الرسم البياني أو رسالة عدم وجود بيانات -->



                    @if($section['current'] > 0)



                        <div class="chart-container">



                            <canvas

                                id="chart-{{ $key }}"

                            ></canvas>



                        </div>



                    @else



                        <div class="empty-state">

                            لا توجد سجلات خلال الفترة المحددة

                        </div>



                    @endif



                    <!-- التفاصيل -->



                    @if(count($section['details']) > 0)



                        <div class="details-grid">



                            @foreach($section['details'] as $label => $value)



                                <div class="detail-item">



                                    <span>

                                        {{ $label }}

                                    </span>



                                    <strong>

                                        {{ number_format($value) }}

                                    </strong>



                                </div>



                            @endforeach



                        </div>



                    @endif



                </div>



            </div>



        @empty



            <div class="card empty-state">

                لا توجد أقسام محددة لعرض التقرير.

            </div>



        @endforelse



    </main>



</div>



<!-- ==============================

     JavaScript

\============================== -->



<script>



    /*

    |--------------------------------------------------------------------------

    | اختيار الأقسام

    |--------------------------------------------------------------------------

    */



    const dropdown = document.getElementById('sectionsDropdown');



    const dropdownButton = document.getElementById('dropdownButton');



    const selectedSectionsText =

        document.getElementById('selectedSectionsText');



    const selectAll = document.getElementById('selectAll');



    const sectionCheckboxes = [

        ...document.querySelectorAll('.section-checkbox')

    ];



    dropdownButton.addEventListener('click', function () {



        dropdown.classList.toggle('open');



        dropdownButton.setAttribute(

            'aria-expanded',

            dropdown.classList.contains('open') ? 'true' : 'false'

        );



    });



    document.addEventListener('click', function (event) {



        if (!dropdown.contains(event.target)) {



            dropdown.classList.remove('open');



            dropdownButton.setAttribute(

                'aria-expanded',

                'false'

            );



        }



    });



    function updateSelectionText() {



        const selected = sectionCheckboxes.filter(

            checkbox => checkbox.checked

        );



        selectAll.checked =

            selected.length === sectionCheckboxes.length;



        if (selected.length === sectionCheckboxes.length) {



            selectedSectionsText.textContent =

                'جميع الأقسام';



        } else if (selected.length === 0) {



            selectedSectionsText.textContent =

                'اختر الأقسام';



        } else if (selected.length === 1) {



            selectedSectionsText.textContent =

                selected[0].closest('label').textContent.trim();



        } else {



            selectedSectionsText.textContent =

                selected.length + ' أقسام محددة';



        }



    }



    selectAll.addEventListener('change', function () {



        sectionCheckboxes.forEach(checkbox => {



            checkbox.checked = selectAll.checked;



        });



        updateSelectionText();



    });



    sectionCheckboxes.forEach(checkbox => {



        checkbox.addEventListener(

            'change',

            updateSelectionText

        );



    });



    updateSelectionText();



    document.getElementById('reportForm').addEventListener(

        'submit',

        function (event) {



            const hasSelection = sectionCheckboxes.some(

                checkbox => checkbox.checked

            );



            if (!hasSelection) {



                event.preventDefault();



                alert(

                    'يرجى اختيار قسم واحد على الأقل.'

                );



            }



        }

    );



    /*

    |--------------------------------------------------------------------------

    | الفترة المخصصة

    |--------------------------------------------------------------------------

    */



    const periodSelect =

        document.getElementById('period');



    const customDates =

        document.querySelectorAll('.custom-date');



    function toggleCustomDates() {



        const isCustom =

            periodSelect.value === 'custom';



        customDates.forEach(element => {



            element.style.display =

                isCustom ? 'flex' : 'none';



        });



        document.getElementById('from').required =

            isCustom;



        document.getElementById('to').required =

            isCustom;



    }



    periodSelect.addEventListener(

        'change',

        toggleCustomDates

    );



    toggleCustomDates();



    /*

    |--------------------------------------------------------------------------

    | فتح وإغلاق الأقسام

    |--------------------------------------------------------------------------

    */



    function toggleSection(key) {



        const section =

            document.getElementById('section-' + key);



        if (!section) {

            return;

        }



        section.classList.toggle('open');



        const button =

            section.querySelector('.section-toggle');



        button.setAttribute(

            'aria-expanded',

            section.classList.contains('open')

                ? 'true'

                : 'false'

        );



        if (

            section.classList.contains('open') &&

            charts[key]

        ) {



            setTimeout(() => {



                charts[key].resize();



            }, 100);



        }



    }



    /*

    |--------------------------------------------------------------------------

    | بيانات الرسومات

    |--------------------------------------------------------------------------

    */



    const reportSections = @json($report['sections']);



    const charts = {};



</script>



<!-- مكتبة Chart.js المحلية -->



<script src="{{ asset('js/chart.umd.js') }}"></script>



<script>



    /*

    |--------------------------------------------------------------------------

    | إنشاء الرسومات البيانية

    |--------------------------------------------------------------------------

    */



    if (typeof Chart !== 'undefined') {



        Object.entries(reportSections).forEach(

            ([key, section]) => {



                const canvas =

                    document.getElementById('chart-' + key);



                if (!canvas) {

                    return;

                }



                charts[key] = new Chart(canvas, {



                    type: 'line',



                    data: {



                        labels: section.chart.labels,



                        datasets: [{



                            label: 'السجلات الجديدة',



                            data: section.chart.values,



                            borderColor: '#64C7C4',



                            backgroundColor:

                                'rgba(100, 199, 196, 0.15)',



                            fill: true,



                            tension: 0.3,



                            pointRadius: 2,



                            pointHoverRadius: 4,



                            borderWidth: 2



                        }]



                    },



                    options: {



                        responsive: true,



                        maintainAspectRatio: false,animation: false,



                        plugins: {



                            legend: {

                                display: true

                            }



                        },



                        scales: {



                            x: {



                                ticks: {



                                    autoSkip: true,



                                    maxTicksLimit: 7,



                                    maxRotation: 0,



                                    minRotation: 0,



                                    font: {

                                        size: 10

                                    }



                                },



                                grid: {

                                    display: false

                                }



                            },



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

        );



    }



    /*

    |--------------------------------------------------------------------------

    | تجهيز الطباعة

    |--------------------------------------------------------------------------

    */



    let printingInProgress = false;



    let originalOpenedSections = [];



    function waitForFrame() {



        return new Promise(resolve => {



            requestAnimationFrame(() => {



                requestAnimationFrame(resolve);



            });



        });



    }



    function waitForImage(image) {



        return new Promise(resolve => {



            if (image.complete) {



                resolve();

                return;



            }



            image.onload = resolve;

            image.onerror = resolve;



        });



    }



    /*

    |--------------------------------------------------------------------------

    | تنظيف صور الطباعة

    |--------------------------------------------------------------------------

    */



    function cleanupPrint() {



        document.querySelectorAll(

            '.print-chart-image'

        ).forEach(image => {



            image.remove();



        });



        document.querySelectorAll(

            '.report-section'

        ).forEach(section => {



            section.classList.remove('open');



        });



        originalOpenedSections.forEach(id => {



            const section =

                document.getElementById(id);



            if (section) {



                section.classList.add('open');



            }



        });



        document.querySelectorAll(

            '.report-section'

        ).forEach(section => {



            const button =

                section.querySelector('.section-toggle');



            if (button) {



                button.setAttribute(

                    'aria-expanded',

                    section.classList.contains('open')

                        ? 'true'

                        : 'false'

                );



            }



        });



        Object.values(charts).forEach(chart => {



            chart.resize();



        });



        printingInProgress = false;



    }



    /*

    |--------------------------------------------------------------------------

    | تصدير PDF بدون تكرار الرسومات

    |--------------------------------------------------------------------------

    */



    async function printReport() {



        if (printingInProgress) {

            return;

        }



        printingInProgress = true;



        try {



            /*

             * حفظ الأقسام المفتوحة

             * قبل الطباعة.

             */



            originalOpenedSections = [

                ...document.querySelectorAll(

                    '.report-section.open'

                )

            ].map(section => section.id);



            /*

             * إزالة أي صور قديمة.

             */



            document.querySelectorAll(

                '.print-chart-image'

            ).forEach(image => {



                image.remove();



            });



            /*

             * فتح الأقسام مؤقتًا

             * حتى تكون Canvas مرئية

             * ويمكن تحويلها إلى صورة.

             */



            document.querySelectorAll(

                '.report-section'

            ).forEach(section => {



                section.classList.add('open');



            });



            /*

             * انتظار المتصفح حتى يحسب

             * أبعاد العناصر بشكل صحيح.

             */



            await waitForFrame();



            /*

             * إنشاء صورة واحدة فقط

             * لكل رسم بياني.

             */



            const images = [];



            for (

                const [key, chart]

                of Object.entries(charts)

            ) {



                const canvas =

                    document.getElementById(

                        'chart-' + key

                    );



                if (!canvas) {

                    continue;

                }



                const container =

                    canvas.closest('.chart-container');



                if (!container) {

                    continue;

                }



                /*

                 * التأكد من عدم وجود

                 * صورة مكررة داخل القسم.

                 */



                container.querySelectorAll(

                    '.print-chart-image'

                ).forEach(image => {



                    image.remove();



                });



                /*

                 * تحديث الرسم بعد

                 * فتح القسم.

                 */



                chart.resize(700, 250);



                chart.update('none');



                /*

                 * تحويل الرسم إلى PNG.

                 */



                const image =

                    document.createElement('img');



                image.className =

                    'print-chart-image';



                image.alt =

                    'الرسم البياني - ' +

                    (

                        reportSections[key]?.title ||

                        key

                    );



                image.src =

                    chart.toBase64Image('image/png');



                /*

                 * وضع الصورة داخل

                 * حاوية الرسم نفسها.

                 */



                container.appendChild(image);



                images.push(image);



            }



            /*

             * انتظار تحميل الصور

             * قبل فتح نافذة الطباعة.

             */



            await Promise.all(

                images.map(waitForImage)

            );



            await waitForFrame();



            /*

             * فتح نافذة الطباعة.

             */



            window.print();



        } catch (error) {



            console.error(

                'خطأ أثناء تجهيز التقرير للطباعة:',

                error

            );



            alert(

                'حدث خطأ أثناء تجهيز التقرير للطباعة.'

            );



            cleanupPrint();



        }



    }



    /*

    |--------------------------------------------------------------------------

    | إعادة الصفحة لطبيعتها بعد الطباعة

    |--------------------------------------------------------------------------

    */



    window.addEventListener(

        'afterprint',

        function () {



            cleanupPrint();



        }

    );



</script>



</body>

</html>