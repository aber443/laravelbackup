<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>لوحة إدارة النظام | TinyGuide</title>


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

        .tg-home :is(button, select, input, textarea) {
            font-family: inherit;
        }

        .tg-home {

            --tg-bg: #F1F8F8;

            --tg-white: #FFFFFF;

            --tg-ink: #2C4A5A;

            --tg-muted: #526D78;

            --tg-border: #86B6B5;
            --tg-primary: #48B9B8;
            --tg-primary-dark: #319F9F;

            --tg-soft: #EAF8F8;

            --tg-teal: #399D9A;

            --tg-green: #237457;

            --tg-red: #A94848;

            --tg-amber: #885C20;


            min-width: 0;

            min-height: 100vh;

            padding: 24px;

            background: var(--tg-bg);

            color: var(--tg-ink);

            font-family: 'Tajawal', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;

        }


        .tg-home *,

        .tg-home *::before,

        .tg-home *::after {

            box-sizing: border-box;

        }


        .tg-home h1,

        .tg-home h2,

        .tg-home p {

            margin: 0;

        }


        .tg-home a {

            color: inherit;

            text-decoration: none;

        }


        .tg-home button,

        .tg-home select {

            font: inherit;

        }


        .tg-home a:focus-visible,

        .tg-home button:focus-visible,

        .tg-home select:focus-visible {

            outline: 2px solid var(--tg-teal);

            outline-offset: 3px;

        }


        .tg-home .tg-card {

            min-width: 0;

            border: 1.5px solid var(--tg-border);

            border-radius: 15px;

            background: var(--tg-white);

        }


        .tg-home .tg-panel-head,
        .tg-home .tg-filter {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px 16px;
        }

        .tg-home .tg-panel-head { justify-content: space-between; }

        .tg-home .tg-top {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px 16px;
            padding: 11px 16px;
            margin-block-end: 16px;
            border-radius: 12px;
        }

        .tg-home .tg-top h1 { font-size: 19px; line-height: 1.35; }

        .tg-home .tg-top p,
        .tg-home .tg-header-account small {
            color: var(--tg-muted);
            font-size: 12px;
        }

        .tg-home .tg-header-actions,
        .tg-home .tg-header-account summary {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tg-home .tg-header-menu { position: relative; }
        .tg-home .tg-header-menu summary { cursor: pointer; list-style: none; }
        .tg-home .tg-header-menu summary::-webkit-details-marker { display: none; }
        .tg-home .tg-header-menu summary:focus-visible {
            outline: 2px solid var(--tg-teal);
            outline-offset: 3px;
        }

        .tg-home .tg-header-account {
            padding-inline-start: 8px;
            border-inline-start: 1px solid var(--tg-border);
        }

        .tg-home .tg-header-account strong,
        .tg-home .tg-header-account small { display: block; }
        .tg-home .tg-header-account strong { font-size: 13px; }

        .tg-home .tg-header-icon {
            position: relative;
            display: grid;
            width: 36px;
            height: 36px;
            place-items: center;
            border-radius: 50%;
            color: var(--tg-ink);
        }

        .tg-home .tg-header-avatar { background: #D5EFEA; color: #197B7E; }
        .tg-home .tg-header-icon svg { width: 22px; height: 22px; }
        .tg-home .tg-header-chevron { width: 14px; height: 14px; }

        .tg-home .tg-header-dot {
            position: absolute;
            inset-block-start: 4px;
            inset-inline-start: 5px;
            width: 8px;
            height: 8px;
            border: 1px solid #fff;
            border-radius: 50%;
            background: #DF5963;
        }

        .tg-home .tg-header-links {
            position: absolute;
            inset-block-start: calc(100% + 6px);
            inset-inline-end: 0;
            min-width: max-content;
            padding: 4px;
            border: 1px solid var(--tg-border);
            border-radius: 9px;
            background: #fff;
            box-shadow: 0 6px 18px #183d4b1a;
        }

        .tg-home .tg-header-bell .tg-header-links { inset-inline: 0 auto; }

        .tg-home .tg-header-links a {
            display: block;
            padding: 7px 10px;
            border-radius: 6px;
            color: var(--tg-ink);
            font-size: 13px;
            text-decoration: none;
        }

        .tg-home .tg-header-links a:hover,
        .tg-home .tg-header-links a:focus-visible { background: var(--tg-soft); }

        .tg-home h1 {

            font-size: clamp(23px, 3vw, 33px);

        }


        .tg-home h2 {

            font-size: 18px;

        }


        .tg-home .tg-muted {

            color: var(--tg-muted);

            font-size: 13px;

        }


        .tg-home .tg-button {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            min-height: 38px;

            padding: 6px 12px;

            border: 1px solid var(--tg-border);

            border-radius: 9px;

            background: var(--tg-white);

            color: var(--tg-ink);

            font-weight: 700;

            cursor: pointer;

        }


        .tg-home .tg-primary {

            background: var(--tg-soft);

            border-color: var(--tg-teal);

        }


        .tg-home .tg-button:hover,

        .tg-home a.tg-stat:hover {

            border-color: var(--tg-teal);

        }


        .tg-home .tg-grid {

            display: grid;

            gap: 14px;

            margin-bottom: 14px;

        }


        .tg-home .tg-stats {

            grid-template-columns: repeat(3, minmax(0, 1fr));

        }


        @media (max-width: 1100px) {

            .tg-home .tg-stats {

                grid-template-columns: repeat(2, minmax(0, 1fr));

            }

        }


        .tg-home .tg-charts {

            grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr);

        }


        .tg-home .tg-stat,

        .tg-home .tg-panel {

            padding: 16px;

        }


        .tg-home .tg-stat {

            display: flex;

            flex-direction: column;

            align-items: stretch;

            gap: 7px;

        }


        .tg-home .tg-stat-head {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            gap: 12px;
            width: 100%;
        }

        .tg-home .tg-stat-content {
            display: grid;
            justify-items: center;
            gap: 9px;
            min-width: 0;
        }

        .tg-home .tg-stat-icon {

            display: grid;

            place-items: center;

            flex: 0 0 34px;

            width: 34px;

            height: 34px;

            border: 1px solid var(--tg-border);

            border-radius: 9px;

            background: var(--tg-soft);

            color: var(--tg-teal);

        }


        .tg-home .tg-stat-icon svg {

            width: 18px;

            height: 18px;

        }


        .tg-home .tg-stat-value {
            display: block;
            width: auto;
            min-height: 0;
            margin: 0;
            color: var(--tg-ink);
            font-size: 32px;
            line-height: 1.2;
            font-weight: 700;
            text-align: center;
            font-variant-numeric: tabular-nums;
        }


        .tg-home .tg-stat-breakdown {

            display: flex;

            flex-wrap: wrap;

            gap: 4px 10px;

            color: var(--tg-muted);

            font-size: 13px;

        }


        .tg-home .tg-link {

            color: var(--tg-ink);

            font-size: 13px;

            font-weight: 700;

            text-decoration: underline;

            text-underline-offset: 3px;

        }


        .tg-home .tg-panel-head {

            align-items: flex-start;

            margin-bottom: 12px;

        }


        .tg-home .tg-filter select {

            min-height: 36px;

            padding: 4px 8px;

            border: 1px solid var(--tg-border);

            border-radius: 8px;

            background: var(--tg-white);

            color: var(--tg-ink);

        }


        .tg-home .tg-chart {

            min-height: 210px;

        }


        .tg-home .tg-chart svg {

            display: block;

            width: 100%;

            height: auto;

        }


        .tg-home .tg-empty {

            padding: 14px;

            border: 1px dashed var(--tg-border);

            border-radius: 10px;

            background: var(--tg-bg);

            color: var(--tg-muted);

            font-size: 13px;

        }


        .tg-home .tg-legend {

            display: flex;

            flex-wrap: wrap;

            gap: 6px 16px;

            margin-top: 8px;

            color: var(--tg-muted);

            font-size: 13px;

        }


        .tg-home .tg-legend span::before {

            display: inline-block;

            width: 10px;

            height: 10px;

            margin-inline-end: 5px;

            border-radius: 3px;

            background: var(--swatch);

            content: "";

        }


        .tg-home .tg-table-wrap {

            overflow-x: auto;

        }


        .tg-home table {

            width: 100%;

            border-collapse: collapse;

            text-align: start;

            font-size: 13px;

        }


        .tg-home th,

        .tg-home td {

            padding: 9px 8px;

            border-bottom: 1px solid var(--tg-border);

            text-align: start;

            vertical-align: top;

        }


        .tg-home th {

            background: var(--tg-bg);

            color: var(--tg-muted);

        }


        .tg-home .tg-badge {

            display: inline-block;

            padding: 2px 8px;

            border-radius: 20px;

            background: var(--tg-soft);

            font-weight: 700;

            white-space: nowrap;

        }


        .tg-home .tg-good {

            background: #E7F6EC;

            color: var(--tg-green);

        }


        .tg-home .tg-bad {

            background: #FFF0F0;

            color: var(--tg-red);

        }


        .tg-home .tg-warning {

            background: #FFF5E8;

            color: var(--tg-amber);

        }


        .tg-home .tg-feature-filters {

            display: flex;

            flex-wrap: wrap;

            gap: 6px;

        }


        .tg-home .tg-feature-filters a[aria-current="true"] {

            border-color: var(--tg-teal);

            background: var(--tg-soft);

            font-weight: 700;

        }


        .tg-home .tg-feature-filters a:focus-visible {

            outline: 2px solid var(--tg-teal);

            outline-offset: 2px;

        }


        .tg-home .tg-feature-donut-layout {

            display: flex;

            flex-wrap: wrap;

            align-items: center;

            justify-content: center;

            gap: 18px;

            margin-top: 16px;

        }


        .tg-home .tg-feature-donut {

            display: grid;

            flex: 0 0 auto;

            width: clamp(126px, 13vw, 168px);

            aspect-ratio: 1;

            place-items: center;

            border-radius: 50%;

            background: conic-gradient(var(--tg-feature-segments));

        }


        .tg-home .tg-feature-donut-center {

            display: grid;

            width: 73%;

            aspect-ratio: 1;

            align-content: center;

            justify-items: center;

            border-radius: 50%;

            background: #fff;

        }


        .tg-home .tg-feature-donut-center strong {

            color: var(--tg-ink);

            font-size: 26px;

            line-height: 1.1;

        }


        .tg-home .tg-feature-donut-center span {

            color: var(--tg-muted);

            font-size: 12px;

        }


        .tg-home .tg-feature-legend {

            display: grid;

            flex: 1 1 190px;

            gap: 8px;

            min-width: 0;

            margin: 0;

            padding: 0;

            list-style: none;

        }


        .tg-home .tg-feature-legend li {

            display: grid;

            grid-template-columns: 10px minmax(0, 1fr) auto;

            align-items: center;

            gap: 8px;

            font-size: 14px;

        }


        .tg-home .tg-feature-swatch {

            width: 10px;

            height: 10px;

            border-radius: 50%;

            background: var(--tg-feature-color);

        }


        .tg-home .tg-feature-share {

            color: var(--tg-muted);

            font-variant-numeric: tabular-nums;

            white-space: nowrap;

        }


        .tg-home .tg-feature-chart {

            display: grid;

            gap: 12px;

            margin: 16px 0 0;

            padding: 0;

            list-style: none;

        }


        .tg-home .tg-feature-row {

            display: grid;

            grid-template-columns: minmax(6.5rem, 30%) minmax(0, 1fr) auto;

            align-items: center;

            gap: 10px;

            font-size: 14px;

        }


        .tg-home .tg-feature-track {

            display: flex;

            height: 12px;

            overflow: hidden;

            border-radius: 999px;

            background: #E4F2F1;

        }


        .tg-home .tg-feature-bar {

            width: var(--tg-feature-width);

            border-radius: inherit;

            background: #399D9A;

        }


        .tg-home .tg-feature-total {

            min-width: 2ch;

            font-variant-numeric: tabular-nums;

        }


        .tg-home .tg-peak-summary {

            margin: 6px 0 0;

            color: var(--tg-muted);

            font-size: 13px;

        }


        .tg-home .tg-peak-columns {

            display: grid;

            grid-template-columns: repeat(4, minmax(0, 1fr));

            gap: clamp(4px, 1vw, 14px);

            max-width: 720px;

            margin: 16px auto 0;

            padding: 0;

            list-style: none;

            text-align: center;

        }


        .tg-home .tg-peak-column {

            display: grid;

            justify-items: center;

            gap: 4px;

            min-width: 0;

            font-size: 13px;

        }


        .tg-home .tg-peak-column > strong {

            font-size: 18px;

            font-variant-numeric: tabular-nums;

        }


        .tg-home .tg-peak-track {

            display: flex;

            align-items: flex-end;

            justify-content: center;

            width: 72%;

            height: 82px;

            border-bottom: 1px solid #D7E9E8;

            background: linear-gradient(to top, #F2F9F9, transparent);

        }


        .tg-home .tg-peak-fill {

            width: min(56px, 70%);

            height: var(--tg-peak-height);

            border-radius: 7px 7px 0 0;

            background: #A8DDDA;

        }


        .tg-home .tg-peak-column.is-peak .tg-peak-fill {

            background: #288983;

        }


        .tg-home .tg-peak-time {

            color: var(--tg-muted);

            font-size: clamp(9px, 1.4vw, 12px);

        }


        /* بطاقات المؤشرات الملونة */
        .tg-home .tg-stat-users {
            background: #EDF4FF;
            border-color: #92B3D9;
        }

        .tg-home .tg-stat-logs {
            background: #FFF3E9;
            border-color: #D6AE87;
        }

        .tg-home .tg-stat-checklists {
            background: #EDF7EF;
            border-color: #9ABD9D;
        }

        .tg-home .tg-stat-users .tg-stat-icon {
            color: #477FB8;
            background: #DCEAFF;
            border-color: #C8DCF5;
        }

        .tg-home .tg-stat-logs .tg-stat-icon {
            color: #B8793E;
            background: #FCE6D3;
            border-color: #F2D5BA;
        }

        .tg-home .tg-stat-checklists .tg-stat-icon {
            color: #4D956A;
            background: #DDF0E1;
            border-color: #CBE5D1;
        }

        .tg-home .tg-stat-value {
            color: var(--tg-ink);
        }

        /* الإحصائيات الصحية */
        .tg-home .tg-health-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: stretch;
            gap: 14px;
            margin-bottom: 14px;
        }

        /* بطاقتا توزيع الجنس وقراءات السكر بنفس الارتفاع، دون تغيير الرسومات */
        .tg-home .tg-health-grid > .tg-panel {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-width: 0;
        }

        .tg-home .tg-health-grid > .tg-panel:first-child .tg-gender-layout {
            flex: 1 1 auto;
            align-content: center;
            align-items: center;
        }

        .tg-home .tg-map-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(220px, .8fr);
            align-items: center;
            gap: 18px;
        }

        .tg-home .tg-map-panel { margin-block-end: 14px; }

        .tg-home .tg-map-stage {
            min-width: 0;
            min-height: 290px;
            display: grid;
            place-items: center;
            padding: 10px;
            border: 1px solid var(--tg-border);
            border-radius: 13px;
            background: #F7FBFB;
        }

        .tg-home .tg-map-stage svg {
            display: block;
            width: 100%;
            max-height: 430px;
            overflow: visible;
        }

        .tg-home .tg-map-region {
            stroke: #FFFFFF;
            stroke-width: 1.8;
            stroke-linejoin: round;
            transition: filter .15s ease, stroke-width .15s ease;
        }

        .tg-home .tg-map-region[role="button"] {
            cursor: pointer;
        }

        .tg-home .tg-map-region[role="button"]:hover,
        .tg-home .tg-map-region[role="button"]:focus {
            filter: brightness(.92);
            stroke: #2C4A5A;
            stroke-width: 2.8;
            outline: none;
        }

        .tg-home .tg-map-side {
            display: grid;
            gap: 12px;
            min-width: 0;
        }

        .tg-home .tg-map-selected {
            padding: 13px;
            border: 1px solid #D7EAE8;
            border-radius: 12px;
            background: #EFF8F7;
        }

        .tg-home .tg-map-selected strong {
            display: block;
            margin-bottom: 3px;
            font-size: 16px;
        }

        .tg-home .tg-map-selected span {
            color: var(--tg-muted);
            font-size: 13px;
        }

        .tg-home .tg-map-distribution {
            display: grid;
            grid-template-columns: minmax(112px, .85fr) minmax(0, 1.15fr);
            align-items: center;
            gap: 12px;
            padding: 11px;
            border: 1px solid #D7EAE8;
            border-radius: 12px;
            background: #FBFDFD;
        }

        .tg-home .tg-governorate-donut {
            display: grid;
            width: clamp(112px, 11vw, 150px);
            max-width: 100%;
            aspect-ratio: 1;
            margin-inline: auto;
            place-items: center;
            border-radius: 50%;
            background: conic-gradient(var(--tg-governorate-segments));
            box-shadow: 0 3px 12px #244E5514;
        }

        .tg-home .tg-governorate-donut-center {
            display: grid;
            width: 67%;
            aspect-ratio: 1;
            align-content: center;
            justify-items: center;
            border: 1px solid #E3EEEE;
            border-radius: 50%;
            background: #FFFFFF;
        }

        .tg-home .tg-governorate-donut-center strong {
            color: var(--tg-ink);
            font-size: clamp(21px, 2vw, 27px);
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
        }

        .tg-home .tg-governorate-donut-center span {
            color: var(--tg-muted);
            font-size: 11px;
        }

        .tg-home .tg-map-ranking {
            display: grid;
            gap: 6px;
            min-width: 0;
            max-height: 270px;
            overflow-y: auto;
            padding-inline-end: 2px;
        }

        .tg-home .tg-map-rank-row {
            display: grid;
            grid-template-columns: 10px minmax(0, 1fr) auto;
            align-items: center;
            gap: 7px;
            width: 100%;
            min-width: 0;
            padding: 7px 8px;
            border: 1px solid #DFECEB;
            border-radius: 8px;
            background: #F7FBFB;
            color: var(--tg-ink);
            font: inherit;
            font-size: 12px;
            text-align: start;
            cursor: pointer;
            transition: background .15s ease, border-color .15s ease;
        }

        .tg-home .tg-map-rank-swatch {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--rank-color);
        }

        .tg-home .tg-map-rank-row strong {
            white-space: nowrap;
            font-size: 12px;
        }

        .tg-home .tg-map-rank-row[aria-pressed="true"],
        .tg-home .tg-map-rank-row:hover {
            border-color: var(--tg-teal);
            background: #E5F5F3;
        }

        .tg-home .tg-map-share {
            color: var(--tg-muted);
            font-size: 11px;
            text-align: end;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }
        .tg-home .tg-map-label {
            fill: #123E48;
            stroke: #fff;
            stroke-width: 3px;
            paint-order: stroke;
            font-size: 16px;
            font-weight: 700;
            pointer-events: none;
        }

        .tg-home .tg-map-legend {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px 13px;
            color: var(--tg-muted);
            font-size: 12px;
        }

        .tg-home .tg-map-legend span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .tg-home .tg-map-legend i {
            display: inline-block;
            width: 11px;
            height: 11px;
            border-radius: 3px;
        }

        .tg-home .tg-map-credit {
            margin-top: 8px;
            color: #71858C;
            font-size: 10px;
            text-align: center;
        }

        .tg-home .tg-health-total {
            color: var(--tg-muted);
            font-size: 12px;
        }

        .tg-home .tg-gender-layout {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
            width: 100%;
            max-width: none;
            margin-inline: 0;
            padding: 8px 0 4px;
        }

        .tg-home .tg-gender-donut {
            display: grid;
            flex: 0 0 auto;
            width: clamp(145px, 15vw, 175px);
            aspect-ratio: 1;
            place-items: center;
            border-radius: 50%;
            background: conic-gradient(var(--tg-gender-segments, #E5EEEE 0% 100%));
        }

        .tg-home .tg-gender-center {
            display: grid;
            width: 70%;
            aspect-ratio: 1;
            align-content: center;
            justify-items: center;
            border-radius: 50%;
            background: #FFFFFF;
        }

        .tg-home .tg-gender-center strong {
            color: var(--tg-ink);
            font-size: 27px;
            line-height: 1.2;
        }

        .tg-home .tg-gender-center span {
            color: var(--tg-muted);
            font-size: 12px;
        }

        .tg-home .tg-gender-legend {
            display: grid;
            flex: 1 1 145px;
            gap: 8px;
            min-width: 0;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .tg-home .tg-gender-legend li {
            display: grid;
            grid-template-columns: 11px minmax(0, 1fr) auto;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border: 1px solid var(--tg-border);
            border-radius: 8px;
            background: #F7FBFB;
            font-size: 13px;
        }

        .tg-home .tg-gender-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--tg-gender-color);
        }

        .tg-home .tg-gender-share {
            color: var(--tg-muted);
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .tg-home .tg-glucose-chart {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            align-items: end;
            gap: 14px;
            min-height: 235px;
            padding: 12px 4px 0;
        }

        .tg-home .tg-glucose-column {
            display: grid;
            grid-template-rows: auto 145px auto auto;
            align-items: end;
            justify-items: center;
            gap: 7px;
            min-width: 0;
            text-align: center;
        }

        .tg-home .tg-glucose-count {
            font-size: 19px;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }

        .tg-home .tg-glucose-track {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            width: 100%;
            height: 145px;
            border-bottom: 1px solid #DDEAE9;
            border-radius: 8px 8px 0 0;
            background: linear-gradient(to top, #F6FAFA, #FFFFFF);
        }

        .tg-home .tg-glucose-fill {
            width: min(54px, 72%);
            height: var(--tg-glucose-height);
            min-height: 3px;
            border-radius: 8px 8px 0 0;
            background: var(--tg-glucose-color);
            box-shadow: 0 4px 12px color-mix(in srgb, var(--tg-glucose-color) 18%, transparent);
        }

        .tg-home .tg-glucose-label {
            font-size: 13px;
            font-weight: 700;
        }

        .tg-home .tg-glucose-share {
            color: var(--tg-muted);
            font-size: 12px;
        }

        .tg-home .tg-health-note {
            margin-top: 12px;
            padding: 9px 11px;
            border-radius: 9px;
            background: #F3F8F8;
            color: var(--tg-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        @media (max-width: 850px) {
            .tg-home .tg-map-layout {
                grid-template-columns: minmax(0, 1fr);
            }

            .tg-home .tg-map-stage {
                min-height: 220px;
            }
        }

        @media (max-width: 700px) {
            .tg-home .tg-health-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 1150px) {

            .tg-home .tg-charts {

                grid-template-columns: minmax(0, 1fr);

            }

        }


        @media (max-width: 520px) {

            .tg-home {

                padding: 12px;

            }

            .tg-home .tg-map-distribution {
                grid-template-columns: minmax(0, 1fr);
            }

            .tg-home .tg-governorate-donut {
                width: 138px;
            }

            .tg-home .tg-map-ranking {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                max-height: none;
            }


            .tg-home .tg-top,

            .tg-home .tg-panel,

            .tg-home .tg-stat {

                padding: 12px;

            }


            .tg-home .tg-stats {

                grid-template-columns: minmax(0, 1fr);

                gap: 8px;

            }

        }

        .tg-home .tg-header-links form { margin: 0; }
        .tg-home .tg-header-links button {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 7px 10px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: var(--tg-ink);
            font-family: inherit;
            font-size: 13px;
            font-weight: 400;
            line-height: 1.5;
            text-align: start;
            cursor: pointer;
        }
        .tg-home .tg-header-links button:hover { background: var(--tg-soft); }
        .tg-home .tg-header-links button svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            stroke: currentColor;
        }

        .tg-home .tg-alert-overview {
            box-sizing: border-box;
            width: min(560px, calc(100% - 28px));
            max-height: calc(100% - 28px);
            margin: auto;
            padding: 18px;
            overflow: auto;
            color: var(--tg-ink);
            box-shadow: 0 14px 44px #12313f40;
        }
        .tg-home .tg-alert-overview::backdrop { background: #12313f65; }
        .tg-home .tg-alert-dialog-actions {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            margin-block-start: 14px;
        }
        .tg-home .tg-alert-dialog-actions form { margin: 0; }
        .tg-home .tg-glucose-types {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 10px 0;
            padding: 0;
            list-style: none;
        }
        .tg-home .tg-glucose-types li {
            display: flex;
            gap: 10px;
            padding: 5px 9px;
            border: 1px solid var(--tg-border);
            border-radius: 8px;
            color: var(--tg-ink);
        }
        .tg-home .tg-glucose-type-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 14px 0 16px;
        }
        .tg-home .tg-glucose-type-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-width: 112px;
            padding: 9px 14px;
            border: 1px solid var(--tg-border);
            border-radius: 10px;
            background: #F7FBFB;
            color: var(--tg-ink);
            font: inherit;
            cursor: pointer;
            transition: background .18s ease, border-color .18s ease, color .18s ease;
        }
        .tg-home .tg-glucose-type-button[aria-pressed="true"] {
            border-color: var(--tg-primary, #48B9B8);
            background: #E5F5F3;
            color: var(--tg-ink);
            box-shadow: inset 0 0 0 1px var(--tg-primary, #48B9B8);
        }
        .tg-home .tg-glucose-type-button strong {
            font-variant-numeric: tabular-nums;
        }
        .tg-home .tg-glucose-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-block: 12px 16px;
        }
        .tg-home .tg-glucose-stat {
            min-width: 0;
            padding: 11px 12px;
            border: 1px solid var(--tg-border);
            border-radius: 10px;
            background: #F7FBFB;
            text-align: right;
        }
        .tg-home .tg-glucose-stat span {
            display: block;
            color: var(--tg-muted);
            font-size: 12px;
        }
        .tg-home .tg-glucose-stat strong {
            display: block;
            margin-block-start: 5px;
            color: var(--tg-ink);
            font-size: 20px;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }
        .tg-home .tg-glucose-line-chart {
            min-height: 0;
            padding: 10px 8px;
            border: 1px solid var(--tg-border);
            border-radius: 10px;
            background: #FFFFFF;
        }
        .tg-home .tg-glucose-line-chart svg {
            display: block;
            width: 100%;
            height: auto;
        }
        .tg-home .tg-glucose-classification {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-block-start: 12px;
        }
        .tg-home .tg-glucose-classification span {
            padding: 7px 10px;
            border: 1px solid var(--tg-border);
            border-radius: 8px;
            background: #F7FBFB;
            color: var(--tg-ink);
            font-size: 12px;
        }
        .tg-home .tg-glucose-empty {
            display: grid;
            min-height: 170px;
            place-items: center;
            padding: 18px;
            border: 1px dashed var(--tg-border);
            border-radius: 10px;
            color: var(--tg-muted);
            text-align: center;
        }
        @media (max-width: 700px) {
            .tg-home .tg-glucose-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .tg-home .tg-gender-layout { justify-content: center; gap: 14px; }
            .tg-home .tg-glucose-type-button { flex: 1 1 calc(50% - 8px); }
        }
        .tg-home .tg-incomplete-account-list {
            display: grid;
            gap: 7px;
            margin: 10px 0;
            padding: 0;
            list-style: none;
        }
        .tg-home .tg-incomplete-account-list li {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 14px;
            padding: 8px 10px;
            border: 1px solid var(--tg-border);
            border-radius: 8px;
            background: #F7FBFB;
        }
        .tg-home .tg-glucose-subtitle { margin-block-start: 12px; }
    </style>

</head>

<body>

@php

    $dashboardAdmin = auth()->user();
    if (!$dashboardAdmin && session('user_id')) {
        $dashboardAdmin = \App\Models\User::find(session('user_id'));
    }
    $dashboardAdminName = trim((string) $dashboardAdmin?->name);
    $dashboardFirstName = \Illuminate\Support\Str::before($dashboardAdminName, ' ');
    $incompleteAccountReview = $incompleteAccounts ?? null;
    $dashboardIncidentCount = $incompleteAccountReview['count'] ?? 0;

    $hasDatabase = ($databaseStatus ?? null) === 'connected';


    $usagePeriods = [

        'daily' => 'يومي',

        'weekly' => 'أسبوعي',

        'monthly' => 'شهري',

    ];


    $periodKey = is_string($period ?? null) && isset($usagePeriods[$period])

        ? $period

        : 'weekly';


    $usageRows = collect($usageChartData ?? [])->values();

    $eventRows = collect($eventsChartData ?? [])->values();

    $governorateAliases = [
        'صنعاء' => 'صنعاء', 'أمانة العاصمة' => 'صنعاء', 'امانة العاصمة' => 'صنعاء',
        'محافظة صنعاء' => 'صنعاء',
        'صعدة' => 'صعدة', 'صعده' => 'صعدة',
        'ذمار' => 'ذمار', 'تعز' => 'تعز',
        'إب' => 'إب', 'اب' => 'إب', 'أب' => 'إب',
        'عدن' => 'عدن', 'الحديدة' => 'الحديدة', 'الحديده' => 'الحديدة',
    ];
    $governorateTotals = array_fill_keys(array_values($governorateAliases), 0);
    foreach ($governorateDistribution ?? [] as $row) {
        $name = $governorateAliases[trim($row['governorate'])] ?? null;
        if ($name !== null) {
            $governorateTotals[$name] += (int) $row['total'];
        }
    }
    arsort($governorateTotals);
    $governorateRows = collect($governorateTotals)
        ->map(fn ($total, $name) => ['governorate' => $name, 'total' => $total])
        ->values();

    $governorateTotal = (int) $governorateRows->sum('total');

    // ألوان مستقلة لكل محافظة حتى تكون قطاعات الرسم الدائري واضحة.
    $governorateDonutColors = [
        'صنعاء' => '#168A83',
        'صعدة' => '#D69B43',
        'ذمار' => '#548BC2',
        'تعز' => '#9672B7',
        'إب' => '#D4778F',
        'عدن' => '#6EAA78',
        'الحديدة' => '#C47B50',
    ];

    $governorateDonutLegend = [];
    $governorateDonutSegments = [];
    $governorateDonutCounted = 0;

    foreach ($governorateRows as $governorate) {
        $name = $governorate['governorate'];
        $count = (int) $governorate['total'];
        $color = $governorateDonutColors[$name] ?? '#718A91';
        $share = $governorateTotal > 0 ? ($count / $governorateTotal) * 100 : 0;

        if ($count > 0 && $governorateTotal > 0) {
            $start = number_format($governorateDonutCounted, 8, '.', '');
            $governorateDonutCounted += $share;
            $end = number_format($governorateDonutCounted, 8, '.', '');
            $governorateDonutSegments[] = "{$color} {$start}% {$end}%";
        }

        $governorateDonutLegend[] = [
            'governorate' => $name,
            'total' => $count,
            'color' => $color,
            'share' => rtrim(rtrim(number_format($share, 1, '.', ''), '0'), '.'),
        ];
    }

    $governorateDonutBackground = $governorateTotal > 0
        ? implode(', ', $governorateDonutSegments)
        : '#E5EEEE 0% 100%';

    $genderRows = collect($genderDistribution ?? [])->values();

    $genderTotal = (int) $genderRows->sum('total');

    $genderColors = [
        'ذكور' => '#5796C8',
        'إناث' => '#C47AA4',
        'غير محدد' => '#A6B4BB',
    ];

    $genderLegend = [];

    $genderSegments = [];

    $genderCounted = 0;

    if ($genderTotal > 0) {
        foreach ($genderRows as $gender) {
            $count = (int) $gender['total'];
            $color = $genderColors[$gender['label']] ?? '#A6B4BB';
            $start = number_format($genderCounted / $genderTotal * 100, 8, '.', '');
            $genderCounted += $count;
            $end = number_format($genderCounted / $genderTotal * 100, 8, '.', '');

            $genderSegments[] = "{$color} {$start}% {$end}%";
            $genderLegend[] = [
                'label' => $gender['label'],
                'total' => $count,
                'color' => $color,
                'share' => rtrim(rtrim(number_format($count / $genderTotal * 100, 1, '.', ''), '0'), '.'),
            ];
        }
    }

    $featureFilters = $featurePeriods ?? [];

    $featurePeriodKey = $featurePeriod ?? 'weekly';

    $peakPeriodKey = $peakPeriod ?? 'weekly';

    $peakRows = collect($peakUsageRows ?? [])->values();

    $peakTotal = (int) $peakRows->sum('total');

    $peakMaximum = (int) $peakRows->max('total');

    $featureRows = collect($featureUsageChartData ?? [])->values();

    $featureTotal = (int) $featureRows->sum('total');

    $featureColors = [

        'قراءات السكر' => '#3972A0',

        'الوجبات' => '#339692',

        'الأنشطة' => '#C28A3B',

        'الأدوية' => '#886DB1',

        'التقارير' => '#BC6683',

        'التذكيرات' => '#628843',

    ];

    $featureLegend = [];

    $featureSegments = [];

    $featureCounted = 0;


    if ($featureTotal > 0) {

        foreach ($featureRows as $feature) {

            $count = (int) $feature['total'];

            $color = $featureColors[$feature['label']] ?? '#567581';

            $start = number_format($featureCounted / $featureTotal * 100, 8, '.', '');

            $featureCounted += $count;

            $end = number_format($featureCounted / $featureTotal * 100, 8, '.', '');


            $featureSegments[] = "{$color} {$start}% {$end}%";

            $featureLegend[] = [

                'label' => $feature['label'],

                'total' => $count,

                'color' => $color,

                'share' => rtrim(rtrim(number_format($count / $featureTotal * 100, 1, '.', ''), '0'), '.'),

            ];

        }

    }


@endphp


<div class="dashboard">

    @include('partials.admin-sidebar')


    <main class="main-content tg-home">

        <header class="tg-card tg-top">
            <div>
                <h1>👋 مرحبًا{{ $dashboardFirstName !== '' ? '، ' . $dashboardFirstName : '' }}</h1>
                <p>لوحة تحكم نظام <bdi>TinyGuide</bdi></p>
            </div>

            <div class="tg-header-actions">
                <details class="tg-header-menu tg-header-bell">
                    <summary class="tg-header-icon" aria-label="{{ $dashboardIncidentCount > 0 ? 'التنبيهات، حسابات غير مكتملة: ' . $dashboardIncidentCount : 'التنبيهات' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12a2 2 0 0 0 4 0"/>
                        </svg>
                        @if ($dashboardIncidentCount > 0)
                            <span class="tg-header-dot" aria-hidden="true"></span>
                        @endif
                    </summary>
                    <div class="tg-header-links">
                        <a href="{{ route('notifications.create') }}"><span aria-hidden="true">＋</span> إضافة إشعار</a>
                        <a href="#tg-dashboard-alerts" aria-haspopup="dialog"><span aria-hidden="true">🔔</span> تنبيهات</a>
                    </div>
                </details>

                <details class="tg-header-menu tg-header-account">
                    <summary>
                        <span class="tg-header-icon tg-header-avatar" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                 stroke-linecap="round" aria-hidden="true">
                                <circle cx="12" cy="8" r="3.5"/>
                                <path d="M5 20v-1a7 7 0 0 1 14 0v1"/>
                            </svg>
                        </span>
                        <span>
                            @if ($dashboardAdminName !== '')
                                <strong>{{ $dashboardAdminName }}</strong>
                            @endif
                            <small>مسؤولة النظام</small>
                        </span>
                        <svg class="tg-header-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="tg-header-links">
                        <a href="{{ url('/settings') }}"><span aria-hidden="true">⚙</span> الإعدادات</a>
                        <form method="POST" action="{{ url('/logout') }}">
                            @csrf
                            <button type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true" focusable="false">
                                    <path d="M10 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h5"/>
                                    <path d="M16 17l5-5-5-5"/>
                                    <path d="M21 12H9"/>
                                </svg>
                                <span>تسجيل الخروج</span>
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </header>

        <dialog id="tg-dashboard-alerts" class="tg-card tg-panel tg-alert-overview" aria-labelledby="tg-dashboard-alert-title">
            <div class="tg-panel-head">
                <div>
                    <h2 id="tg-dashboard-alert-title">تنبيهات</h2>
                    <p class="tg-muted">حسابات أولياء الأمور التي مضى على تسجيلها ٢٤ ساعة ولم يُضف أصحابها طفلًا بعد.</p>
                </div>
            </div>
            @if ($incompleteAccountReview === null)
                <p class="tg-empty">تعذّر التحقق من الحسابات غير المكتملة حاليًا.</p>
            @elseif ($dashboardIncidentCount === 0)
                <p class="tg-empty">لا توجد حسابات غير مكتملة تحتاج متابعة حاليًا.</p>
            @else
                <p><strong>{{ number_format($dashboardIncidentCount) }}</strong> حساب غير مكتمل</p>
                <ul class="tg-incomplete-account-list">
                    @foreach ($incompleteAccountReview['items'] as $account)
                        <li>
                            <strong>{{ $account['name'] !== '' ? $account['name'] : 'ولي أمر بدون اسم' }}</strong>
                            <span>تاريخ التسجيل: <bdi>{{ $account['registeredAt'] }}</bdi></span>
                            <span>منذ {{ number_format($account['hoursSinceRegistration']) }} ساعة</span>
                        </li>
                    @endforeach
                </ul>
                @if ($dashboardIncidentCount > count($incompleteAccountReview['items']))
                    <p class="tg-muted">تظهر هنا أقدم ٥ حسابات غير مكتملة.</p>
                @endif
            @endif
            <div class="tg-alert-dialog-actions">
                <form method="dialog">
                    <button type="submit" class="tg-button" autofocus>إغلاق</button>
                </form>
            </div>
        </dialog>

        <section class="tg-grid tg-stats" aria-label="مؤشرات النظام">

            <a class="tg-card tg-stat tg-stat-users" href="{{ route('users.index') }}">

                <div class="tg-stat-head">
                    <span class="tg-stat-icon" aria-hidden="true">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"

                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"

                             focusable="false">

                            <circle cx="9" cy="8" r="3"/>

                            <path d="M3 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>

                            <path d="M16 5a3 3 0 0 1 0 6m2 3a4 4 0 0 1 3 4v2"/>

                        </svg>

                    </span>
                    <div class="tg-stat-content">
                        <strong class="tg-stat-title" style="font-size: 18px;">إجمالي الحسابات</strong>
                        <strong class="tg-stat-value">
                            {{ $hasDatabase && isset($usersCount) ? number_format($usersCount) : '—' }}
                        </strong>
                    </div>
                </div>


            </a>


            <a class="tg-card tg-stat tg-stat-logs" href="{{ route('system-logs.index') }}">

                <div class="tg-stat-head">
                    <span class="tg-stat-icon" aria-hidden="true">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"

                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"

                             focusable="false">

                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>

                            <path d="M14 2v6h6M8 12h8M8 16h8"/>

                        </svg>

                    </span>
                    <div class="tg-stat-content">
                        <strong class="tg-stat-title" style="font-size: 18px;">سجلات النظام</strong>
                        <strong class="tg-stat-value">
                            {{ $hasDatabase && isset($systemLogsCount) ? number_format($systemLogsCount) : '—' }}
                        </strong>
                    </div>
                </div>

            </a>


            <a class="tg-card tg-stat tg-stat-checklists" href="{{ route('admin.checklists.index') }}">

                <div class="tg-stat-head">
                    <span class="tg-stat-icon" aria-hidden="true">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"

                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"

                             focusable="false">

                            <rect x="4" y="3" width="16" height="18" rx="2"/>

                            <path d="m7 9 1 1 2-2m2 1h5m-10 6 1 1 2-2m2 1h5"/>

                        </svg>

                    </span>
                    <div class="tg-stat-content">
                        <strong class="tg-stat-title" style="font-size: 18px;">قوائم التحقق</strong>
                        <strong class="tg-stat-value">
                            {{ isset($checklistsCount) ? number_format($checklistsCount) : '—' }}
                        </strong>
                    </div>
                </div>

            </a>


        </section>


        <div class="tg-grid tg-charts">

            <section class="tg-card tg-panel" aria-labelledby="tg-usage-title">

                <div class="tg-panel-head">

                    <div>

                        <h2 id="tg-usage-title">استخدام تطبيق TinyGuide</h2>

                        <p class="tg-muted">عدد حسابات أولياء الأمور المختلفة في كل فترة.</p>

                    </div>


                    <form class="tg-filter" action="{{ url('/dashboard') }}" method="GET">

                        <input type="hidden" name="feature_period" value="{{ $featurePeriodKey }}">

                        <input type="hidden" name="peak_period" value="{{ $peakPeriodKey }}">

                        <label for="tg-period">الفترة</label>


                        <select id="tg-period" name="period">

                            @foreach ($usagePeriods as $key => $label)

                                <option value="{{ $key }}" @selected($periodKey === $key)>

                                    {{ $label }}

                                </option>

                            @endforeach

                        </select>


                        <button class="tg-button" type="submit">عرض</button>

                    </form>

                </div>


                @if ($hasDatabase && $usageRows->isNotEmpty())

                    <div id="tg-usage-chart" class="tg-chart"></div>
                    <p class="tg-muted">آخر فترة معروضة: {{ number_format((int) $usageRows->last()['total']) }} حساب</p>

                @else

                    <p class="tg-empty">بيانات استخدام التطبيق غير متاحة للعرض حاليًا.</p>

                @endif

            </section>


            <section class="tg-card tg-panel" aria-labelledby="tg-events-title">

                <div class="tg-panel-head">

                    <div>

                        <h2 id="tg-events-title">أحداث النظام</h2>

                        <p class="tg-muted">الأخطاء التقنية والتنبيهات الأمنية خلال آخر ٧ أيام.</p>

                    </div>

                </div>


                @if ($hasDatabase && $eventRows->isNotEmpty())

                    <div id="tg-events-chart" class="tg-chart"></div>
                    @if (((int) ($errorsCount ?? 0) + (int) ($securityAlertsCount ?? 0)) === 0)
                        <p class="tg-muted">لم تُسجّل أحداث من هذين النوعين خلال الفترة.</p>
                    @endif


                    <div class="tg-legend">

                        <span style="--swatch: #D46A6A">أخطاء تقنية {{ number_format($errorsCount ?? 0) }}</span>

                        <span style="--swatch: #C18A38">تنبيهات أمنية {{ number_format($securityAlertsCount ?? 0) }}</span>

                    </div>

                @else

                    <p class="tg-empty">بيانات رسم أحداث النظام غير متاحة للعرض حاليًا.</p>

                @endif

            </section>

        </div>



        <section class="tg-card tg-panel tg-map-panel" aria-labelledby="tg-governorates-title">
            <div class="tg-panel-head">
                <div>
                    <h2 id="tg-governorates-title">توزيع الأطفال حسب المحافظة</h2>
                    <p class="tg-muted">خريطة ورسم دائري يوضحان عدد الأطفال المسجلين في كل محافظة.</p>
                </div>
                <span class="tg-badge">{{ number_format((int) $governorateRows->sum('total')) }} طفلًا في المحافظات السبع</span>
            </div>

            @if ($hasDatabase)
                <div class="tg-map-layout">
                    <div>
                        <div id="tg-governorate-map" class="tg-map-stage" aria-label="خريطة تفاعلية لمحافظات اليمن">
                            <p class="tg-muted">جارٍ تحميل خريطة المحافظات...</p>
                        </div>
                        <div class="tg-map-legend" aria-label="مفتاح ألوان الخريطة">
                            <span><i style="background:#76C9C1"></i> طفل واحد</span>
                            <span><i style="background:#43B2A8"></i> طفلان</span>
                            <span><i style="background:#218F87"></i> 3 إلى 4</span>
                            <span><i style="background:#075F5D"></i> 5 فأكثر</span>
                            <span><i style="background:#EDF3F4"></i> صفر</span>
                            <span><i style="background:#DDE8F0"></i> خارج المحافظات السبع</span>
                        </div>
                        <p class="tg-map-credit">حدود المحافظات: geoBoundaries / OpenStreetMap · CC BY 4.0 وODbL</p>
                    </div>

                    <div class="tg-map-side">
                        <div id="tg-map-selected" class="tg-map-selected" aria-live="polite">
                            <strong>اختاري محافظة من الخريطة أو القائمة</strong>
                            <span>سيظهر هنا عدد الأطفال المسجلين فيها.</span>
                        </div>

                        <div class="tg-map-distribution">
                            <div class="tg-governorate-donut"
                                 style="--tg-governorate-segments: {{ $governorateDonutBackground }}"
                                 role="img"
                                 aria-label="رسم دائري يوضح توزيع {{ number_format($governorateTotal) }} طفلًا حسب المحافظة">
                                <div class="tg-governorate-donut-center">
                                    <strong>{{ number_format($governorateTotal) }}</strong>
                                    <span>طفلًا</span>
                                </div>
                            </div>

                            <div class="tg-map-ranking" aria-label="مفتاح الرسم الدائري وعدد الأطفال حسب المحافظة">
                                @foreach ($governorateDonutLegend as $governorate)
                                    <button type="button" class="tg-map-rank-row"
                                            data-map-region="{{ $governorate['governorate'] }}"
                                            aria-pressed="false">
                                        <span class="tg-map-rank-swatch" style="--rank-color: {{ $governorate['color'] }}" aria-hidden="true"></span>
                                        <span>{{ $governorate['governorate'] }}</span>
                                        <strong>{{ number_format($governorate['total']) }} <small>({{ $governorate['share'] }}٪)</small></strong>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p class="tg-empty">بيانات المحافظات غير متاحة حاليًا.</p>
            @endif
        </section>

        <div class="tg-grid tg-health-grid">
            <section class="tg-card tg-panel" aria-labelledby="tg-gender-title">
                <div class="tg-panel-head">
                    <div>
                        <h2 id="tg-gender-title">توزيع الأطفال حسب الجنس</h2>
                        <p class="tg-muted">مقارنة عدد الذكور والإناث من الأطفال المسجلين.</p>
                    </div>
                    <span class="tg-health-total">الإجمالي: {{ number_format($genderTotal) }}</span>
                </div>

                @if ($hasDatabase && $genderTotal > 0)
                    <div class="tg-gender-layout">
                        <div class="tg-gender-donut"
                             style="--tg-gender-segments: {{ implode(', ', $genderSegments) }}"
                             role="img"
                             aria-label="رسم دائري لتوزيع الأطفال حسب الجنس">
                            <div class="tg-gender-center">
                                <strong>{{ number_format($genderTotal) }}</strong>
                                <span>طفلًا</span>
                            </div>
                        </div>

                        <ul class="tg-gender-legend">
                            @foreach ($genderLegend as $gender)
                                <li>
                                    <span class="tg-gender-dot" style="--tg-gender-color: {{ $gender['color'] }}"></span>
                                    <span>{{ $gender['label'] }}</span>
                                    <span class="tg-gender-share">
                                        <strong>{{ number_format($gender['total']) }}</strong>
                                        · {{ $gender['share'] }}٪
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif ($hasDatabase)
                    <p class="tg-empty">لا توجد بيانات جنس مسجلة حتى الآن.</p>
                @else
                    <p class="tg-empty">بيانات الجنس غير متاحة حاليًا.</p>
                @endif
            </section>

            <section class="tg-card tg-panel" aria-labelledby="tg-glucose-title">
                <div class="tg-panel-head">
                    <div>
                        <h2 id="tg-glucose-title">مستويات قراءات السكر</h2>
                        <p class="tg-muted">اختاري نوع القياس لعرض إحصائياته وقراءات السكر المسجلة.</p>
                    </div>
                </div>

                @if ($hasDatabase)
                    <div class="tg-glucose-type-buttons" role="group" aria-label="اختيار نوع قراءة السكر">
                        @foreach (['صائم', 'قبل الوجبة', 'بعد الوجبة'] as $type)
                            <button
                                type="button"
                                class="tg-glucose-type-button"
                                data-glucose-type="{{ $type }}"
                                aria-pressed="{{ $type === 'بعد الوجبة' ? 'true' : 'false' }}"
                            >
                                <span>{{ $type }}</span>
                                <strong>{{ number_format($glucoseTypeCounts[$type] ?? 0) }}</strong>
                            </button>
                        @endforeach
                    </div>

                    <div class="tg-glucose-summary" aria-live="polite">
                        <div class="tg-glucose-stat"><span>عدد القراءات</span><strong id="tg-glucose-total">0</strong></div>
                        <div class="tg-glucose-stat"><span>المتوسط (mg/dL)</span><strong id="tg-glucose-average">—</strong></div>
                        <div class="tg-glucose-stat"><span>نطاق القراءات (الأقل – الأعلى)</span><strong id="tg-glucose-range">—</strong></div>
                    </div>

                    <h3 class="tg-glucose-subtitle" id="tg-glucose-chart-title">قراءات بعد الوجبة حسب تاريخ التسجيل</h3>
                    <div class="tg-glucose-line-chart" id="tg-glucose-line-chart" aria-live="polite"></div>
                    <div class="tg-glucose-classification" id="tg-glucose-classification" aria-live="polite"></div>
                @elseif (!$hasDatabase)
                    <p class="tg-empty">بيانات قراءات السكر غير متاحة حاليًا.</p>
                @endif
            </section>
        </div>

        <div class="tg-grid tg-charts">
<section class="tg-card tg-panel" aria-labelledby="tg-feature-title">

                <div class="tg-panel-head">

                    <div>

                        <h2 id="tg-feature-title">أكثر أقسام التطبيق استخدامًا</h2>

                        <p class="tg-muted">عدد الطلبات المسجّلة لكل قسم · {{ $featureFilters[$featurePeriodKey]['caption'] ?? 'آخر ٧ أيام' }}</p>

                    </div>


                    <nav class="tg-feature-filters" aria-label="فترة استخدام الأقسام">

                        @foreach ($featureFilters as $key => $filter)

                            <a class="tg-button"

                               href="{{ url('/dashboard') . '?' . http_build_query(['period' => $periodKey, 'feature_period' => $key, 'peak_period' => $peakPeriodKey]) }}#tg-feature-title"

                               @if ($key === $featurePeriodKey) aria-current="true" @endif>

                                {{ $filter['label'] }}

                            </a>

                        @endforeach

                    </nav>

                </div>


                @if ($hasDatabase && $featureTotal > 0)

                    <div class="tg-feature-donut-layout">

                        <div class="tg-feature-donut" style="--tg-feature-segments: {{ implode(', ', $featureSegments) }}">

                            <div class="tg-feature-donut-center">

                                <strong>{{ number_format($featureTotal) }}</strong>

                                <span>إجمالي الطلبات</span>

                            </div>

                        </div>


                        <ul class="tg-feature-legend" aria-label="توزيع الطلبات حسب القسم">

                            @foreach ($featureLegend as $feature)

                                <li>

                                    <span class="tg-feature-swatch" style="--tg-feature-color: {{ $feature['color'] }}" aria-hidden="true"></span>

                                    <span>{{ $feature['label'] }}</span>

                                    <span class="tg-feature-share">

                                        <strong>{{ number_format($feature['total']) }}</strong>

                                        <span aria-hidden="true">·</span>

                                        <bdi>{{ $feature['share'] }}٪</bdi>

                                    </span>

                                </li>

                            @endforeach

                        </ul>

                    </div>

                @elseif ($hasDatabase)

                    <p class="tg-empty">ما فيه طلبات أمكن ربطها بالأقسام خلال الفترة المختارة.</p>

                @else

                    <p class="tg-empty">بيانات استخدام الأقسام غير متاحة حاليًا.</p>

                @endif

            </section>
<section class="tg-card tg-panel" aria-labelledby="tg-peak-title">

            <div class="tg-panel-head">

                <div>

                    <h2 id="tg-peak-title">أوقات ذروة استخدام التطبيق</h2>

                    <p class="tg-muted">طلبات التطبيق حسب وقت اليوم · {{ $featureFilters[$peakPeriodKey]['caption'] ?? 'آخر ٧ أيام' }}</p>

                </div>


                <nav class="tg-feature-filters" aria-label="فترة أوقات الذروة">

                    @foreach ($featureFilters as $key => $filter)

                        <a class="tg-button"

                           href="{{ url('/dashboard') . '?' . http_build_query(['period' => $periodKey, 'feature_period' => $featurePeriodKey, 'peak_period' => $key]) }}#tg-peak-title"

                           @if ($key === $peakPeriodKey) aria-current="true" @endif>

                            {{ $filter['label'] }}

                        </a>

                    @endforeach

                </nav>

            </div>


            @if ($hasDatabase && $peakTotal > 0)

                <p class="tg-peak-summary">

                    إجمالي الطلبات <strong>{{ number_format($peakTotal) }}</strong>

                    · أعلى عدد في فترة <strong>{{ number_format($peakMaximum) }}</strong>

                </p>

                <ul class="tg-peak-columns" aria-label="عدد الطلبات في فترات اليوم">

                    @foreach ($peakRows as $slot)

                        <li class="tg-peak-column{{ $slot['total'] === $peakMaximum ? ' is-peak' : '' }}">

                            <strong>{{ number_format($slot['total']) }}</strong>

                            <span class="tg-peak-track" aria-hidden="true">

                                <span class="tg-peak-fill"

                                      style="--tg-peak-height: {{ round($slot['total'] / $peakMaximum * 100, 1) }}%"></span>

                            </span>

                            <span>{{ $slot['label'] }}</span>

                            <bdi class="tg-peak-time">{{ $slot['hours'] }}</bdi>

                        </li>

                    @endforeach

                </ul>

            @elseif ($hasDatabase)

                <p class="tg-empty">ما فيه طلبات تطبيق مسجّلة خلال هذي الفترة.</p>

            @else

                <p class="tg-empty">بيانات أوقات الاستخدام غير متاحة حاليًا.</p>

            @endif

        </section>
        </div>
    </main>

</div>


<script>

    (() => {

        const usageRows = {{ \Illuminate\Support\Js::from($usageRows) }};

        const eventRows = {{ \Illuminate\Support\Js::from($eventRows) }};

        const glucoseAnalytics = {{ \Illuminate\Support\Js::from($glucoseAnalytics ?? []) }};

        const svgNamespace = 'http://www.w3.org/2000/svg';

        const formatNumber = new Intl.NumberFormat('ar');


        function addSvg(parent, tag, attributes, text = null) {

            const element = document.createElementNS(svgNamespace, tag);


            for (const [name, value] of Object.entries(attributes)) {

                element.setAttribute(name, value);

            }


            if (text !== null) {

                element.textContent = text;

            }


            parent.append(element);

            return element;

        }


        function drawChart(containerId, rows, series, type, title) {

            const container = document.getElementById(containerId);


            if (!container || rows.length === 0) {

                return;

            }


            const width = 480;

            const height = 210;

            const left = 46;

            const right = 458;

            const top = 20;

            const bottom = 172;

            const slot = (right - left) / rows.length;


            const readValue = (row, key) => Math.max(0, Number(row[key]) || 0);

            const maximum = Math.max(1, ...rows.flatMap(

                (row) => series.map(({ key }) => readValue(row, key))

            ));


            const step = Math.max(1, Math.ceil(maximum / 4));

            const axisMax = step * 4;

            const x = (index) => left + slot * (index + 0.5);

            const y = (value) => bottom - (value / axisMax) * (bottom - top);


            const svg = addSvg(container, 'svg', {

                viewBox: `0 0 ${width} ${height}`,

                role: 'img',

                'aria-label': title,

                direction: 'ltr',

            });


            const description = rows.map((row) => {

                const values = series.map(({ key, label }) =>

                    `${label} ${formatNumber.format(readValue(row, key))}`

                );


                return `${row.label}: ${values.join('، ')}`;

            });


            addSvg(svg, 'desc', {}, description.join('. '));


            for (let index = 0; index <= 4; index++) {

                const value = step * index;

                const position = y(value);


                addSvg(svg, 'line', {

                    x1: left, x2: right, y1: position, y2: position,

                    stroke: '#C9E7E5',

                });


                addSvg(svg, 'text', {

                    x: left - 8, y: position + 4, 'text-anchor': 'end',

                    'font-size': 12, fill: '#526D78',

                }, formatNumber.format(value));

            }


            const labelStep = Math.max(1, Math.ceil(rows.length / 7));


            rows.forEach((row, index) => {

                if (index % labelStep === 0) {

                    addSvg(svg, 'text', {

                        x: x(index), y: bottom + 20, 'text-anchor': 'middle',

                        'font-size': 11, fill: '#526D78',

                    }, row.label);

                }

            });


            series.forEach(({ key, label, color }, seriesIndex) => {

                if (type === 'line') {

                    const points = rows.map((row, index) => ({
                        x: x(index),
                        y: y(readValue(row, key)),
                    }));

                    let curvePath = '';

                    points.forEach((point, index) => {
                        if (index === 0) {
                            curvePath = `M ${point.x} ${point.y}`;
                            return;
                        }

                        const previous = points[index - 1];
                        const middleX = (previous.x + point.x) / 2;

                        curvePath += ` C ${middleX} ${previous.y}, ${middleX} ${point.y}, ${point.x} ${point.y}`;
                    });

                    const firstPoint = points[0];
                    const lastPoint = points[points.length - 1];
                    const areaPath = `${curvePath} L ${lastPoint.x} ${bottom} L ${firstPoint.x} ${bottom} Z`;

                    addSvg(svg, 'path', {
                        d: areaPath,
                        fill: color,
                        'fill-opacity': .12,
                    });

                    addSvg(svg, 'path', {
                        d: curvePath,
                        fill: 'none',
                        stroke: color,
                        'stroke-width': 3,
                        'stroke-linecap': 'round',
                        'stroke-linejoin': 'round',
                    });

                }


                rows.forEach((row, index) => {

                    const value = readValue(row, key);

                    const position = y(value);

                    const barWidth = Math.min(17, slot / 4);


                    const mark = type === 'line'

                        ? addSvg(svg, 'circle', {

                            cx: x(index), cy: position, r: 5,

                            fill: color, stroke: '#FFFFFF', 'stroke-width': 2,

                        })

                        : addSvg(svg, 'rect', {

                            x: x(index) + (seriesIndex - (series.length - 1) / 2) * (barWidth + 2) - barWidth / 2,

                            y: position,

                            width: barWidth,

                            height: bottom - position,

                            rx: 2,

                            fill: color,

                        });


                    addSvg(mark, 'title', {}, `${row.label} · ${label} ${formatNumber.format(value)}`);
                    if (value > 0 || type === 'line') {
                        const chartX = type === 'line' ? x(index) : Number(mark.getAttribute('x')) + barWidth / 2;
                        addSvg(svg, 'text', {
                            x: chartX, y: position - 6, 'text-anchor': 'middle',
                            'font-size': 11, 'font-weight': 700, fill: '#2C4A5A',
                        }, formatNumber.format(value));
                    }

                });

            });

        }


        const governorateRows = {{ \Illuminate\Support\Js::from($governorateRows) }};
        const governorateMapElement = document.getElementById('tg-governorate-map');
        const governorateMapUrl = @json(asset('data/yemen-governorates.geojson'));
        const governorateCounts = new Map(
            governorateRows.map((row) => [row.governorate, Number(row.total) || 0])
        );
        const governorateNamesByIso = new Map([
            ['YE-AB', 'أبين'], ['YE-AD', 'عدن'], ['YE-AM', 'عمران'],
            ['YE-BA', 'البيضاء'], ['YE-DA', 'الضالع'], ['YE-DH', 'ذمار'],
            ['YE-HD', 'حضرموت'], ['YE-HJ', 'حجة'], ['YE-HU', 'الحديدة'],
            ['YE-IB', 'إب'], ['YE-JA', 'الجوف'], ['YE-LA', 'لحج'],
            ['YE-MA', 'مأرب'], ['YE-MR', 'المهرة'], ['YE-MW', 'المحويت'],
            ['YE-RA', 'ريمة'], ['YE-SA', 'صنعاء'], ['YE-SD', 'صعدة'],
            ['YE-SH', 'شبوة'], ['YE-SN', 'أمانة العاصمة'], ['YE-TA', 'تعز'],
            ['YE-SU', 'سقطرى']
        ]);
        const normalizeGovernorate = (value) => String(value ?? '').trim()
            .replace(/[أإآ]/g, 'ا').replace(/ة/g, 'ه').replace(/[ًٌٍَُِّْ]/g, '');
        const governorateAliases = new Map([
            ['صنعاء', 'صنعاء'], ['امانه العاصمه', 'أمانة العاصمة'], ['العاصمه', 'أمانة العاصمة'],
            ['الحديده', 'الحديدة'], ['المحويت', 'المحويت'], ['الضالع', 'الضالع'],
            ['البيضاء', 'البيضاء'], ['اب', 'إب'], ['ذمار', 'ذمار'], ['صعده', 'صعدة']
        ]);

        const regionButtons = document.querySelectorAll('[data-map-region]');

        function selectGovernorate(name) {
            const selected = document.getElementById('tg-map-selected');
            if (!selected) return;

            const heading = document.createElement('strong');
            const detail = document.createElement('span');
            heading.textContent = name;
            detail.textContent = `${formatNumber.format(governorateCounts.get(name) ?? 0)} طفل مسجل`;
            selected.replaceChildren(heading, detail);
            regionButtons.forEach((button) => {
                button.setAttribute('aria-pressed', String(button.dataset.mapRegion === name));
            });
        }

        regionButtons.forEach((button) => {
            button.addEventListener('click', () => selectGovernorate(button.dataset.mapRegion));
        });

        function governorateColor(count) {
            if (count <= 0) return '#EDF3F4';
            if (count === 1) return '#76C9C1';
            if (count === 2) return '#43B2A8';
            if (count <= 4) return '#218F87';
            return '#075F5D';
        }

        function polygonGroups(geometry) {
            if (!geometry) return [];
            if (geometry.type === 'Polygon') return [geometry.coordinates];
            if (geometry.type === 'MultiPolygon') return geometry.coordinates;
            return [];
        }

        function polygonPath(polygon, project) {
            return polygon.map((ring) => {
                return ring.map((point, index) => {
                    const [x, y] = project(point);
                    return `${index === 0 ? 'M' : 'L'}${x.toFixed(2)},${y.toFixed(2)}`;
                }).join(' ') + ' Z';
            }).join(' ');
        }

        function drawGovernorateMap(geoJson) {
            if (!governorateMapElement) return;

            const features = Array.isArray(geoJson?.features) ? geoJson.features : [];
            if (!features.length) {
                governorateMapElement.innerHTML = '<p class="tg-empty">ملف خريطة المحافظات لا يحتوي على حدود قابلة للعرض.</p>';
                return;
            }

            const allPoints = [];
            features.forEach((feature) => {
                polygonGroups(feature.geometry).forEach((polygon) => {
                    polygon.forEach((ring) => ring.forEach((point) => allPoints.push(point)));
                });
            });

            if (!allPoints.length) {
                governorateMapElement.innerHTML = '<p class="tg-empty">تعذر قراءة إحداثيات خريطة المحافظات.</p>';
                return;
            }

            const longitudes = allPoints.map((point) => Number(point[0]));
            const latitudes = allPoints.map((point) => Number(point[1]));
            const minLon = Math.min(...longitudes);
            const maxLon = Math.max(...longitudes);
            const minLat = Math.min(...latitudes);
            const maxLat = Math.max(...latitudes);
            const width = 820;
            const height = 470;
            const padding = 18;
            const lonRange = Math.max(.0001, maxLon - minLon);
            const latRange = Math.max(.0001, maxLat - minLat);
            const scale = Math.min((width - padding * 2) / lonRange, (height - padding * 2) / latRange);
            const drawnWidth = lonRange * scale;
            const drawnHeight = latRange * scale;
            const offsetX = (width - drawnWidth) / 2;
            const offsetY = (height - drawnHeight) / 2;

            const project = ([longitude, latitude]) => [
                offsetX + (Number(longitude) - minLon) * scale,
                height - offsetY - (Number(latitude) - minLat) * scale
            ];

            const svg = document.createElementNS(svgNamespace, 'svg');
            svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
            svg.setAttribute('role', 'group');
            svg.setAttribute('aria-label', 'خريطة محافظات اليمن وعدد الأطفال المسجلين');
            svg.setAttribute('class', 'tg-map-svg');

            const labels = [];
            features.forEach((feature) => {
                const iso = String(feature.properties?.shapeISO ?? '').trim().toUpperCase();
                const rawName = feature.properties?.shapeName ?? feature.properties?.NAME_1 ?? feature.properties?.name ?? feature.properties?.admin1Name ?? '';
                const normalizedName = normalizeGovernorate(rawName);
                const canonicalName = governorateNamesByIso.get(iso)
                    ?? governorateAliases.get(normalizedName)
                    ?? (governorateRows.find((row) => normalizeGovernorate(row.governorate) === normalizedName)?.governorate || null);
                const count = governorateCounts.get(canonicalName) ?? 0;

                const polygons = polygonGroups(feature.geometry);
                if (canonicalName && iso !== 'YE-SA') {
                    const rings = polygons.map((polygon) => polygon[0] || []);
                    const area = (ring) => Math.abs(ring.reduce((sum, point, index) => {
                        const next = ring[(index + 1) % ring.length];
                        return sum + point[0] * next[1] - next[0] * point[1];
                    }, 0));
                    const largest = rings.sort((a, b) => area(b) - area(a))[0];
                    if (largest?.length) {
                        const points = largest.map(project);
                        const xs = points.map(([px]) => px);
                        const ys = points.map(([, py]) => py);
                        labels.push({
                            name: canonicalName,
                            x: (Math.min(...xs) + Math.max(...xs)) / 2,
                            y: (Math.min(...ys) + Math.max(...ys)) / 2,
                        });
                    }
                }

                polygons.forEach((polygon) => {
                    const path = document.createElementNS(svgNamespace, 'path');
                    path.setAttribute('d', polygonPath(polygon, project));
                    path.setAttribute('class', 'tg-map-region');
                    path.setAttribute('fill', canonicalName ? governorateColor(count) : '#DFEBF6');
                    path.setAttribute('fill-rule', 'evenodd');
                    if (!canonicalName) {
                        path.setAttribute('aria-hidden', 'true');
                        svg.appendChild(path);
                        return;
                    }

                    path.setAttribute('tabindex', '0');
                    path.setAttribute('role', 'button');
                    path.setAttribute('aria-label', `${canonicalName}: ${formatNumber.format(count)} طفل`);
                    const title = document.createElementNS(svgNamespace, 'title');
                    title.textContent = `${canonicalName}: ${formatNumber.format(count)} طفل`;
                    path.appendChild(title);

                    const selectRegion = () => selectGovernorate(canonicalName);

                    path.addEventListener('click', selectRegion);
                    path.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            selectRegion();
                        }
                    });

                    svg.appendChild(path);
                });
            });

            const placed = [];
            labels.sort((a, b) => a.y - b.y).forEach(({ name, x, y }) => {
                let labelY = y;
                while (placed.some((label) => Math.abs(label.x - x) < 59 && Math.abs(label.y - labelY) < 19)) {
                    labelY += 19;
                }
                placed.push({ x, y: labelY });
                if (labelY !== y) {
                    addSvg(svg, 'line', { x1: x, y1: y, x2: x, y2: labelY, stroke: '#315D68', 'pointer-events': 'none' });
                }
                addSvg(svg, 'text', {
                    x, y: labelY, 'text-anchor': 'middle', 'dominant-baseline': 'middle',
                    class: 'tg-map-label', 'aria-hidden': 'true',
                }, name);
            });

            governorateMapElement.innerHTML = '';
            governorateMapElement.appendChild(svg);
        }

        if (governorateMapElement) {
            fetch(governorateMapUrl, { headers: { 'Accept': 'application/geo+json, application/json' } })
                .then((response) => {
                    if (!response.ok) throw new Error('Map file not found');
                    return response.json();
                })
                .then(drawGovernorateMap)
                .catch(() => {
                    governorateMapElement.innerHTML =
                        '<p class="tg-empty">ملف خريطة المحافظات غير موجود. سنضيف ملف الحدود الجغرافية في الخطوة التالية.</p>';
                });
        }


        const glucoseTypeButtons = document.querySelectorAll('[data-glucose-type]');
        const glucoseChartContainer = document.getElementById('tg-glucose-line-chart');
        const glucoseClassification = document.getElementById('tg-glucose-classification');
        const glucoseNumber = new Intl.NumberFormat('ar', { maximumFractionDigits: 1 });

        function renderGlucoseType(type) {
            const stats = glucoseAnalytics[type] || {};
            const readings = Array.isArray(stats.readings) ? stats.readings : [];
            const total = Number(stats.total) || 0;

            document.getElementById('tg-glucose-total').textContent = formatNumber.format(total);
            document.getElementById('tg-glucose-average').textContent = stats.average === null || stats.average === undefined ? '—' : glucoseNumber.format(Number(stats.average));
            document.getElementById('tg-glucose-range').textContent = stats.minimum === null || stats.minimum === undefined || stats.maximum === null || stats.maximum === undefined ? '—' : `${glucoseNumber.format(Number(stats.minimum))} – ${glucoseNumber.format(Number(stats.maximum))}`;
            document.getElementById('tg-glucose-chart-title').textContent = `قراءات ${type} حسب تاريخ التسجيل`;

            glucoseTypeButtons.forEach((button) => {
                button.setAttribute('aria-pressed', String(button.dataset.glucoseType === type));
            });

            glucoseClassification.replaceChildren();
            if (type === 'بعد الوجبة' && Array.isArray(stats.classification) && stats.classification.length) {
                const colors = { 'منخفض': '#E47778', 'ضمن النطاق': '#43A69D', 'مرتفع': '#E2A24E' };
                stats.classification.forEach((item) => {
                    const chip = document.createElement('span');
                    const dot = document.createElement('strong');
                    dot.textContent = `● ${formatNumber.format(Number(item.total) || 0)}`;
                    dot.style.color = colors[item.label] || '#399D9A';
                    chip.append(document.createTextNode(`${item.label} `), dot);
                    glucoseClassification.append(chip);
                });
            }

            if (!readings.length) {
                glucoseChartContainer.innerHTML = '<div class="tg-glucose-empty">لا توجد قراءات مسجلة لهذا النوع حتى الآن.</div>';
                return;
            }

            glucoseChartContainer.replaceChildren();
            const width = 700;
            const height = 220;
            const left = 54;
            const right = 680;
            const top = 18;
            const bottom = 166;
            const values = readings.map((item) => Number(item.value) || 0);
            let minValue = Math.floor(Math.min(...values) / 20) * 20;
            let maxValue = Math.ceil(Math.max(...values) / 20) * 20;
            if (minValue === maxValue) { minValue -= 20; maxValue += 20; }
            const padding = Math.max(10, Math.round((maxValue - minValue) * .12));
            minValue = Math.max(0, minValue - padding);
            maxValue += padding;
            const x = (index) => readings.length === 1 ? (left + right) / 2 : left + (right - left) * index / (readings.length - 1);
            const y = (value) => bottom - ((value - minValue) / (maxValue - minValue)) * (bottom - top);
            const svg = addSvg(glucoseChartContainer, 'svg', { viewBox: `0 0 ${width} ${height}`, role: 'group', 'aria-label': `رسم قراءات ${type} بوحدة mg/dL`, direction: 'ltr' });
            addSvg(svg, 'text', { x: right, y: 12, 'text-anchor': 'end', 'font-size': 11, fill: '#526D78' }, 'mg/dL · كل نقطة تمثل قراءة مسجلة');

            for (let tick = 0; tick <= 4; tick++) {
                const value = minValue + ((maxValue - minValue) * tick / 4);
                const py = y(value);
                addSvg(svg, 'line', { x1: left, x2: right, y1: py, y2: py, stroke: '#D7EAE8', 'stroke-width': 1 });
                addSvg(svg, 'text', { x: left - 9, y: py + 4, 'text-anchor': 'end', 'font-size': 11, fill: '#526D78' }, glucoseNumber.format(value));
            }

            const points = readings.map((item, index) => `${x(index)},${y(Number(item.value) || 0)}`);
            if (readings.length > 1) {
                addSvg(svg, 'polygon', { points: `${x(0)},${bottom} ${points.join(' ')} ${x(readings.length - 1)},${bottom}`, fill: '#48B9B8', 'fill-opacity': .13 });
                addSvg(svg, 'polyline', { points: points.join(' '), fill: 'none', stroke: '#319F9F', 'stroke-width': 3, 'stroke-linecap': 'round', 'stroke-linejoin': 'round' });
            }

            const labelStep = Math.max(1, Math.ceil(readings.length / 7));
            readings.forEach((item, index) => {
                const cx = x(index);
                const cy = y(Number(item.value) || 0);
                const point = addSvg(svg, 'circle', { cx, cy, r: 5.5, fill: '#319F9F', stroke: '#FFFFFF', 'stroke-width': 2, tabindex: 0, role: 'img', 'aria-label': `${item.label || 'قراءة'}: ${glucoseNumber.format(Number(item.value) || 0)} mg/dL` });
                addSvg(point, 'title', {}, `${item.label || 'قراءة'} · ${glucoseNumber.format(Number(item.value) || 0)} mg/dL`);
                if (index % labelStep === 0 || index === readings.length - 1) {
                    addSvg(svg, 'text', { x: cx, y: bottom + 20, 'text-anchor': 'middle', 'font-size': 10, fill: '#526D78' }, item.label || '');
                }
            });
        }

        glucoseTypeButtons.forEach((button) => {
            button.addEventListener('click', () => renderGlucoseType(button.dataset.glucoseType));
        });
        if (glucoseTypeButtons.length) renderGlucoseType('بعد الوجبة');

        drawChart('tg-usage-chart', usageRows, [

            { key: 'total', label: 'حسابات استخدمت التطبيق', color: '#399D9A' },

        ], 'line', 'رسم استخدام تطبيق TinyGuide');


        drawChart('tg-events-chart', eventRows, [

            { key: 'errors', label: 'أخطاء تقنية', color: '#D46A6A' },

            { key: 'security', label: 'تنبيهات أمنية', color: '#C18A38' },

        ], 'bars', 'رسم الأخطاء التقنية والتنبيهات الأمنية');

    })();

</script>

<script>
    (() => {
        const dialog = document.getElementById('tg-dashboard-alerts');
        const hash = '#' + dialog.id;
        let opener;

        function openAlerts() {
            if (!dialog.open) dialog.showModal();
        }

        document.addEventListener('click', (event) => {
            const link = event.target.closest?.('a[href]');
            if (!link || event.defaultPrevented || event.button !== 0
                || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;

            const target = new URL(link.href, location.href);
            if (target.origin !== location.origin || target.pathname !== location.pathname || target.hash !== hash) return;

            event.preventDefault();
            opener = link;
            const menu = link.closest('details');
            if (menu) menu.open = false;
            openAlerts();
        });

        dialog.addEventListener('close', () => {
            if (location.hash === hash) {
                history.replaceState(history.state, '', location.pathname + location.search);
            }
            if (opener) {
                const focusTarget = opener.closest('details')?.querySelector('summary') ?? opener;
                focusTarget.focus({ preventScroll: true });
            }
        });

        if (location.hash === hash) openAlerts();
    })();
</script>

</body>

</html>
