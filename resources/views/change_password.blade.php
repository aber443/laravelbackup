<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تغيير كلمة المرور | TinyGuide</title>

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
            background: #ffffff;
            padding: 30px 20px;
            border-left: 1px solid #E2EEEE;
            flex-shrink: 0;
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
            padding: 30px;
            min-width: 0;
            overflow-x: hidden;
        }

        .page-header {
            margin-bottom: 520px;
            margin: 0 auto 25px;
        }

        .page-header h1 {
            font-size: 26px;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #78909A;
        }

        .form-card {
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #CFE0E0;
            border-radius: 10px;
            font: inherit;
            color: #2C4A5A;
        }

        input:focus {
            outline: none;
            border-color: #64C7C4;
            box-shadow: 0 0 0 3px rgba(100, 199, 196, 0.15);
        }

        .save-button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: #64C7C4;
            color: white;
            font: inherit;
            font-weight: bold;
            cursor: pointer;
        }

        .save-button:hover {
            background: #4EB5B2;
        }

        .error {
            background: #FFF0F0;
            color: #A52828;
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: 10px;
        }

        .success {
            background: #ECFAF3;
            color: #277A55;
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: 10px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #64C7C4;
            text-decoration: none;
            font-weight: bold;
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
                padding: 18px;
            }

            .form-card {
                padding: 22px;
            }

            .page-header h1 {
                font-size: 22px;
            }
        }

        @media (max-width: 420px) {
            .menu {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 12px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        <div class="page-header">
            <h1>تغيير كلمة المرور</h1>
            <p>تحديث كلمة مرور حساب المشرف.</p>
        </div>

        <section class="form-card">

            @if (session('success'))
                <div class="success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ url('/change-password') }}">
                @csrf

                <div class="form-group">
                    <label for="current_password">
                        كلمة المرور الحالية
                    </label>

                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">
                        كلمة المرور الجديدة
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        تأكيد كلمة المرور الجديدة
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <button type="submit" class="save-button">
                    حفظ كلمة المرور
                </button>

            </form>

            <a href="{{ url('/settings') }}" class="back-link">
                ← العودة إلى الإعدادات
            </a>

        </section>

    </main>

</div>

</body>
</html>