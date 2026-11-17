<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إشعارات أولياء الأمور | TinyGuide</title>
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
    <style>
        body { color: #2C4A5A; }
        .tg-notification-page { box-sizing: border-box; }
        .tg-notification-page .notice-layout { display: grid; gap: 16px; max-width: 1100px; margin: auto; }
        .tg-notification-page .notice-panel { min-width: 0; background: #fff; border: 1.5px solid #86B6B5; border-radius: 16px; padding: clamp(14px, 3vw, 24px); }
        .tg-notification-page .notice-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; }
        .tg-notification-page h1, .tg-notification-page h2, .tg-notification-page h3 { margin: 0; }
        .tg-notification-page h1 { font-size: clamp(22px, 3vw, 28px); }
        .tg-notification-page h2 { font-size: 21px; margin-block-end: 12px; }
        .tg-notification-page h3 { font-size: 17px; overflow-wrap: anywhere; }
        .tg-notification-page .note { margin: 4px 0 0; color: #526D78; }
        .tg-notification-page .notice-actions { display: flex; flex-wrap: wrap; gap: 8px; }
        .tg-notification-page .notice-button { display: inline-flex; align-items: center; border: 1px solid #86B6B5; border-radius: 9px; padding: 8px 12px; background: #F2FAF9; color: #225956; text-decoration: none; font: inherit; font-weight: 700; cursor: pointer; }
        .tg-notification-page .notice-primary { border-color: #236D6A; background: #287B77; color: #fff; }
        .tg-notification-page #send:target, .tg-notification-page #sent:target { border-color: #287B77; }
        .tg-notification-page .notice-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .tg-notification-page .notice-field { display: grid; gap: 5px; min-width: 0; }
        .tg-notification-page .notice-field[hidden] { display: none; }
        .tg-notification-page .notice-wide { grid-column: 1 / -1; }
        .tg-notification-page :is(input, textarea, select) { box-sizing: border-box; width: 100%; border: 1px solid #86B6B5; border-radius: 9px; padding: 8px 10px; background: #FAFDFD; color: inherit; font: inherit; }
        .tg-notification-page textarea { resize: vertical; min-height: 92px; }
        .tg-notification-page :focus-visible { outline: 2px solid #207875; outline-offset: 3px; }
        .tg-notification-page .notice-list { display: grid; gap: 9px; }
        .tg-notification-page .notice-item { padding: 12px 14px; border: 1px solid #9AC2C0; border-radius: 10px; background: #F7FBFB; }
        .tg-notification-page .notice-meta { display: flex; gap: 7px 14px; flex-wrap: wrap; margin-block: 4px; font-size: 14px; color: #526D78; }
        .tg-notification-page details summary { color: #225956; font-weight: 700; cursor: pointer; }
        .tg-notification-page .notice-body { margin: 8px 0 0; white-space: pre-wrap; overflow-wrap: anywhere; }
        .tg-notification-page .notice-status { margin: 0; border: 1px solid #86B6B5; border-radius: 9px; padding: 8px 12px; background: #F0FAF5; }
        .tg-notification-page .notice-error { color: #8C3038; border-color: #E8A8AD; background: #FFF6F6; }
        @media (max-width: 600px) { .tg-notification-page .notice-form { grid-template-columns: minmax(0, 1fr); } }

        .tg-notification-page .notice-compose { max-width: 700px; width: 100%; margin-inline: auto; box-sizing: border-box; }
        .tg-notification-page .notice-search { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin: 15px 0; }
        .tg-notification-page .notice-search input { width: min(100%, 310px); }
        .tg-notification-page .notice-day { color: #225956; font-size: 15px; font-weight: 700; margin: 18px 0 5px; }
        .tg-notification-page .notice-entry { border-bottom: 1px solid #B0D3D2; }
        .tg-notification-page .notice-entry summary { display: flex; align-items: center; gap: 10px; padding: 11px 2px; list-style: none; overflow-wrap: anywhere; }
        .tg-notification-page .notice-entry summary::-webkit-details-marker { display: none; }
        .tg-notification-page .notice-entry summary::before { content: ''; background: #287B77; width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .tg-notification-page .notice-entry summary::after { content: '⌄'; margin-inline-start: auto; }
        .tg-notification-page .notice-entry[open] summary::after { transform: rotate(180deg); }
        .tg-notification-page .notice-entry .notice-item { margin: 0 17px 10px; }
        .tg-notification-page .notice-pagination { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 8px; margin-top: 16px; }
        .tg-notification-page .notice-results { list-style: none; display: grid; gap: 5px; margin: 0; padding: 0; }
        .tg-notification-page .notice-results button { width: 100%; text-align: start; justify-content: space-between; gap: 8px; }
    </style>
</head>
<body>
<div class="dashboard">
    @include('partials.admin-sidebar')
    <main class="main-content tg-notification-page">
        <div class="notice-layout">
            @if (session('success'))
                <p class="notice-status" role="status">{{ session('success') }}</p>
            @endif
            @if (session('error'))
                <p class="notice-status notice-error" role="alert">{{ session('error') }}</p>
            @endif
            @if ($errors->any())
                <ul class="notice-status notice-error" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <section class="notice-panel notice-compose" aria-labelledby="send-title">
                <header class="notice-header">
                    <h1 id="send-title">إضافة إشعار</h1>
                    <a class="notice-button" href="{{ route('notifications.index') }}">رجوع إلى السجل</a>
                </header>
                <form class="notice-form" style="margin-top: 14px" action="{{ route('notifications.send') }}" method="POST">
                    @csrf
                    <div class="notice-field notice-wide">
                        <label for="notice-title">العنوان</label>
                        <input id="notice-title" name="title" maxlength="150" value="{{ old('title') }}" required>
                    </div>
                    <div class="notice-field notice-wide">
                        <label for="notice-message">نص الإشعار</label>
                        <textarea id="notice-message" name="message" rows="4" maxlength="1000" required>{{ old('message') }}</textarea>
                    </div>
                    <div class="notice-field notice-wide">
                        <label for="notice-recipient">المستلمون</label>
                        <select id="notice-recipient" name="recipient_type">
                            <option value="all" @selected(old('recipient_type', 'all') === 'all')>جميع أولياء الأمور</option>
                            <option value="specific" @selected(old('recipient_type') === 'specific')>ولي أمر محدد</option>
                        </select>
                    </div>
                    <div class="notice-field notice-wide" id="notice-parent-field" hidden>
                        <label for="notice-parent">اسم ولي الأمر</label>
                        <input id="notice-parent" type="search" maxlength="100" autocomplete="off"
                               placeholder="اكتبي حرفين من الاسم" value="{{ $selectedParent?->name }}"
                               data-url="{{ route('notifications.recipients.search') }}">
                        <input type="hidden" name="recipient_user_id" id="notice-parent-id" value="{{ $selectedParent?->user_id }}">
                        <p class="note" id="notice-parent-status" aria-live="polite"></p>
                        <ul class="notice-results" id="notice-parent-results"></ul>
                    </div>
                    <div class="notice-actions notice-wide">
                        <button class="notice-button notice-primary" type="submit">إرسال الإشعار</button>
                    </div>
                </form>
            </section>

        </div>
    </main>
</div>
<script>
    (() => {
        const form = document.querySelector('.notice-form');
        const scope = document.getElementById('notice-recipient');
        const group = document.getElementById('notice-parent-field');
        const name = document.getElementById('notice-parent');
        const id = document.getElementById('notice-parent-id');
        const status = document.getElementById('notice-parent-status');
        const results = document.getElementById('notice-parent-results');
        let timer;
        let pending;

        function syncScope() {
            const specific = scope.value === 'specific';
            group.hidden = !specific;
            name.disabled = id.disabled = !specific;
            name.required = specific;
            name.setCustomValidity('');

            if (!specific) {
                clearTimeout(timer);
                pending?.abort();
                name.value = id.value = status.textContent = '';
                results.replaceChildren();
            }
        }

        scope.addEventListener('change', syncScope);
        name.addEventListener('input', () => {
            clearTimeout(timer);
            pending?.abort();
            id.value = status.textContent = '';
            results.replaceChildren();
            name.setCustomValidity('');
            const query = name.value.trim();
            if (query.length < 2) return;

            timer = setTimeout(async () => {
                const request = new AbortController();
                pending = request;
                status.textContent = 'جارٍ البحث…';

                try {
                    const url = new URL(name.dataset.url, location.href);
                    url.searchParams.set('q', query);
                    const response = await fetch(url, {
                        headers: { Accept: 'application/json' },
                        signal: request.signal,
                    });
                    if (!response.ok) throw new Error('Search failed');
                    const parents = await response.json();
                    if (request.signal.aborted) return;
                    status.textContent = parents.length ? '' : 'لا يوجد اسم مطابق.';
                    for (const parent of parents) {
                        const item = document.createElement('li');
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'notice-button';
                        button.textContent = parent.name + ' · حساب ' + parent.user_id;
                        button.addEventListener('click', () => {
                            name.value = parent.name;
                            id.value = parent.user_id;
                            name.setCustomValidity('');
                            status.textContent = 'تم اختيار ' + parent.name;
                            results.replaceChildren();
                            name.focus();
                        });
                        item.append(button);
                        results.append(item);
                    }
                } catch {
                    if (!request.signal.aborted) status.textContent = 'تعذّر البحث. جرّبي مرة أخرى.';
                }
            }, 250);
        });

        form.addEventListener('submit', (event) => {
            if (scope.value === 'specific' && !id.value) {
                event.preventDefault();
                name.setCustomValidity('اختاري اسم ولي الأمر من النتائج.');
                name.reportValidity();
            }
        });
        syncScope();
        if (id.value) status.textContent = 'تم اختيار ' + name.value;
    })();
</script>
</body>
</html>
