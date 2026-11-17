<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الإدارة - TinyGuide</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: #f1f8f8;
            color: #2c4a5a;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 460px;
            background: white;
            padding: 34px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(44, 74, 90, 0.08);
            border: 2px solid #64c7c4;
        }

        .logo {
            text-align: center;
            margin-bottom: 26px;
        }

        .logo h2 {
            margin: 0;
            color: #64c7c4;
            font-size: 28px;
        }

        .logo p {
            margin: 5px 0 0;
            color: #78909a;
        }

        h1 {
            margin: 0 0 8px;
            text-align: center;
            font-size: 25px;
        }

        .subtitle {
            text-align: center;
            color: #78909a;
            margin-bottom: 25px;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #cfe0e0;
            border-radius: 10px;
            font: inherit;
            color: #2c4a5a;
            background: white;
        }

        input:focus {
            outline: none;
            border-color: #64c7c4;
            box-shadow: 0 0 0 3px rgba(100, 199, 196, 0.15);
        }

        /* حقل كلمة المرور وزر العين */
        .password-wrapper {
            position: relative;
            margin-bottom: 18px;
        }

        .password-wrapper input {
            margin-bottom: 0;
            padding-left: 45px;
        }

        .toggle-password {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: auto;
            padding: 5px;
            border: none;
            background: transparent;
            color: #78909a;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            background: transparent;
            color: #64c7c4;
        }

        .toggle-password svg {
            width: 21px;
            height: 21px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .toggle-password .eye-off {
            display: none;
        }

        button.submit-button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 10px;
            background: #64c7c4;
            color: white;
            font: inherit;
            font-weight: bold;
            cursor: pointer;
        }

        button.submit-button:hover {
            background: #4eb5b2;
        }

        .error {
            background: #fff0f0;
            color: #a52828;
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: 10px;
        }

        .error div + div {
            margin-top: 5px;
        }

        @media (max-width: 500px) {
            .container {
                padding: 24px 18px;
            }

            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="logo">
        <h2>TinyGuide</h2>
        <p>المرشد الصغير</p>
    </div>

    <h1>تسجيل دخول الإدارة</h1>
    <p class="subtitle">الدخول مخصص لحساب المشرف فقط.</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <label for="email">البريد الإلكتروني</label>

        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            autocomplete="email"
            required
        >

        <label for="password">كلمة المرور</label>

        <div class="password-wrapper">

            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button
                type="button"
                class="toggle-password"
                id="togglePassword"
                aria-label="إظهار كلمة المرور"
                title="إظهار كلمة المرور"
            >
                <!-- أيقونة العين -->
                <svg class="eye-on" viewBox="0 0 24 24">
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>

                <!-- أيقونة إخفاء العين -->
                <svg class="eye-off" viewBox="0 0 24 24">
                    <path d="M3 3l18 18"/>
                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                    <path d="M9.9 5.2A10.5 10.5 0 0 1 12 5c6.4 0 10 7 10 7a16 16 0 0 1-3.1 3.8"/>
                    <path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7c1.2 0 2.3-.2 3.3-.6"/>
                </svg>
            </button>

        </div>

        <button type="submit" class="submit-button">
            دخول لوحة الإدارة
        </button>
    </form>

</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    const eyeOn = togglePassword.querySelector('.eye-on');
    const eyeOff = togglePassword.querySelector('.eye-off');

    togglePassword.addEventListener('click', function () {

        if (passwordInput.type === 'password') {

            passwordInput.type = 'text';

            eyeOn.style.display = 'none';
            eyeOff.style.display = 'block';

            togglePassword.setAttribute('aria-label', 'إخفاء كلمة المرور');
            togglePassword.setAttribute('title', 'إخفاء كلمة المرور');

        } else {

            passwordInput.type = 'password';

            eyeOn.style.display = 'block';
            eyeOff.style.display = 'none';

            togglePassword.setAttribute('aria-label', 'إظهار كلمة المرور');
            togglePassword.setAttribute('title', 'إظهار كلمة المرور');
        }

    });
</script>

</body>
</html>