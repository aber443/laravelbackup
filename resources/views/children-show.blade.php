<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطفل - TinyGuide</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
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

        h1 {
            margin-bottom: 25px;
            text-align: center;
        }

        /* البطاقة الرئيسية */
        .details-card {
            background: white;
            border: 1.5px solid #83D2CF;
            border-radius: 18px;
            padding: 30px;

            box-shadow:
                0 4px 12px rgba(44, 74, 90, 0.07),
                0 2px 8px rgba(100, 199, 196, 0.10);

            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .section-title {
            color: #2c7775;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5f7f6;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        /* بطاقات البيانات الداخلية */
        .detail-item {
            background: #f7fbfb;
            border: 1px solid #D5ECEB;
            padding: 18px;
            border-radius: 12px;
            box-shadow:
                0 3px 9px rgba(44, 74, 90, 0.04),
                0 2px 6px rgba(100, 199, 196, 0.06);
            overflow-wrap: anywhere;
        }

        .detail-label {
            color: #7b929b;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .detail-value {
            font-size: 17px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .parent-link {
            color: #2c9995;
            text-decoration: none;
        }

        .parent-link:hover {
            text-decoration: underline;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 22px;
            background: #64C7C4;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }

        .back-btn:hover {
            background: #4bb6b3;
        }

        @media (max-width: 700px) {
            .dashboard {
                display: block;
            }

            .sidebar {
                width: 100%;
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

            .details-card {
                padding: 20px;
            }

            .details-grid {
                grid-template-columns: 1fr;
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

        <h1>تفاصيل الطفل</h1>

        <div class="details-card">

            <h2 class="section-title">👶 البيانات الأساسية</h2>

            <div class="details-grid">

                <div class="detail-item">
                    <div class="detail-label">اسم الطفل</div>
                    <div class="detail-value">
                        {{ $child->name }}
                    </div>
                </div>

                <div class="detail-item child-id-item">
                    <div class="detail-label">رقم الطفل</div>
                    <div class="detail-value">
                        {{ $child->child_id }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">تاريخ الميلاد</div>
                    <div class="detail-value">
                        {{ $child->birth_date ?? '-' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">العمر</div>
                    <div class="detail-value">
                        {{ $child->birth_date ? \Carbon\Carbon::parse($child->birth_date)->age . ' سنوات' : '-' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">الجنس</div>
                    <div class="detail-value">
                        @if($child->gender === 'male')
                            ذكر
                        @elseif($child->gender === 'female')
                            أنثى
                        @else
                            {{ $child->gender ?? '-' }}
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">تاريخ التشخيص</div>
                    <div class="detail-value">
                        {{ $child->diagnosis_date?->format('Y-m-d') ?? '-' }}
                    </div>
                </div>

            </div>

            <h2 class="section-title" style="margin-top: 30px;">
                👤 بيانات ولي الأمر
            </h2>

            <div class="details-grid">

                <div class="detail-item">
                    <div class="detail-label">اسم ولي الأمر</div>
                    <div class="detail-value">
                        @if($child->user)
                            <a class="parent-link"
                               href="{{ route('users.show', ['user' => $child->user->user_id]) }}">
                                {{ $child->user->name }}
                            </a>
                        @else
                            -
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">رقم هاتف ولي الأمر</div>
                    <div class="detail-value" dir="ltr" style="text-align: right;">
                        {{ $child->user?->phone ?: 'غير مسجل' }}
                    </div>
                </div>

            </div>

            <a href="{{ route('children.index') }}" class="back-btn">
                ← العودة إلى الأطفال
            </a>

        </div>

    </main>

</div>

</body>
</html>