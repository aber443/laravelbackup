<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>السجلات - TinyGuide</title>

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

        .container {
            padding: 40px;
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            margin-bottom: 10px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        /* ترتيب البطاقات: 3 فوق و2 في المنتصف تحت */
        .records-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 25px;
        }

        /* بطاقات السجلات الصحية */
        .record-card {
            grid-column: span 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 228px;
            background: white;
            padding: 30px 20px;

            border: 2px solid #64C7C4;
            border-radius: 16px;

            text-decoration: none;
            color: #2c4a5a;
            text-align: center;

            box-shadow:
                0 5px 15px rgba(44, 74, 90, 0.10),
                0 3px 10px rgba(100, 199, 196, 0.16);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        /* توسيط البطاقتين في الصف الثاني */
        .record-card:nth-child(4) {
            grid-column: 2 / span 2;
        }

        .record-card:nth-child(5) {
            grid-column: 4 / span 2;
        }

        /* تأثير المرور على البطاقات */
        .record-card:hover {
            transform: translateY(-5px);
            border-color: #399D9A;

            box-shadow:
                0 10px 25px rgba(44, 74, 90, 0.15),
                0 5px 15px rgba(100, 199, 196, 0.25);
        }

        .record-card:focus-visible {
            outline: 3px solid #64C7C4;
            outline-offset: 4px;
        }

        .icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .record-card h3 {
            margin: 0 0 8px;
        }

        .record-card p {
            margin: 0;
            color: #777;
            font-size: 14px;
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
            min-width: 0;
        }

        /* الشاشات المتوسطة */
        @media (max-width: 900px) {
            .records-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .record-card,
            .record-card:nth-child(4),
            .record-card:nth-child(5) {
                grid-column: auto;
            }
        }

        /* الهواتف */
        @media (max-width: 700px) {
            .dashboard {
                display: block;
            }

            .sidebar {
                width: 100%;
                border-left: none;
                border-bottom: 1px solid #e2eeee;
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

            .container {
                padding: 20px;
            }
        }

        @media (max-width: 420px) {
            .menu {
                grid-template-columns: 1fr;
            }

            .records-grid {
                grid-template-columns: 1fr;
            }

            .record-card,
            .record-card:nth-child(4),
            .record-card:nth-child(5) {
                grid-column: auto;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        <div class="container">

            <h1>السجلات الصحية</h1>

            <div class="subtitle">
                عرض ومتابعة سجلات الأطفال الصحية
            </div>

            <div class="records-grid">

                <!-- قراءات السكر -->
                <a href="{{ url('/glucose-readings') }}" class="record-card">
                    <div class="icon">🩸</div>
                    <h3>قراءات السكر</h3>
                    <p>عرض سجلات قياسات السكر</p>
                </a>

                <!-- الوجبات -->
                <a href="{{ url('/meals') }}" class="record-card">
                    <div class="icon">🍽️</div>
                    <h3>الوجبات</h3>
                    <p>عرض سجلات الوجبات</p>
                </a>

                <!-- النشاط -->
                <a href="{{ url('/activities') }}" class="record-card">
                    <div class="icon">🏃</div>
                    <h3>النشاط</h3>
                    <p>عرض سجلات النشاط البدني</p>
                </a>

                <!-- الأدوية -->
                <a href="{{ url('/medications') }}" class="record-card">
                    <div class="icon">💊</div>
                    <h3>الأدوية</h3>
                    <p>عرض سجلات الأدوية والجرعات</p>
                </a>

                <!-- الفحوصات الطبية -->
                <a href="{{ url('/medical-exams') }}" class="record-card">
                    <div class="icon">🩺</div>
                    <h3>الفحوصات الطبية</h3>
                    <p>عرض نتائج الفحوصات الطبية</p>
                </a>

            </div>

        </div>

    </main>

</div>

</body>
</html>