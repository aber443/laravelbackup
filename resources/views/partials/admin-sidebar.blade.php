
@php
    $menuItems = [
        [
            'label' => 'الرئيسية',
            'icon' => '🏠',
            'url' => url('/dashboard'),
            'active' => request()->is('dashboard'),
        ],
        [
            'label' => 'إجمالي الحسابات',
            'icon' => '👥',
            'url' => route('users.index'),
            'active' => request()->routeIs('users.*'),
        ],
        [
            'label' => 'سجلات النظام',
            'icon' => '📋',
            'url' => route('system-logs.index'),
            'active' => request()->routeIs('system-logs.*'),
        ],
        [
            'label' => 'قوائم التحقق',
            'icon' => '✅',
            'url' => route('admin.checklists.index'),
            'active' => request()->routeIs('admin.checklists.*'),
        ],
        [
            'label' => 'إدارة الإشعارات',
            'icon' => '🔔',
            'url' => route('notifications.index'),
            'active' => request()->routeIs('notifications.*'),
        ],
        [
            'label' => 'رسائل المستخدمين',
            'icon' => '💬',
            'url' => route('admin.contact-messages.index'),
            'active' => request()->routeIs('admin.contact-messages.*'),
        ],
        [
            'label' => 'قياس رضا المستخدمين',
            'icon' => '⭐',
            'url' => route('admin.nps.index'),
            'active' => request()->routeIs('admin.nps.*'),
        ],
    ];
@endphp

<style>
    .sidebar.tg-admin-sidebar {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        overflow-y: auto;
        box-sizing: border-box;
    }

    .tg-admin-sidebar .logo {
        flex-shrink: 0;
        margin-bottom: 30px;
        text-align: center;
    }

    .tg-admin-sidebar .logo-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        margin-bottom: 5px;
    }

    .tg-admin-sidebar .logo-drop {
        width: 35px;
        height: 35px;
        flex-shrink: 0;
    }

    .tg-admin-sidebar .logo h2 {
        margin: 0;
        color: #64C7C4;
        font-size: 25px;
    }

    .tg-admin-sidebar .logo p {
        margin: 0;
        color: #8AA1AA;
        font-size: 14px;
    }

    .tg-admin-sidebar .menu {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 8px;
    }

    .sidebar.tg-admin-sidebar .menu a {
        position: relative;

        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        justify-content: flex-start !important;

        gap: 8px;
        width: 100%;
        height: 48px !important;
        min-height: 48px !important;
        max-height: 48px !important;
        flex: 0 0 48px;

        padding: 8px 10px !important;
        box-sizing: border-box;

        border: 2px solid transparent;
        border-radius: 12px;

        color: #2C4A5A;
        text-decoration: none;
        text-align: right;
        white-space: nowrap;
    }

    .sidebar.tg-admin-sidebar .menu a:hover {
        border-color: #C9E7E5;
        background: #EAF8F8;
        color: #2C4A5A;
    }

    .sidebar.tg-admin-sidebar .menu a .tg-menu-label,
    .sidebar.tg-admin-sidebar .menu a:hover .tg-menu-label {
        display: inline-block;
        flex: 0 1 auto;
        min-width: 0;
        color: #2C4A5A;
        white-space: nowrap !important;
        font-size: 15px;
        line-height: 1.5;
    }

    .sidebar.tg-admin-sidebar .menu a:active,
    .sidebar.tg-admin-sidebar .menu a.active,
    .sidebar.tg-admin-sidebar .menu a.active:hover {
        border-color: #399D9A;
        background: #64C7C4;
        color: #2C4A5A;
        font-weight: 700;
    }

    .sidebar.tg-admin-sidebar .menu a:active::before,
    .sidebar.tg-admin-sidebar .menu a.active::before {
        position: absolute;
        inset-block: 10px;
        inset-inline-start: 4px;
        width: 3px;
        border-radius: 3px;
        background: #399D9A;
        pointer-events: none;
        content: "";
    }

    .sidebar.tg-admin-sidebar .menu a .tg-menu-icon {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        flex: 0 0 22px;
        width: 22px;
        min-width: 22px;
        line-height: 1;
        font-size: 18px;
        text-align: center;
    }

    .sidebar.tg-admin-sidebar .menu a:focus-visible {
        outline: 2px solid #399D9A;
        outline-offset: 2px;
    }
</style>

<aside class="sidebar tg-admin-sidebar">

    <div class="logo">
        <div class="logo-brand">

            <svg
                class="logo-drop"
                viewBox="0 0 48 48"
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
                focusable="false"
            >
                <path
                    d="M24 3 C20 10 9 22 9 31
                       C9 40 15.5 46 24 46
                       C32.5 46 39 40 39 31
                       C39 22 28 10 24 3Z"
                    fill="#64C7C4"
                />

                <path
                    d="M17 29 C17 24 20 19 23 15"
                    stroke="#FFFFFF"
                    stroke-width="3"
                    stroke-linecap="round"
                    fill="none"
                    opacity="0.85"
                />
            </svg>

            <h2 dir="ltr">TinyGuide</h2>
        </div>

        <p>المرشد الصغير</p>
    </div>

    <nav class="menu" aria-label="القائمة الرئيسية">

        @foreach ($menuItems as $item)

            <a
                href="{{ $item['url'] }}"
                @class(['active' => $item['active']])
                @if ($item['active']) aria-current="location" @endif
            >
                <span class="tg-menu-icon" aria-hidden="true">
                    {{ $item['icon'] }}
                </span>

                <span class="tg-menu-label">
                    {{ $item['label'] }}
                </span>
            </a>

        @endforeach

    </nav>

</aside>
