<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تقرير قراءات السكر | TinyGuide</title>

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

        .summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: #FFFFFF;
            padding: 22px;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        .summary-card span {
            display: block;
            color: #78909A;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .summary-card strong {
            display: block;
            color: #64C7C4;
            font-size: 27px;
        }

        .summary-card small {
            display: block;
            margin-top: 5px;
            color: #8AA1AA;
            font-size: 12px;
        }

        .table-card {
            background: #FFFFFF;
            padding: 24px;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        .table-title {
            margin-bottom: 18px;
        }

        .table-title h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .table-title p {
            color: #78909A;
            font-size: 13px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px 12px;
            text-align: right;
            border-bottom: 1px solid #EDF3F3;
            font-size: 14px;
        }

        th {
            background: #EAF7F7;
            color: #2C4A5A;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #F8FCFC;
        }

        .reading-value {
            display: inline-block;
            padding: 6px 10px;
            background: #EAF7F7;
            border-radius: 10px;
            font-weight: bold;
            white-space: nowrap;
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

        .notes {
            max-width: 280px;
            white-space: normal;
            line-height: 1.6;
        }

        .empty {
            text-align: center;
            padding: 40px 20px;
            color: #78909A;
        }

        .actions {
            margin-top: 22px;
        }

        .back {
            display: inline-block;
            padding: 11px 18px;
            background: #64C7C4;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 12px;
            font-size: 14px;
            transition: 0.2s;
        }

        .back:hover {
            opacity: 0.88;
        }

        @media (max-width: 1000px) {
            .summary {
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
            .table-card {
                padding: 18px;
            }

            .summary {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .summary-card {
                padding: 18px;
            }

            .summary-card strong {
                font-size: 23px;
            }
        }

        @media (max-width: 420px) {
            .menu,
            .summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        <div class="header">
            <h1>🩸 تقرير قراءات السكر</h1>
            <p>ملخص وتفاصيل قراءات مستوى السكر المسجلة للأطفال.</p>
        </div>

        <section class="summary">

            <div class="summary-card">
                <span>عدد القراءات</span>
                <strong>{{ $readings->count() }}</strong>
                <small>إجمالي القراءات المسجلة</small>
            </div>

            <div class="summary-card">
                <span>متوسط السكر</span>
                <strong>
                    {{ $average !== null ? number_format($average, 1) : '—' }}
                </strong>
                <small>mg/dL</small>
            </div>

            <div class="summary-card">
                <span>أعلى قراءة</span>
                <strong>
                    {{ $highest !== null ? $highest : '—' }}
                </strong>
                <small>mg/dL</small>
            </div>

            <div class="summary-card">
                <span>أقل قراءة</span>
                <strong>
                    {{ $lowest !== null ? $lowest : '—' }}
                </strong>
                <small>mg/dL</small>
            </div>

        </section>

        <div class="table-card">

            <div class="table-title">
                <h2>تفاصيل القراءات</h2>
                <p>عرض جميع قراءات السكر الموجودة في التقرير.</p>
            </div>

            @if($readings->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الطفل</th>
                                <th>مستوى السكر</th>
                                <th>نوع القياس</th>
                                <th>وقت القياس</th>
                                <th>الملاحظات</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($readings as $reading)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $reading->child->name ?? 'غير محدد' }}
                                </td>

                                <td>
                                    <span class="reading-value">
                                        {{ $reading->glucose_level }}
                                        {{ $reading->unit ?? 'mg/dL' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $reading->measurement_type ?: '—' }}
                                </td>

                                <td>
                                    <div class="date-time">

                                        <span class="date">
                                            {{ \Carbon\Carbon::parse($reading->measured_at)->format('Y-m-d') }}
                                        </span>

                                        <span class="time">
                                            {{ \Carbon\Carbon::parse($reading->measured_at)->format('H:i') }}
                                        </span>

                                    </div>
                                </td>

                                <td class="notes">
                                    {{ $reading->notes ?: '—' }}
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty">
                    لا توجد قراءات سكر مسجلة حاليًا.
                </div>

            @endif

        </div>

        <div class="actions">
            <a href="{{ url('/reports') }}" class="back">
                ← العودة إلى التقارير
            </a>
        </div>

    </main>

</div>

</body>
</html>