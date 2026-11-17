<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>الإعدادات | TinyGuide</title>
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
    <style>
        @font-face {
            font-family: 'Tajawal';
            src: url("{{ asset('fonts/Tajawal-Regular.ttf') }}") format('truetype');
            font-weight: 400;
            font-display: swap;
        }

        @if (is_file(public_path('fonts/Tajawal-Bold.ttf')))
            @font-face {
                font-family: 'Tajawal';
                src: url("{{ asset('fonts/Tajawal-Bold.ttf') }}") format('truetype');
                font-weight: 700;
                font-display: swap;
            }
        @endif

        body.tg-settings-page {
            margin: 0;
            min-width: 320px;
            background: #F1F8F8;
            font-family: 'Tajawal', Arial, sans-serif;
            color: #2C4A5A;
        }

        /* استخدام تخطيط الداشبورد الأصلي حتى لا يتكرر هامش القائمة الجانبية */
        .dashboard.tg-settings-layout {
            display: block;
            min-height: 100vh;
            width: 100%;
        }

        .tg-settings-layout > .tg-settings-content {
            box-sizing: border-box;
            width: calc(100% - 260px);
            max-width: none;
            min-width: 0;
            margin: 0 260px 0 0;
            padding: clamp(18px, 3vw, 38px);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            direction: rtl;
            text-align: right;
            color: #2C4A5A;
            font-family: 'Tajawal', Arial, sans-serif;
            overflow-x: hidden;
        }

        .tg-settings-content h1,
        .tg-settings-content h2,
        .tg-settings-content p {
            margin: 0;
        }

        .tg-settings-content h1 { font-size: clamp(24px, 3vw, 32px); }
        .tg-settings-content h2 { font-size: 19px; }

        .tg-settings-content,
        .tg-settings-content :is(h1, h2, label, p, summary, button) {
            text-align: right;
        }

        .tg-settings-content .tg-settings-panel-title {
            justify-content: flex-start;
            direction: rtl;
        }

        .tg-settings-content .tg-settings-button {
            margin-inline-start: 0;
        }

        .tg-settings-content .tg-settings-field input[type="email"] {
            direction: ltr;
            text-align: left;
        }

        .tg-settings-card {
            box-sizing: border-box;
            width: min(920px, 100%);
            max-width: 920px;
            margin: 10px auto 36px;
            overflow: hidden;
            border: 1px solid #A8DEDC;
            border-radius: 18px;
            background: #fff;
        }

        .tg-settings-title {
            padding: 17px clamp(16px, 3vw, 24px);
            border-block-end: 1px solid #A8DEDC;
        }

        /* صفحة واحدة بعمود رأسي: الحساب أولاً ثم كلمة المرور */
        .tg-settings-sections {
            display: flex;
            flex-direction: column;
            gap: 0;
            padding: 0;
            align-items: stretch;
        }

        .tg-settings-panel {
            box-sizing: border-box;
            width: 100%;
            min-width: 0;
            padding: 24px clamp(18px, 3vw, 32px);
            border: 0;
            border-radius: 0;
            background: #FFFFFF;
        }

        .tg-settings-panel + .tg-settings-panel {
            border-block-start: 1px solid #D5EAEA;
            background: #FFFFFF;
        }

        .tg-settings-panel-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-block-end: 18px;
        }

        .tg-settings-icon {
            display: grid;
            flex: 0 0 36px;
            width: 36px;
            height: 36px;
            place-items: center;
            border-radius: 9px;
            background: #E4F5F4;
            color: #319FA1;
        }

        .tg-settings-icon svg { width: 19px; height: 19px; }

        .tg-settings-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            gap: 12px 14px;
            margin-block-start: 13px;
        }

        .tg-settings-actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px 12px;
            grid-column: 1 / -1;
            padding-block-start: 2px;
        }

        .tg-settings-field {
            display: grid;
            width: 100%;
            min-width: 0;
            gap: 7px;
            text-align: right;
        }

        .tg-settings-field label {
            font-weight: 700;
        }

        .tg-settings-field input {
            box-sizing: border-box;
            width: 100%;
            min-height: 44px;
            padding: 8px 11px;
            border: 1px solid #C5E2E2;
            border-radius: 11px;
            background: #F7FBFB;
            color: inherit;
            font: inherit;
            text-align: right;
            direction: rtl;
        }

        .tg-settings-field input[aria-invalid="true"] {
            border-color: #B55A60;
        }

        .tg-password-control {
            position: relative;
        }

        .tg-password-control input {
            padding-inline-end: 48px;
        }

        .tg-password-control input::-ms-reveal {
            display: none;
        }

        .tg-password-toggle {
            position: absolute;
            inset-block: 3px;
            inset-inline-end: 3px;
            display: grid;
            width: 40px;
            place-items: center;
            padding: 0;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: #319FA1;
            cursor: pointer;
        }

        .tg-password-toggle[hidden],
        .tg-password-toggle .tg-eye-slash { display: none; }

        .tg-password-toggle[data-visible="true"] .tg-eye-slash { display: block; }
        .tg-password-toggle:hover { background: #E6F4F2; }
        .tg-password-toggle svg { width: 19px; height: 19px; }

        .tg-settings-hint,
        .tg-settings-error {
            font-size: 13px;
        }

        .tg-settings-hint { color: #486573; }
        .tg-settings-error { color: #A13944; }

        .tg-settings-button {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            min-height: 40px;
            padding: 7px 14px;
            border: 1px solid #A8DEDC;
            border-radius: 8px;
            background: #E8F7F6;
            color: #244D55;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .tg-settings-button:hover {
            background: #D5EFEC;
        }

        .tg-settings-disclosure > summary:focus-visible,
        .tg-settings-preference input:focus-visible,
        .tg-settings-button:focus-visible,
        .tg-password-toggle:focus-visible,
        .tg-settings-field input:focus-visible {
            outline: 2px solid #46B7B9;
            outline-offset: 2px;
        }

        .tg-settings-save {
            border-color: #46B7B9;
            background: #46B7B9;
            color: #fff;
        }

        .tg-settings-save:hover {
            border-color: #329FA2;
            background: #329FA2;
        }

        .tg-settings-content .tg-settings-success {
            margin: 16px clamp(16px, 3vw, 28px) 0;
            padding: 9px 12px;
            border-radius: 8px;
            background: #E5F6ED;
            color: #265B44;
        }

        .tg-settings-strength {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px 8px;
            margin-block-start: 3px;
            color: #486573;
            font-size: 13px;
        }

        .tg-settings-strength[hidden] { display: none; }

        .tg-settings-strength meter {
            width: 100%;
            height: 9px;
            accent-color: #46B7B9;
        }

        .tg-settings-form.tg-settings-account,
        .tg-settings-disclosure {
            max-inline-size: 100%;
        }

        .tg-settings-form.tg-settings-account {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            row-gap: 15px;
            width: 100%;
            max-inline-size: none;
            margin: 0;
        }

        .tg-settings-disclosure .tg-settings-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            width: 100%;
            max-inline-size: none;
            margin-inline: 0;
        }

        .tg-settings-disclosure .tg-settings-form > .tg-settings-field:first-of-type {
            grid-column: auto;
        }

        .tg-settings-verify[hidden] { display: none; }

        .tg-settings-disclosure > summary {
            padding: 8px 11px;
            list-style: none;
            border: 1px solid #C5E2E2;
            border-radius: 12px;
            background: #F7FBFB;
            cursor: pointer;
        }

        .tg-settings-disclosure > summary::-webkit-details-marker { display: none; }
        .tg-settings-disclosure > summary:hover { background: #EDF7F6; }

        .tg-settings-disclosure h2 {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .tg-settings-disclosure .tg-settings-chevron {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            margin-inline-start: auto;
            color: #319FA1;
        }

        .tg-settings-disclosure[open] .tg-settings-chevron { transform: rotate(180deg); }

        @media (max-width: 980px) {
            .tg-settings-panel { padding: 20px; }
        }

        @media (max-width: 620px) {
            .tg-settings-form,
            .tg-settings-form.tg-settings-account,
            .tg-settings-disclosure .tg-settings-form { grid-template-columns: minmax(0, 1fr); }
            .tg-settings-panel { padding: 17px 15px; }
            .tg-settings-title { padding: 16px 18px; }
        }

        @media (max-width: 760px) {
            .dashboard.tg-settings-layout {
                display: block;
            }

            .tg-settings-layout > .tg-settings-content {
                width: 100%;
                margin: 0;
                padding: 16px 12px;
            }

            .tg-settings-card {
                width: 100%;
                margin-block: 8px 24px;
            }
        }
    </style>
</head>
<body class="tg-settings-page">
<div class="dashboard tg-settings-layout">
    @include('partials.admin-sidebar')

    <main class="main-content tg-settings-content">
        <article class="tg-settings-card" aria-labelledby="tg-settings-title">
            <header class="tg-settings-title">
                <h1 id="tg-settings-title">الإعدادات</h1>
            </header>

            @if (session('success'))
                <p class="tg-settings-success" role="status">{{ session('success') }}</p>
            @endif

            <div class="tg-settings-sections">
                <section class="tg-settings-panel" aria-labelledby="tg-settings-account-title">
                        <div class="tg-settings-panel-title">
                            <span class="tg-settings-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" focusable="false">
                                    <circle cx="12" cy="8" r="3.5"/>
                                    <path d="M5 20v-1a7 7 0 0 1 14 0v1"/>
                                </svg>
                            </span>
                            <h2 id="tg-settings-account-title">إعدادات الحساب</h2>
                        </div>

                        <form class="tg-settings-form tg-settings-account" method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="tg-settings-field">
                                <label for="settings-name">الاسم الكامل</label>
                                <input id="settings-name" name="name" type="text" maxlength="255" autocomplete="name"
                                       value="{{ old('name', $user->name) }}" required
                                       @error('name') aria-invalid="true" aria-describedby="settings-name-error" @enderror>
                                @error('name')
                                    <p class="tg-settings-error" id="settings-name-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="tg-settings-field">
                                <label for="settings-email">البريد الإلكتروني</label>
                                <input id="settings-email" name="email" type="email" maxlength="255" autocomplete="email"
                                       dir="ltr" value="{{ old('email', $user->email) }}" data-saved-email="{{ $user->email }}" required
                                       @error('email') aria-invalid="true" aria-describedby="settings-email-error" @enderror>
                                @error('email')
                                    <p class="tg-settings-error" id="settings-email-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="tg-settings-field tg-settings-verify" id="settings-email-verification"
                                 data-has-error="{{ $errors->has('current_password') ? '1' : '0' }}">
                                <label for="settings-current-password">كلمة المرور الحالية</label>
                                <div class="tg-password-control">
                                    <input id="settings-current-password" name="current_password" type="password"
                                           autocomplete="current-password" aria-describedby="settings-password-hint{{ $errors->has('current_password') ? ' settings-password-error' : '' }}"
                                           @error('current_password') aria-invalid="true" @enderror>
                                    <button type="button" class="tg-password-toggle" data-password-toggle
                                            aria-controls="settings-current-password" aria-label="إظهار كلمة المرور"
                                            title="إظهار كلمة المرور" hidden>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                            <path d="M2.5 12c2.5-4 5.5-6 9.5-6s7 2 9.5 6c-2.5 4-5.5 6-9.5 6s-7-2-9.5-6z"/>
                                            <circle cx="12" cy="12" r="2.5"/>
                                            <path class="tg-eye-slash" d="m4 4 16 16"/>
                                        </svg>
                                    </button>
                                </div>
                                <p class="tg-settings-hint" id="settings-password-hint">تُطلب عند تغيير البريد الإلكتروني.</p>
                                @error('current_password')
                                    <p class="tg-settings-error" id="settings-password-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="tg-settings-actions">
                                <button class="tg-settings-button tg-settings-save" type="submit">حفظ بيانات الحساب</button>
                            </div>
                        </form>
                </section>
                <section class="tg-settings-panel" aria-labelledby="tg-settings-security-title">
                        <details class="tg-settings-disclosure" open>
                            <summary>
                                <h2 id="tg-settings-security-title">
                                    <span class="tg-settings-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                             stroke-linecap="round" stroke-linejoin="round" focusable="false">
                                            <rect x="5" y="10" width="14" height="10" rx="2"/>
                                            <path d="M8 10V7a4 4 0 0 1 8 0v3m-4 4v2"/>
                                        </svg>
                                    </span>
                                    تغيير كلمة المرور
                                    <svg class="tg-settings-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         aria-hidden="true" focusable="false">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </h2>
                            </summary>

                            @php
                                $passwordFields = [
                                    ['name' => 'current_password', 'label' => 'كلمة المرور الحالية', 'autocomplete' => 'current-password'],
                                    ['name' => 'password', 'label' => 'كلمة المرور الجديدة', 'autocomplete' => 'new-password'],
                                    ['name' => 'password_confirmation', 'label' => 'تأكيد كلمة المرور الجديدة', 'autocomplete' => 'new-password'],
                                ];
                            @endphp

                            <form class="tg-settings-form" method="POST" action="{{ url('/change-password') }}">
                                @csrf
                                <input type="hidden" name="settings_form" value="change_password">

                                @foreach ($passwordFields as $field)
                                    @php
                                        $id = 'security-' . $field['name'];
                                        $descriptionIds = $field['name'] === 'password' ? ['security-password-hint', 'settings-strength-value'] : [];
                                        if ($errors->getBag('settingsPassword')->has($field['name'])) {
                                            $descriptionIds[] = $id . '-error';
                                        }
                                    @endphp

                                    <div class="tg-settings-field">
                                        <label for="{{ $id }}">{{ $field['label'] }}</label>
                                        <div class="tg-password-control">
                                            <input id="{{ $id }}" name="{{ $field['name'] }}" type="password"
                                                   autocomplete="{{ $field['autocomplete'] }}" required
                                                   @if ($field['name'] !== 'current_password') minlength="6" @endif
                                                   @if ($descriptionIds) aria-describedby="{{ implode(' ', $descriptionIds) }}" @endif
                                                   @error($field['name'], 'settingsPassword') aria-invalid="true" @enderror>
                                            <button type="button" class="tg-password-toggle" data-password-toggle
                                                    aria-controls="{{ $id }}" aria-label="إظهار كلمة المرور"
                                                    title="إظهار كلمة المرور" hidden>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                                    <path d="M2.5 12c2.5-4 5.5-6 9.5-6s7 2 9.5 6c-2.5 4-5.5 6-9.5 6s-7-2-9.5-6z"/>
                                                    <circle cx="12" cy="12" r="2.5"/>
                                                    <path class="tg-eye-slash" d="m4 4 16 16"/>
                                                </svg>
                                            </button>
                                        </div>

                                        @if ($field['name'] === 'password')
                                            <p class="tg-settings-hint" id="security-password-hint">٦ أحرف على الأقل.</p>
                                            <div class="tg-settings-strength" id="settings-strength" hidden>
                                                <label for="settings-strength-meter">قوة كلمة المرور</label>
                                                <span id="settings-strength-value" aria-live="polite"></span>
                                                <meter id="settings-strength-meter" min="0" max="3" low="1" high="2" optimum="3" value="0"></meter>
                                            </div>
                                        @endif

                                        @error($field['name'], 'settingsPassword')
                                            <p class="tg-settings-error" id="{{ $id }}-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach

                                <div class="tg-settings-actions">
                                    <button class="tg-settings-button tg-settings-save" type="submit">تحديث كلمة المرور</button>
                                </div>
                            </form>
                        </details>
                </section>
            </div>
        </article>
    </main>
</div>
<script>
    for (const button of document.querySelectorAll('[data-password-toggle]')) {
        const input = document.getElementById(button.getAttribute('aria-controls'));

        if (!input) continue;

        button.hidden = false;
        button.addEventListener('click', () => {
            const visible = input.type === 'password';
            const label = visible ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور';

            input.type = visible ? 'text' : 'password';
            button.dataset.visible = String(visible);
            button.setAttribute('aria-label', label);
            button.title = label;
        });
    }

    const email = document.getElementById('settings-email');
    const verification = document.getElementById('settings-email-verification');
    const currentPassword = document.getElementById('settings-current-password');

    function updateEmailVerification() {
        const emailChanged = email.value.trim() !== email.dataset.savedEmail;
        verification.hidden = !emailChanged && verification.dataset.hasError !== '1';
        currentPassword.disabled = verification.hidden;
        currentPassword.required = emailChanged;
    }

    email.addEventListener('input', updateEmailVerification);
    email.addEventListener('change', updateEmailVerification);
    window.addEventListener('pageshow', updateEmailVerification);
    updateEmailVerification();

    const newPassword = document.getElementById('security-password');
    const strength = document.getElementById('settings-strength');
    const meter = document.getElementById('settings-strength-meter');
    const strengthValue = document.getElementById('settings-strength-value');

    function updatePasswordStrength() {
        const password = newPassword.value;
        const characters = Array.from(password);
        const variety = new Set(characters).size;
        const types = [
            /\p{L}/u.test(password),
            /\p{N}/u.test(password),
            /[^\p{L}\p{N}\s]/u.test(password),
        ].filter(Boolean).length;

        let score = 1;
        if (characters.length >= 14 && variety >= 8 && (types >= 2 || characters.length >= 18)) {
            score = 3;
        } else if (characters.length >= 10 && variety >= 5) {
            score = 2;
        }

        strength.hidden = password.length === 0;
        meter.value = password.length ? score : 0;
        const label = password.length ? ['', 'ضعيفة', 'متوسطة', 'قوية'][score] : '';
        if (strengthValue.textContent !== label) strengthValue.textContent = label;
    }

    newPassword.addEventListener('input', updatePasswordStrength);
    updatePasswordStrength();
</script>
</body>
</html>
