<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title }} | TinyGuide</title>

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
            background: #FFFFFF;
            padding: 22px;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
            margin-bottom: 25px;
        }

        .summary span {
            display: block;
            color: #78909A;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .summary strong {
            display: block;
            font-size: 30px;
            color: #64C7C4;
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
            border-collapse: collapse;
            min-width: 800px;
        }

        th,
        td {
            padding: 15px 12px;
            text-align: right;
            border-bottom: 1px solid #EDF3F3;
            font-size: 14px;
            white-space: nowrap;
        }

        th {
            background: #EAF7F7;
            color: #2C4A5A;
        }

        tbody tr:hover {
            background: #F8FCFC;
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
            .summary,
            .table-card {
                padding: 18px;
            }
        }

        @media (max-width: 420px) {
            .menu {
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
            <h1>{{ $title }}</h1>
            <p>تفاصيل السجلات المسجلة في TinyGuide.</p>
        </div>

        <div class="summary">
            <span>إجمالي السجلات</span>
            <strong>{{ $rows->count() }}</strong>
        </div>

        <div class="table-card">

            <div class="table-title">
                <h2>تفاصيل التقرير</h2>
                <p>عرض البيانات المسجلة ضمن هذا التقرير.</p>
            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($rows as $row)

                            <tr>
                                @foreach($row as $value)
                                    <td>{{ $value ?? '—' }}</td>
                                @endforeach
                            </tr>

                        @empty

                            <tr>
                                <td colspan="{{ count($headers) }}" class="empty">
                                    لا توجد بيانات مسجلة حاليًا.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

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