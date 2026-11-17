<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>الفحوصات الطبية | TinyGuide</title>

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

        /* الشريط الجانبي */

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

        /* المحتوى */

        .main-content {
            flex: 1;
            min-width: 0;
            padding: 30px;
        }

        .header {
            background: #FFFFFF;
            padding: 24px 28px;
            border: 1px solid #9ADFDB;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.05);
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
            padding: 11px 17px;
            border-radius: 12px;
            background: #EAF7F7;
            color: #2C4A5A;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap;
            transition: 0.2s;
        }

        .back-to-records:hover {
            background: #64C7C4;
            color: #FFFFFF;
        }

        /* القسم الرئيسي للفحوصات */

        .exams-overview {
            background: linear-gradient(135deg, #FFFFFF, #EAF8F7);
            border: 1px solid #9ADFDB;
            border-radius: 22px;
            padding: 35px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(44, 74, 90, 0.05);
        }

        .overview-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
        }

        .overview-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .overview-icon {
            width: 55px;
            height: 55px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #DDF4F2;
            border-radius: 16px;
            font-size: 27px;
        }

        .overview-title h2 {
            font-size: 20px;
            margin-bottom: 6px;
        }

        .overview-title p {
            font-size: 13px;
            color: #78909A;
        }

        /* فلتر الفترة الزمنية */

        .period-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .period-form label {
            font-size: 13px;
            color: #78909A;
            font-weight: bold;
            white-space: nowrap;
        }

        .period-select {
            min-width: 175px;
            padding: 12px 15px;
            border: 1px solid #CFE7E5;
            border-radius: 12px;
            background: #FFFFFF;
            color: #2C4A5A;
            font-family: inherit;
            font-size: 14px;
            cursor: pointer;
            outline: none;
        }

        .period-select:focus {
            border-color: #64C7C4;
            box-shadow: 0 0 0 3px rgba(100, 199, 196, 0.15);
        }

        /* العدد الإجمالي */

        .exams-total {
            display: flex;
            align-items: center;
            gap: 25px;
            padding: 20px 0;
        }

        .total-number {
            font-size: 76px;
            line-height: 1;
            font-weight: bold;
            color: #2C4A5A;
        }

        .total-link {
            display: flex;
            align-items: center;
            gap: 25px;
            color: inherit;
            text-decoration: none;
            border-radius: 12px;
            transition: 0.2s;
        }

        .total-link:hover .total-number {
            color: #48AAA6;
        }

        .total-description {
            border-right: 3px solid #64C7C4;
            padding-right: 18px;
        }

        .total-description strong {
            display: block;
            font-size: 17px;
            margin-bottom: 8px;
        }

        .total-description span {
            color: #78909A;
            font-size: 14px;
            line-height: 1.8;
        }

        /* قسم المرفقات */

        .attachments-section {
            background: #FFFFFF;
            border-radius: 22px;
            padding: 30px;
            border: 1px solid #9ADFDB;
            box-shadow: 0 5px 20px rgba(44, 74, 90, 0.05);
            margin-bottom: 25px;
        }

        .attachments-header {
            margin-bottom: 30px;
        }

        .attachments-header h2 {
            font-size: 20px;
            margin-bottom: 9px;
        }

        .attachments-header p {
            color: #78909A;
            font-size: 14px;
            line-height: 1.9;
        }

        .attachment-progress {
            width: 100%;
            height: 24px;
            display: flex;
            overflow: hidden;
            border-radius: 20px;
            background: #E5EEEE;
            margin-bottom: 30px;
        }

        .progress-with {
            height: 100%;
            background: #64C7C4;
            transition: width 0.4s ease;
        }

        .progress-without {
            height: 100%;
            background: #DCEAEA;
            transition: width 0.4s ease;
        }

        .attachment-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        /* بطاقات قابلة للضغط */

        .attachment-stat {
            display: block;
            background: #F7FBFB;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #9ADFDB;
            color: inherit;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .attachment-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(44, 74, 90, 0.08);
        }

        .attachment-stat-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #526D79;
            font-size: 14px;
            font-weight: bold;
        }

        .stat-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .stat-dot.with {
            background: #64C7C4;
        }

        .stat-dot.without {
            background: #DCEAEA;
            border: 1px solid #C5D8D8;
        }

        .attachment-stat-value {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .attachment-stat-percentage {
            font-size: 15px;
            color: #78909A;
        }

        .card-hint {
            display: block;
            margin-top: 12px;
            color: #2C9995;
            font-size: 12px;
        }

        .empty-note {
            margin-top: 22px;
            padding: 14px 18px;
            background: #F5FAFA;
            border-radius: 12px;
            text-align: center;
            color: #78909A;
            font-size: 13px;
        }

        /* قسم تفاصيل الفحوصات */

        .details-section {
            background: #FFFFFF;
            border: 1px solid #9ADFDB;
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(44, 74, 90, 0.05);
        }

        .details-section h2 {
            font-size: 19px;
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
            margin-bottom: 20px;
            color: #2C9995;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
        }

        .details-back:hover {
            text-decoration: underline;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .details-table {
            width: 100%;
            min-width: 750px;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            padding: 14px;
            text-align: right;
            border-bottom: 1px solid #E8F0F0;
            font-size: 13px;
        }

        .details-table th {
            background: #EAF7F7;
            color: #2C4A5A;
            font-weight: bold;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .attachment-link {
            display: inline-block;
            padding: 8px 12px;
            background: #E5F7F5;
            color: #258E89;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            white-space: nowrap;
        }

        .attachment-link:hover {
            background: #64C7C4;
            color: white;
        }

        .empty-details {
            padding: 25px;
            background: #F7FBFB;
            border-radius: 12px;
            text-align: center;
            color: #78909A;
            font-size: 14px;
        }

        /* التوافق مع الشاشات */

        @media (max-width: 850px) {
            .overview-top {
                flex-direction: column;
            }

            .exams-total {
                flex-wrap: wrap;
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

            .header {
                padding: 20px;
            }

            .header-top {
                flex-direction: column;
                align-items: flex-start;
            }

            .exams-overview,
            .attachments-section,
            .details-section {
                padding: 22px;
            }

            .total-number {
                font-size: 58px;
            }

            .attachment-stats {
                grid-template-columns: 1fr;
            }

            .total-link {
                gap: 15px;
            }
        }

        @media (max-width: 420px) {
            .menu {
                grid-template-columns: 1fr;
            }

            .period-form {
                flex-direction: column;
                align-items: flex-start;
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
                    <h1>🩺 الفحوصات الطبية</h1>
                    <p>متابعة استخدام ميزة الفحوصات الطبية في تطبيق TinyGuide.</p>
                </div>

                <a href="{{ url('/records') }}" class="back-to-records">
                    ← العودة للسجلات الصحية
                </a>

            </div>
        </div>

        <!-- إجمالي الفحوصات مع الفلتر -->

        <section class="exams-overview">

            <div class="overview-top">

                <div class="overview-title">

                    <div class="overview-icon">🧪</div>

                    <div>
                        <h2>الفحوصات الطبية المسجلة</h2>
                        <p>عدد الفحوصات التي أُضيفت إلى التطبيق.</p>
                    </div>

                </div>

                <form method="GET"
                      action="{{ url()->current() }}"
                      class="period-form">

                    <label for="period">الفترة الزمنية</label>

                    <select
                        name="period"
                        id="period"
                        class="period-select"
                        onchange="this.form.submit()"
                    >

                        <option value="all" {{ $period === 'all' ? 'selected' : '' }}>
                            جميع الفترات
                        </option>

                        <option value="day" {{ $period === 'day' ? 'selected' : '' }}>
                            آخر 24 ساعة
                        </option>

                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>
                            آخر 7 أيام
                        </option>

                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>
                            آخر 30 يومًا
                        </option>

                        <option value="3months" {{ $period === '3months' ? 'selected' : '' }}>
                            آخر 3 أشهر
                        </option>

                        <option value="6months" {{ $period === '6months' ? 'selected' : '' }}>
                            آخر 6 أشهر
                        </option>

                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>
                            آخر سنة
                        </option>

                    </select>

                </form>

            </div>

            <!-- إجمالي الفحوصات القابل للضغط -->

            <div class="exams-total">

                <a href="{{ url()->current() }}?period={{ $period }}&details=all"
                   class="total-link">

                    <div class="total-number">
                        {{ number_format($totalExams) }}
                    </div>

                    <div class="total-description">

                        <strong>فحص طبي مسجل</strong>

                        <span>
                            @switch($period)

                                @case('day')
                                    خلال آخر 24 ساعة
                                    @break

                                @case('week')
                                    خلال آخر 7 أيام
                                    @break

                                @case('month')
                                    خلال آخر 30 يومًا
                                    @break

                                @case('3months')
                                    خلال آخر 3 أشهر
                                    @break

                                @case('6months')
                                    خلال آخر 6 أشهر
                                    @break

                                @case('year')
                                    خلال آخر سنة
                                    @break

                                @default
                                    منذ بداية استخدام التطبيق

                            @endswitch
                        </span>

                        <span class="card-hint">
                            اضغط لعرض جميع الفحوصات
                        </span>

                    </div>

                </a>

            </div>

        </section>

        <!-- استخدام المرفقات -->

        <section class="attachments-section">

            <div class="attachments-header">

                <h2>📎 استخدام ميزة المرفقات</h2>

                <p>
                    مقارنة بين الفحوصات الطبية التي تحتوي على
                    ملفات أو صور مرفقة والفحوصات التي لا تحتوي على مرفقات،
                    ضمن الفترة الزمنية المحددة.
                </p>

            </div>

            <div class="attachment-progress"
                 role="progressbar"
                 aria-label="نسبة الفحوصات التي تحتوي على مرفقات"
                 aria-valuenow="{{ $attachmentPercentage }}"
                 aria-valuemin="0"
                 aria-valuemax="100">

                <div class="progress-with"
                     style="width: {{ $attachmentPercentage }}%">
                </div>

                <div class="progress-without"
                     style="width: {{ $withoutAttachmentPercentage }}%">
                </div>

            </div>

            <div class="attachment-stats">

                <!-- فحوصات تحتوي على مرفقات -->

                <a href="{{ url()->current() }}?period={{ $period }}&details=with_attachments"
                   class="attachment-stat">

                    <div class="attachment-stat-header">
                        <span class="stat-dot with"></span>
                        فحوصات تحتوي على مرفقات
                    </div>

                    <div class="attachment-stat-value">
                        {{ number_format($examsWithAttachments) }}
                    </div>

                    <div class="attachment-stat-percentage">
                        {{ $attachmentPercentage }}%
                        من الفحوصات في الفترة المحددة
                    </div>

                    <span class="card-hint">
                        اضغط لعرض الفحوصات والمرفقات
                    </span>

                </a>

                <!-- فحوصات بدون مرفقات -->

                <a href="{{ url()->current() }}?period={{ $period }}&details=without_attachments"
                   class="attachment-stat">

                    <div class="attachment-stat-header">
                        <span class="stat-dot without"></span>
                        فحوصات بدون مرفقات
                    </div>

                    <div class="attachment-stat-value">
                        {{ number_format($examsWithoutAttachments) }}
                    </div>

                    <div class="attachment-stat-percentage">
                        {{ $withoutAttachmentPercentage }}%
                        من الفحوصات في الفترة المحددة
                    </div>

                    <span class="card-hint">
                        اضغط لعرض الفحوصات
                    </span>

                </a>

            </div>

            @if($totalExams === 0)

                <div class="empty-note">
                    لا توجد فحوصات طبية مسجلة خلال الفترة المحددة.
                </div>

            @endif

        </section>

        <!-- تفاصيل البطاقة المختارة -->

        @if($details)

            <section class="details-section" id="exam-details">

                <a href="{{ url()->current() }}?period={{ $period }}"
                   class="details-back">
                    ← العودة إلى إحصائيات الفحوصات
                </a>

                @if($details === 'all')

                    <h2>🧪 جميع الفحوصات الطبية</h2>

                    <p class="details-description">
                        جميع الفحوصات المسجلة ضمن الفترة الزمنية المختارة.
                    </p>

                @elseif($details === 'with_attachments')

                    <h2>📎 الفحوصات التي تحتوي على مرفقات</h2>

                    <p class="details-description">
                        الفحوصات التي تحتوي على ملف أو صورة مرفقة.
                    </p>

                @elseif($details === 'without_attachments')

                    <h2>📋 الفحوصات التي لا تحتوي على مرفقات</h2>

                    <p class="details-description">
                        الفحوصات التي تم تسجيلها دون إرفاق ملف.
                    </p>

                @endif

                @if($exams->isNotEmpty())

                    <div class="table-wrapper">

                        <table class="details-table">

                            <thead>
                                <tr>
                                    <th>رقم الفحص</th>
                                    <th>اسم الطفل</th>
                                    <th>اسم الفحص</th>
                                    <th>النتيجة</th>
                                    <th>وحدة القياس</th>
                                    <th>تاريخ الفحص</th>
                                    <th>المرفق</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($exams as $exam)

                                    <tr>

                                        <td>
                                            {{ $exam->medical_exam_id }}
                                        </td>

                                        <td>
                                            {{ $exam->child?->name ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $exam->exam_name ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $exam->exam_result ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $exam->measurement_unit ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $exam->exam_date?->format('Y-m-d') ?? '—' }}
                                        </td>

                                        <td>

                                            @if($exam->attachment_path)

                                                <a
                                                    href="{{ \Illuminate\Support\Facades\Storage::url($exam->attachment_path) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="attachment-link"
                                                >
                                                    عرض المرفق
                                                </a>

                                            @else

                                                —

                                            @endif

                                        </td>

                                    </tr>

                                    @if($exam->notes)

                                        <tr>
                                            <td colspan="7">
                                                <strong>ملاحظات:</strong>
                                                {{ $exam->notes }}
                                            </td>
                                        </tr>

                                    @endif

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="empty-details">
                        لا توجد فحوصات تطابق هذا التصنيف ضمن الفترة المحددة.
                    </div>

                @endif

            </section>

        @endif

    </main>

</div>

</body>
</html>