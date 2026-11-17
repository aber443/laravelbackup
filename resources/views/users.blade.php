<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المستخدمون | TinyGuide</title>

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
            background: #ffffff;
            padding: 30px 20px;
            border-left: 1px solid #e2eeee;
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
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

        /* بطاقة عنوان الصفحة */
        .header {
            background: white;
            padding: 20px 25px;
            border: 1.5px solid #83D2CF;
            border-radius: 18px;
            margin-bottom: 25px;

            box-shadow:
                0 4px 12px rgba(44, 74, 90, 0.07),
                0 2px 8px rgba(100, 199, 196, 0.10);
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .header p {
            color: #78909a;
            font-size: 14px;
        }

        /* بطاقة البحث */
        .search-card {
            background: white;
            padding: 20px 25px;
            border: 1.5px solid #83D2CF;
            border-radius: 18px;
            margin-bottom: 25px;

            box-shadow:
                0 4px 12px rgba(44, 74, 90, 0.07),
                0 2px 8px rgba(100, 199, 196, 0.10);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .search-card:hover {
            border-color: #64C7C4;

            box-shadow:
                0 7px 18px rgba(44, 74, 90, 0.10),
                0 3px 10px rgba(100, 199, 196, 0.16);
        }

        .search-form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 220px;
            padding: 13px 15px;
            border: 1px solid #d9e9e9;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
        }

        .search-input:focus {
            border-color: #64C7C4;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-family: inherit;
            cursor: pointer;
            white-space: nowrap;
            text-align: center;
        }

        .btn-primary {
            background: #64C7C4;
            color: white;
        }

        .btn-secondary {
            background: #E8F7F6;
            color: #2C4A5A;
        }

        .btn-warning {
            background: #FFF0D6;
            color: #A66A00;
        }

        .btn-danger {
            background: #FCE8E8;
            color: #C74747;
        }

        .btn-success {
            background: #E1F6EA;
            color: #238A56;
        }

        /* بطاقة جدول المستخدمين */
        .table-card {
            background: white;
            border: 1.5px solid #83D2CF;
            border-radius: 18px;
            padding: 25px;

            box-shadow:
                0 4px 12px rgba(44, 74, 90, 0.07),
                0 2px 8px rgba(100, 199, 196, 0.10);

            overflow-x: auto;

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .table-card:hover {
            border-color: #64C7C4;

            box-shadow:
                0 7px 18px rgba(44, 74, 90, 0.10),
                0 3px 10px rgba(100, 199, 196, 0.16);
        }

        .table-title {
            font-size: 18px;
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th {
            background: #E8F7F6;
            color: #2C4A5A;
            padding: 15px;
            text-align: right;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #edf3f3;
            font-size: 14px;
            vertical-align: middle;
        }

        .phone-number {
            direction: ltr;
            unicode-bidi: isolate;
            display: inline-block;
            white-space: nowrap;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
        }

        .status-active {
            background: #E1F6EA;
            color: #238A56;
        }

        .status-disabled {
            background: #FCE8E8;
            color: #C74747;
        }

        .status-admin {
            background: #E8F7F6;
            color: #3FAEAA;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .actions form {
            display: inline;
        }

        .empty-message {
            text-align: center;
            color: #8aa1aa;
            padding: 35px 10px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #E1F6EA;
            color: #238A56;
        }

        .alert-error {
            background: #FCE8E8;
            color: #C74747;
        }

        @media (max-width: 700px) {
            .dashboard {
                display: block;
            }

            .sidebar {
                width: 100%;
                border-left: none;
            }

            .main-content {
                padding: 18px;
            }

            .search-form {
                flex-direction: column;
            }

            .search-input {
                min-width: 100%;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        <div class="header">
            <h1>المستخدمون</h1>
            <p>
                إدارة مستخدمي تطبيق المرشد الصغير
                وعرض بياناتهم وأرقام هواتفهم وحالة حساباتهم.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="search-card">

            <form
                action="{{ route('users.index') }}"
                method="GET"
                class="search-form"
            >

                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="ابحث باسم المستخدم أو رقم الهاتف..."
                    value="{{ $search ?? '' }}"
                >

                <button type="submit" class="btn btn-primary">
                    بحث
                </button>

                <a
                    href="{{ route('users.index') }}"
                    class="btn btn-secondary"
                >
                    عرض الكل
                </a>

            </form>

        </div>

        <div class="table-card">

            <h2 class="table-title">
                قائمة المستخدمين ({{ $users->count() }})
            </h2>

            <table>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المستخدم</th>
                        <th>رقم الهاتف</th>
                        <th>الحالة</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>{{ $user->user_id }}</td>

                            <td>{{ $user->name }}</td>

                            <td>
                                @if(!empty($user->phone))
                                    <span class="phone-number">
                                        {{ $user->phone }}
                                    </span>
                                @else
                                    <span style="color: #8aa1aa;">
                                        غير مسجل
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($user->is_active)
                                    <span class="status status-active">
                                        نشط
                                    </span>
                                @else
                                    <span class="status status-disabled">
                                        معطّل
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $user->created_at?->format('Y-m-d') ?? '—' }}
                            </td>

                            <td>

                                <div class="actions">

                                    <a
                                        href="{{ route('users.show', $user->user_id) }}"
                                        class="btn btn-primary"
                                    >
                                        التفاصيل
                                    </a>

                                    @unless($user->isAdmin())

                                        @if($user->is_active)

                                            <form
                                                action="{{ route('users.disable', $user->user_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('هل تريد تعطيل حساب هذا المستخدم؟ لن يتم حذف بياناته.');"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    تعطيل
                                                </button>
                                            </form>

                                        @else

                                            <form
                                                action="{{ route('users.enable', $user->user_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('هل تريد إعادة تفعيل حساب هذا المستخدم؟');"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-success"
                                                >
                                                    تفعيل
                                                </button>
                                            </form>

                                        @endif

                                    @endunless

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">

                                <div class="empty-message">

                                    @if(!empty($search))
                                        لا توجد نتائج مطابقة للبحث.
                                    @else
                                        لا توجد بيانات مستخدمين حاليًا.
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