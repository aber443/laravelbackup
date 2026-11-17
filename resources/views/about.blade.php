<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>حول TinyGuide</title>

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

        .about-card {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        .about-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background: #E8F7F6;
            color: #64C7C4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .about-card h2 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .about-card p {
            color: #5F747E;
            line-height: 1.9;
            margin-bottom: 16px;
        }

        .info-box {
            margin-top: 22px;
            padding: 18px;
            background: #F8FBFB;
            border: 1px solid #EDF3F3;
            border-radius: 14px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #EDF2F2;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #8AA1AA;
        }

        .info-value {
            font-weight: bold;
        }

        .back-link {
            display: inline-block;
            margin-top: 22px;
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

            .about-card {
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

            .info-row {
                flex-direction: column;
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
            <h1>حول TinyGuide</h1>
            <p>معلومات عامة عن النظام ولوحة الإدارة.</p>
        </div>

        <section class="about-card">

            <div class="about-icon">
                ℹ️
            </div>

            <h2>TinyGuide - المرشد الصغير</h2>

            <p>
                TinyGuide هو نظام يساعد في متابعة الأطفال المصابين بالسكري
                وتنظيم بياناتهم الصحية بطريقة واضحة ومنظمة.
            </p>

            <p>
                يستخدم ولي الأمر التطبيق لتسجيل ومتابعة بيانات الطفل،
                بينما توفر لوحة الإدارة للمشرف إمكانية متابعة المستخدمين
                والأطفال والسجلات الصحية والتقارير والمواعيد.
            </p>

            <div class="info-box">

                <div class="info-row">
                    <span class="info-label">اسم النظام</span>
                    <span class="info-value">TinyGuide</span>
                </div>

                <div class="info-row">
                    <span class="info-label">نوع الواجهة</span>
                    <span class="info-value">لوحة إدارة</span>
                </div>

                <div class="info-row">
                    <span class="info-label">الغرض</span>
                    <span class="info-value">
                        متابعة وإدارة البيانات الصحية
                    </span>
                </div>

            </div>

            <a href="{{ url('/settings') }}" class="back-link">
                ← العودة إلى الإعدادات
            </a>

        </section>

    </main>

</div>

</body>
</html>