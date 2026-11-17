<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المستخدم | TinyGuide</title>

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

        .header {
            background: white;
            padding: 25px;
            border-radius: 18px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .header p {
            color: #78909a;
            font-size: 14px;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
            max-width: 800px;
        }

        .form-card h2 {
            font-size: 19px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            font-size: 14px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #d9e9e9;
            border-radius: 11px;
            font-size: 15px;
            font-family: inherit;
            outline: none;
            background: #fff;
            color: #2C4A5A;
        }

        .form-control:focus {
            border-color: #64C7C4;
            box-shadow: 0 0 0 3px rgba(100, 199, 196, 0.12);
        }

        .form-control.is-invalid {
            border-color: #C74747;
        }

        .error-text {
            display: block;
            color: #C74747;
            font-size: 13px;
            margin-top: 7px;
        }

        .info-box {
            background: #E8F7F6;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            line-height: 1.8;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-family: inherit;
            cursor: pointer;
            text-align: center;
        }

        .btn-primary {
            background: #64C7C4;
            color: white;
        }

        .btn-primary:hover {
            background: #4db6b3;
        }

        .btn-secondary {
            background: #E8F7F6;
            color: #2C4A5A;
        }

        .alert-error {
            background: #FCE8E8;
            color: #C74747;
            padding: 15px 18px;
            border-radius: 12px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        .alert-error ul {
            padding-right: 20px;
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

            .form-card {
                padding: 20px;
            }

            .actions {
                flex-direction: column;
                align-items: stretch;
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
            <h1>تعديل المستخدم</h1>
            <p>تحديث بيانات حساب المستخدم في تطبيق المرشد الصغير.</p>
        </div>

        <div class="form-card">

            <h2>بيانات المستخدم: {{ $user->name }}</h2>

            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('users.update', $user->id) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">اسم المستخدم</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}"
                        required
                        maxlength="255"
                        autocomplete="name"
                    >

                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

               <div class="form-group">
    <label for="phone">رقم هاتف ولي الأمر</label>

    <input
        type="tel"
        id="phone"
        name="phone"
        class="form-control @error('phone') is-invalid @enderror"
        value="{{ old('phone', $user->phone) }}"
        placeholder="أدخلي رقم هاتف ولي الأمر"
        maxlength="20"
        autocomplete="tel"
        dir="ltr"
        required
    >

    @error('phone')
        <span class="error-text">{{ $message }}</span>
    @enderror
</div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        حفظ التعديلات
                    </button>

                    <a
                        href="{{ route('users.show', $user->id) }}"
                        class="btn btn-secondary"
                    >
                        إلغاء والعودة للتفاصيل
                    </a>

                    <a
                        href="{{ route('users.index') }}"
                        class="btn btn-secondary"
                    >
                        قائمة المستخدمين
                    </a>
                </div>

            </form>

        </div>

    </main>

</div>

</body>
</html>