<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بيانات الطفل</title>

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

        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            cursor: pointer;
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

    <h1>بيانات الطفل</h1>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ url('/child-info') }}">
        @csrf

        <label>اسم الطفل</label>
        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            required
        >

        <label>تاريخ الميلاد</label>
        <input
            type="date"
            name="birth_date"
            value="{{ old('birth_date') }}"
            required
        >

        <label>الجنس</label>
        <select name="gender" required>
            <option value="ذكر">ذكر</option>
            <option value="أنثى">أنثى</option>
        </select>

        <label>تاريخ تشخيص السكري</label>
        <input
            type="date"
            name="diagnosis_date"
            value="{{ old('diagnosis_date') }}"
        >

        <button type="submit">حفظ البيانات</button>
    </form>

</div>

</body>
</html>