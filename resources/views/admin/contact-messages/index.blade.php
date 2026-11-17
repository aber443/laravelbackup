<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسائل المستخدمين | TinyGuide</title>
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

    <style>
        .contact-inbox {
            min-width: 0;
            padding: clamp(16px, 3vw, 30px);
            background: #F1F8F8;
            color: #2C4A5A;
            font: 16px/1.6 Arial, sans-serif;
        }

        .contact-inbox .inbox-card {
            padding: clamp(16px, 3vw, 24px);
            border: 1px solid #A8DEDC;
            border-radius: 18px;
            background: #fff;
        }

        .contact-inbox .inbox-card + .inbox-card,
        .contact-inbox .inbox-feedback + .inbox-card {
            margin-top: 16px;
        }

        .contact-inbox h1 {
            margin: 0 0 16px;
            font-size: 25px;
        }

        .contact-inbox .inbox-filter,
        .contact-inbox .inbox-pagination,
        .contact-inbox .inbox-dialog-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }

        .contact-inbox .inbox-filter select,
        .contact-inbox .inbox-button,
        .contact-inbox .inbox-reply-input {
            padding: 8px 12px;
            border: 1px solid #A8DEDC;
            border-radius: 9px;
            color: inherit;
            font: inherit;
        }

        .contact-inbox .inbox-filter select,
        .contact-inbox .inbox-reply-input {
            background: #fff;
        }

        .contact-inbox .inbox-button {
            display: inline-block;
            background: #E8F7F6;
            text-decoration: none;
            cursor: pointer;
        }

        .contact-inbox .inbox-button-primary {
            border-color: #399D9A;
            background: #64C7C4;
            color: #163C3D;
            font-weight: 700;
        }

        .contact-inbox .inbox-table-wrap {
            overflow-x: auto;
        }

        .contact-inbox table {
            width: 100%;
            min-width: 680px;
            border-collapse: collapse;
        }

        .contact-inbox th,
        .contact-inbox td {
            padding: 12px;
            border-bottom: 1px solid #E4EEEE;
            text-align: start;
            vertical-align: middle;
        }

        .contact-inbox th {
            background: #E8F7F6;
        }

        .contact-inbox .inbox-detail-button {
            padding: 6px 10px;
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: #2C4A5A;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .contact-inbox .inbox-detail-button:hover,
        .contact-inbox .inbox-detail-button:focus-visible {
            border-color: #A8DEDC;
            background: #E8F7F6;
            color: #196361;
        }

        .contact-inbox .inbox-detail-button[aria-expanded="true"] {
            border-color: #399D9A;
            background: #64C7C4;
            color: #163C3D;
        }

        .contact-inbox .inbox-dialog {
            box-sizing: border-box;
            width: min(600px, calc(100% - 32px));
            max-height: 80vh;
            margin: auto;
            padding: clamp(16px, 4vw, 28px);
            overflow-y: auto;
            border: 1.5px solid #399D9A;
            border-radius: 18px;
            background: #fff;
            color: #2C4A5A;
            box-shadow: 0 14px 44px rgb(44 74 90 / 18%);
        }

        .contact-inbox .inbox-dialog::backdrop {
            background: rgb(44 74 90 / 35%);
        }

        .contact-inbox .inbox-dialog h2 {
            margin: 0;
            font-size: 22px;
        }

        .contact-inbox .inbox-dialog h3 {
            margin: 0 0 8px;
            font-size: 16px;
        }

        .contact-inbox .inbox-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 16px;
            margin: 10px 0 16px;
            color: #526D78;
            font-size: 14px;
        }

        .contact-inbox .inbox-message-box {
            margin-top: 12px;
            padding: 14px;
            border: 1px solid #A8DEDC;
            border-radius: 12px;
            background: #F1F8F8;
        }

        .contact-inbox .inbox-message {
            margin: 0;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            line-height: 1.8;
        }

        .contact-inbox .inbox-reply-form {
            display: grid;
            gap: 8px;
        }

        .contact-inbox .inbox-reply-input {
            box-sizing: border-box;
            width: 100%;
            min-height: 100px;
            resize: vertical;
        }

        .contact-inbox .inbox-reply-form .inbox-button {
            justify-self: start;
        }

        .contact-inbox .inbox-dialog-actions {
            justify-content: flex-end;
            margin-top: 16px;
        }

        .contact-inbox .inbox-dialog-actions form {
            margin: 0;
        }

        .contact-inbox .inbox-pagination {
            justify-content: center;
            margin-top: 16px;
        }

        .contact-inbox .inbox-feedback {
            margin: 16px 0 0;
            padding: 10px 14px;
            border: 1px solid #A8DEDC;
            border-radius: 9px;
            background: #E8F7F6;
        }

        .contact-inbox .inbox-feedback-error {
            border-color: #F1D0D0;
            background: #FCE8E8;
            color: #9A3838;
        }

        .contact-inbox .inbox-reply-error {
            margin: 0;
            color: #9A3838;
        }
    </style>
</head>

