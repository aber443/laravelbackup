<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            cursor: pointer;
        }

        a {
            display: block;
            margin-top: 15px;
        }

        .error {
            background: #ffe5e5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>إعادة تعيين كلمة المرور</h1>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ url('/forgot-password') }}">
        @csrf

        <label>البريد الإلكتروني</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
        >

        <label>كلمة المرور الجديدة</label>
        <input
            type="password"
            name="password"
            required
        >

        <label>تأكيد كلمة المرور الجديدة</label>
        <input
            type="password"
            name="password_confirmation"
            required
        >

        <button type="submit">
            تغيير كلمة المرور
        </button>
    </form>

    <a href="{{ url('/login') }}">
        العودة لتسجيل الدخول
    </a>

</div>

</body>
</html>