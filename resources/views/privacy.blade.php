<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>الخصوصية | TinyGuide</title>

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
        }

        .page-header {
            width: 100%;
            max-width: 650px;
            margin: 0 auto 25px;
        }

        .page-header h1 {
            font-size: 26px;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #78909A;
        }

        .privacy-card {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        .privacy-icon {
            width: 65px;
            height: 65px;
            margin-bottom: 20px;
            border-radius: 16px;
            background: #E8F7F6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .privacy-card h2 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .privacy-card p {
            line-height: 1.9;
            color: #5F747E;
            margin-bottom: 18px;
        }

        .privacy-list {
            margin: 20px 0;
            padding-right: 20px;
        }

        .privacy-list li {
            margin-bottom: 12px;
            line-height: 1.7;
            color: #5F747E;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
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

            .privacy-card {
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
            <h1>الخصوصية</h1>
            <p>معلومات الخصوصية وحماية البيانات في TinyGuide.</p>
        </div>

        <section class="privacy-card">

            <div class="privacy-icon">
                🛡️
            </div>

            <h2>حماية بيانات المستخدمين</h2>

            <p>
                يلتزم TinyGuide بالحفاظ على خصوصية بيانات المستخدمين
                والأطفال، واستخدام البيانات فقط ضمن وظائف النظام
                ومتابعة السجلات الصحية.
            </p>

            <ul class="privacy-list">
                <li>
                    يتم التعامل مع بيانات المستخدمين والأطفال بسرية.
                </li>

                <li>
                    لا يتم عرض بيانات لوحة الإدارة إلا للمشرف المصرح له.
                </li>

                <li>
                    تستخدم البيانات الصحية لأغراض المتابعة داخل النظام.
                </li>

                <li>
                    يتم حماية حساب الإدارة من خلال تسجيل الدخول وكلمة المرور.
                </li>
            </ul>

            <a href="{{ url('/settings') }}" class="back-link">
                ← العودة إلى الإعدادات
            </a>

        </section>

    </main>

</div>

</body>
</html>