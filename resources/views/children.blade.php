<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأطفال - TinyGuide</title>

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
            font-family: Arial, sans-serif;
            background: #f1f8f8;
            color: #2c4a5a;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #ffffff;
            padding: 30px 20px;
            border-left: 1px solid #e2eeee;
            flex-shrink: 0;
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
            color: #8aa1aa;
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
            padding: 30px;
            min-width: 0;
        }

        h1 {
            margin-bottom: 25px;
        }

        /* بطاقة جدول الأطفال */
        .table-card {
            background: white;
            border: 1.5px solid #83D2CF;
            border-radius: 15px;
            padding: 20px;

            box-shadow:
                0 4px 12px rgba(44, 74, 90, 0.07),
                0 2px 8px rgba(100, 199, 196, 0.10);

            overflow-x: auto;

            transition:
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .table-card:hover {
            border-color: #64C7C4;

            box-shadow:
                0 7px 18px rgba(44, 74, 90, 0.10),
                0 3px 10px rgba(100, 199, 196, 0.16);
        }

        /* البحث والإحصائيات */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-input {
            padding: 12px 15px;
            width: 260px;
            max-width: 100%;
            border: 1px solid #d5e7e7;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
        }

        .search-input:focus {
            border-color: #64C7C4;
        }

        .search-btn {
            background: #64C7C4;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
        }

        .search-btn:hover {
            background: #4bb6b3;
        }

        .reset-btn {
            display: inline-block;
            padding: 12px 15px;
            background: #eef6f6;
            color: #2c4a5a;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
        }

        /* بطاقة عدد الأطفال */
        .children-count {
            background: #e5f7f6;
            color: #2c7775;
            padding: 12px 18px;
            border: 1px solid #c5e9e7;
            border-radius: 10px;
            font-weight: bold;

            box-shadow:
                0 2px 7px rgba(44, 74, 90, 0.04);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #64c7c4;
            color: white;
        }

        .empty-message {
            padding: 30px;
            color: #777;
            text-align: center;
        }

        @media (max-width: 700px) {
            .dashboard {
                display: block;
            }

            .sidebar {
                width: 100%;
                box-sizing: border-box;
                border-left: none;
                border-bottom: 1px solid #e2eeee;
            }

            .main-content {
                padding: 18px;
            }

            .menu {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .menu a {
                margin-bottom: 0;
                text-align: center;
            }

            .toolbar {
                align-items: stretch;
            }

            .search-form {
                width: 100%;
            }

            .search-input {
                width: 100%;
                box-sizing: border-box;
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

        <h1>الأطفال</h1>

        <div class="table-card">

            <!-- البحث وعدد الأطفال -->
            <div class="toolbar">

                <form method="GET"
                      action="{{ url('/children') }}"
                      class="search-form">

                    <input
                        type="text"
                        name="search"
                        class="search-input"
                        placeholder="ابحث باسم الطفل أو ولي الأمر..."
                        value="{{ $search ?? '' }}"
                    >

                    <button type="submit" class="search-btn">
                        بحث
                    </button>

                    <a href="{{ url('/children') }}" class="reset-btn">
                        عرض الكل
                    </a>

                </form>

                @if(request('without_readings') === '1')

                    <div class="children-count">
                        🔔 الأطفال بدون قراءات السكر:
                        {{ $children->count() }}
                    </div>

                @else

                    <div class="children-count">
                        عدد الأطفال: {{ $children->count() }}
                    </div>

                @endif

            </div>

            <!-- جدول الأطفال -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الطفل</th>
                        <th>المستخدم</th>
                        <th>تاريخ الميلاد</th>
                        <th>الجنس</th>
                        <th>تاريخ التشخيص</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($children as $child)
                        <tr>
                            <td>{{ $child->child_id }}</td>
                            <td>{{ $child->name }}</td>
                            <td>{{ $child->user->name ?? '-' }}</td>
                            <td>{{ $child->birth_date }}</td>
                            <td>{{ $child->gender }}</td>
                            <td>{{ $child->diagnosis_date ?? '-' }}</td>
                            <td>
                                <a href="{{ route('children.show', $child->child_id) }}"
                                   style="
                                   display: inline-block;
                                   background: #64C7C4;
                                   color: white;
                                   padding: 9px 15px;
                                   border-radius: 8px;
                                   text-decoration: none;
                                   font-size: 13px;
                                   ">
                                    عرض التفاصيل
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-message">
                                    @if(request('without_readings') === '1')

                                        @if(!empty($search))
                                            🔍 لا يوجد أطفال بدون قراءات يطابقون البحث.
                                        @else
                                            ✅ لا يوجد حاليًا أطفال بدون قراءات سكر.
                                        @endif

                                    @elseif(!empty($search))

                                        🔍 لا توجد نتائج مطابقة للبحث.

                                    @else

                                        لا توجد بيانات أطفال حاليًا.

                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </main>

</div>

</body>
</html>