<body>
<div class="dashboard">
    @include('partials.admin-sidebar')

    <main class="main-content contact-inbox">
        <header class="inbox-card">
            <h1>رسائل المستخدمين</h1>

            <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="inbox-filter">
                <label for="contact-type">نوع الرسالة</label>

                <select id="contact-type" name="type">
                    <option value="">جميع الأنواع</option>

                    @foreach ($typeLabels as $type => $label)
                        <option value="{{ $type }}" @selected($selectedType === $type)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="inbox-button">عرض الرسائل</button>
            </form>
        </header>

        @if (session('contact_success'))
            <p class="inbox-feedback" role="status">{{ session('contact_success') }}</p>
        @endif

        @if (session('contact_error'))
            <p class="inbox-feedback inbox-feedback-error" role="alert">{{ session('contact_error') }}</p>
        @endif

        <section class="inbox-card" aria-label="قائمة رسائل المستخدمين">
            <div class="inbox-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">المرسل</th>
                            <th scope="col">النوع</th>
                            <th scope="col">وقت الإرسال</th>
                            <th scope="col">الحالة</th>
                            <th scope="col">التفاصيل</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($messages as $item)
                            <tr>
                                <td><bdi>{{ $item->user?->name ?? 'حساب غير متاح' }}</bdi></td>
                                <td>{{ $typeLabels[$item->type] ?? 'أخرى' }}</td>
                                <td><bdi>{{ $item->created_at?->format('Y-m-d H:i') ?? '—' }}</bdi></td>
                                <td>{{ $statusLabels[$item->status] ?? 'غير محددة' }}</td>
                                <td>
                                    <button type="button" class="inbox-detail-button"
                                            aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="inbox-message-{{ $item->id }}">
                                        عرض التفاصيل
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">ما في رسائل لعرضها حاليًا.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($messages->hasPages())
                <nav class="inbox-pagination" aria-label="التنقل بين صفحات الرسائل">
                    @if ($messages->previousPageUrl())
                        <a class="inbox-button" rel="prev" href="{{ $messages->previousPageUrl() }}">
                            السابق
                        </a>
                    @endif

                    @if ($messages->nextPageUrl())
                        <a class="inbox-button" rel="next" href="{{ $messages->nextPageUrl() }}">
                            التالي
                        </a>
                    @endif
                </nav>
            @endif
        </section>

        @foreach ($messages as $item)
          @php
    $canReply = in_array(
        $item->type,
        ['inquiry', 'problem', 'suggestion'],
        true
    )
        && in_array($item->status, ['new', 'reviewed'], true)
        && $item->reply === null;

    $hasReplyError = $canReply
        && $errors->has('reply')
        && old('reply_message_id') === (string) $item->id;
@endphp

            <dialog class="inbox-dialog" id="inbox-message-{{ $item->id }}"
                    aria-labelledby="inbox-title-{{ $item->id }}"
                    @if ($hasReplyError) data-reopen="true" @endif>
                <h2 id="inbox-title-{{ $item->id }}">
                    {{ $typeLabels[$item->type] ?? 'تفاصيل الرسالة' }}
                </h2>

                <p class="inbox-meta">
                    <span>المرسل: <bdi>{{ $item->user?->name ?? 'حساب غير متاح' }}</bdi></span>
                    <span>وقت الإرسال: <bdi>{{ $item->created_at?->format('Y-m-d H:i') ?? '—' }}</bdi></span>
                    <span>الحالة: {{ $statusLabels[$item->status] ?? 'غير محددة' }}</span>
                </p>

                @if ($item->rating !== null)
                    <section class="inbox-message-box">
                        <h3>التقييم</h3>
                        <p class="inbox-message">{{ $item->rating }} من ٥</p>
                    </section>
                @endif

                @if (filled($item->message))
                    <section class="inbox-message-box">
                        <h3>{{ $item->type === 'rating' ? 'التعليق' : 'نص الرسالة' }}</h3>
                        <p class="inbox-message" dir="auto">{{ $item->message }}</p>
                    </section>
                @endif

                @if (filled($item->reply))
                    <section class="inbox-message-box">
                        <h3>رد الإدارة</h3>
                        <p class="inbox-message" dir="auto">{{ $item->reply }}</p>
                    </section>
                @elseif ($canReply)
                    <form class="inbox-message-box inbox-reply-form" method="POST"
                          action="{{ route('admin.contact-messages.reply', $item) }}">
                        @csrf
                        <input type="hidden" name="reply_message_id" value="{{ $item->id }}">

                        <label for="inbox-reply-{{ $item->id }}"><strong>رد الإدارة</strong></label>
                        <textarea id="inbox-reply-{{ $item->id }}" name="reply"
                                  class="inbox-reply-input" dir="auto" required maxlength="2000">{{ $hasReplyError ? old('reply') : '' }}</textarea>

                        @if ($hasReplyError)
                            <p class="inbox-reply-error" role="alert">{{ $errors->first('reply') }}</p>
                        @endif

                        <button type="submit" class="inbox-button inbox-button-primary">
                            حفظ الرد
                        </button>
                    </form>
                @endif

                <div class="inbox-dialog-actions">
                    @if ($item->status === 'new')
                        <form method="POST" action="{{ route('admin.contact-messages.review', $item) }}">
                            @csrf
                            @method('PATCH')

                            <button type="submit" class="inbox-button">تمت المراجعة</button>
                        </form>
                    @endif

                    <form method="dialog">
                        <button type="submit" class="inbox-button" autofocus>إغلاق</button>
                    </form>
                </div>
            </dialog>
        @endforeach
    </main>
</div>

<script>
    document.querySelectorAll('.contact-inbox .inbox-detail-button').forEach((button) => {
        const dialog = document.getElementById(button.getAttribute('aria-controls'));

        function openDialog() {
            dialog.showModal();
            button.setAttribute('aria-expanded', 'true');
        }

        button.addEventListener('click', openDialog);

        dialog.addEventListener('close', () => {
            button.setAttribute('aria-expanded', 'false');
            button.focus();
        });

        if (dialog.dataset.reopen === 'true') {
            openDialog();
        }
    });
</script>
</body>
</html>