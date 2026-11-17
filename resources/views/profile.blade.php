<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>الملف الشخصي | TinyGuide</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
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
            border-left: 1px solid #E2EEEE;
            flex-shrink: 0;
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo h2 {
            color: #64C7C4;
            margin-top: 0;
            margin-bottom: 5px;
        }

        .logo p {
            color: #8AA1AA;
            font-size: 14px;
            margin-top: 0;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #2C4A5A;
            padding: 14px 16px;
            margin-top: 0;
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

        /* عنوان الصفحة */
        .page-header {
            width: 100%;
            background: #ffffff;
            border: 1px solid #BFE9E6;
            border-radius: 18px;
            padding: 22px 25px;
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #78909A;
            font-size: 14px;
        }

        /* البطاقة الرئيسية */
        .profile-card {
            width: 100%;
            background: #ffffff;
            border: 1px solid #BFE9E6;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(44, 74, 90, 0.06);
        }

        /* الجزء العلوي للحساب */
       .profile-top {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 22px;
    padding: 25px;
    background: #F5FBFA;
    border: 1px solid #E2EEEE;
    border-radius: 15px;
    text-align: right;
}

        .avatar {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            background: #E8F7F6;
            color: #64C7C4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .profile-top h2 {
            font-size: 22px;
            margin: 0 0 8px;
        }

        .profile-email {
            color: #78909A;
            font-size: 14px;
            margin: 0;
            overflow-wrap: anywhere;
        }

        /* توزيع بيانات الحساب */
        .profile-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-top: 25px;
            width: 100%;
        }

        /* بطاقات المعلومات */
        .info-row {
            background: #F8FCFC;
            border: 1px solid #E2EEEE;
            border-radius: 14px;
            padding: 25px;
            min-height: 115px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: right;
        }

        .info-label {
            display: block;
            color: #8AA1AA;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .info-value {
            font-size: 17px;
            font-weight: bold;
            color: #2C4A5A;
            overflow-wrap: anywhere;
        }

        /* الشاشات الصغيرة */
        @media (max-width: 700px) {

            .dashboard {
                display: block;
            }

            .sidebar {
                width: 100%;
                border-left: none;
                border-bottom: 1px solid #E2EEEE;
            }

            .main-content {
                padding: 18px;
            }

            .profile-card {
                padding: 18px;
            }

            .profile-info {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 22px;
            }
        }

        @media (max-width: 500px) {

            .profile-top {
                flex-direction: column;
                text-align: center;
            }

            .info-row {
                padding: 20px;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
</head>

<body>

<div class="dashboard">

    @include('partials.admin-sidebar')

    <main class="main-content">

        <!-- عنوان الصفحة -->
        <div class="page-header">

            <h1>الملف الشخصي</h1>

            <p>
                إدارة بيانات حسابك في TinyGuide.
            </p>

        </div>

        <!-- بطاقة الملف الشخصي -->
        <section class="profile-card">

            <!-- بيانات الحساب الأساسية -->
            <div class="profile-top">

                <div class="avatar">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>

                <div>

                    <h2>
                        {{ $user->name }}
                    </h2>

                    <p class="profile-email" dir="ltr">
                        {{ $user->email }}
                    </p>

                </div>

            </div>

            <!-- المعلومات الشخصية -->
            <div class="profile-info">

                <div class="info-row">

                    <span class="info-label">
                        الاسم الكامل
                    </span>

                    <div class="info-value">
                        {{ $user->name }}
                    </div>

                </div>

                <div class="info-row">

                    <span class="info-label">
                        البريد الإلكتروني
                    </span>

                    <div class="info-value" dir="ltr">
                        {{ $user->email }}
                    </div>

                </div>

            </div>

        </section>

    </main>

</div>

</body>
</html>