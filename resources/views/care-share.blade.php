










<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#F1F8F8">

    <title>TinyGuide - مشاركة متابعة الطفل</title>



    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Round&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>

        * {

            box-sizing: border-box;

        }



        [hidden] {

            display: none !important;

        }



        :root {

            --primary: #64C7C4;

            --primary-dark: #50B8B5;

            --dark: #2C4A5A;

            --background: #F1F8F8;

            --border: #D8E8E8;

            --muted: #8A9BA2;

            --white: #FFFFFF;

            --meal: #39A45C;

            --activity: #7050C6;

            --glucose: #DE5B5B;

        }



        html {

            min-height: 100%;

            background: var(--background);

        }



        body {

            margin: 0;

            min-height: 100vh;

            padding: 18px 14px 28px;

            background: var(--background);

            color: var(--dark);

            font-family: Tahoma, Arial, sans-serif;

        }



        button,

        input,

        select,

        textarea {

            font: inherit;

        }



        button {

            cursor: pointer;

        }



        button:disabled {

            opacity: .65;

            cursor: wait;

        }



        /* عرض ثابت قريب من عرض شاشة Flutter على الهاتف */

        .page {

            width: 100%;

            max-width: 430px;

            margin: 0 auto;

        }



        /* =====================================

           صفحة إدخال رمز المشاركة

        ===================================== */



        .verify-view {

            width: 100%;

            max-width: 430px;

            margin: 0 auto;

            padding-top: 12px;

        }



        .page-title {

            margin: 0 0 38px;

            color: var(--dark);

            text-align: center;

            font-size: 29px;

            line-height: 1.5;

            font-weight: 700;

        }



        .verify-description {

            margin: -18px 0 26px;

            color: var(--muted);

            text-align: center;

            font-size: 15px;

            line-height: 1.9;

        }



        .verify-form {

            width: 100%;

        }



        .code-field {

            position: relative;

            width: 100%;

            height: 76px;

            margin-bottom: 18px;

        }



        .code-field input {

            width: 100%;

            height: 100%;

            padding: 12px 60px 12px 15px;

            border: 2px solid var(--border);

            border-radius: 22px;

            outline: none;

            background: var(--white);

            color: var(--dark);

            text-align: center;

            font-size: 25px;

            letter-spacing: 10px;

            direction: ltr;

            transition: border-color .2s, box-shadow .2s;

        }



        .code-field input:focus {

            border-color: var(--primary);

            box-shadow: 0 0 0 4px rgba(100, 199, 196, .12);

        }



        .code-field input::placeholder {

            color: #A8B8BD;

            letter-spacing: 12px;

        }



        .code-icon {

            position: absolute;

            top: 50%;

            right: 18px;

            width: 28px;

            height: 28px;

            color: var(--primary);

            transform: translateY(-50%);

            pointer-events: none;

        }



        .verify-options {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;

            margin: 2px 0 25px;

        }



        .remember-label {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            color: var(--dark);

            font-size: 15px;

            cursor: pointer;

            user-select: none;

        }



        .remember-label input {

            appearance: none;

            -webkit-appearance: none;

            width: 25px;

            height: 25px;

            margin: 0;

            border: 2px solid #B9D0D1;

            border-radius: 6px;

            background: white;

            display: grid;

            place-items: center;

            cursor: pointer;

        }



        .remember-label input:checked {

            border-color: var(--primary);

            background: var(--primary);

        }



        .remember-label input:checked::after {

            content: "";

            width: 7px;

            height: 13px;

            border: solid white;

            border-width: 0 3px 3px 0;

            transform: rotate(45deg) translate(-1px, -2px);

        }



        .remember-hint {

            max-width: 185px;

            color: var(--primary-dark);

            font-size: 11px;

            line-height: 1.7;

            text-align: left;

        }



        .main-button {

            width: 100%;

            min-height: 64px;

            padding: 13px 20px;

            border: 0;

            border-radius: 19px;

            background: var(--primary);

            color: white;

            font-size: 19px;

            font-weight: 700;

            transition: background .2s, transform .2s;

        }



        .main-button:hover {

            background: var(--primary-dark);

        }



        .main-button:active {

            transform: scale(.99);

        }



        /* =====================================

           الرسائل

        ===================================== */



        .message {

            width: 100%;

            margin-top: 18px;

            padding: 14px 18px;

            border-radius: 16px;

            font-size: 15px;

            line-height: 1.8;

        }



        .message.error {

            border: 1px solid #F2C9C9;

            background: #FFF1F1;

            color: #B93838;

        }



        .message.success {

            border: 1px solid #BDE5D9;

            background: #ECF9F5;

            color: #237A68;

        }



        /* =====================================

           الصفحة الرئيسية

        ===================================== */



        .dashboard {

            padding-top: 10px;

        }



        .dashboard-heading {

            margin: 0 0 24px;

            color: var(--dark);

            text-align: center;

            font-size: 25px;

            font-weight: 700;

        }



        .child-card {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 14px;

            width: 100%;

            min-height: 128px;

            margin-bottom: 22px;

            padding: 16px 18px;

            border-radius: 22px;

            background: white;

            box-shadow: 0 6px 18px rgba(44, 74, 90, .05);

        }



        .child-info {

            flex: 1;

            min-width: 0;

        }



        .child-name {

            margin: 0;

            color: var(--dark);

            font-size: 20px;

            font-weight: 700;

            line-height: 1.6;

            overflow-wrap: anywhere;

        }



        .child-age {

            display: flex;

            align-items: center;

            gap: 7px;

            margin-top: 8px;

            color: var(--muted);

            font-size: 14px;

        }



        .child-age svg {

            width: 19px;

            height: 19px;

            color: var(--primary);

            flex: 0 0 auto;

        }



        .child-avatar {

            display: flex;

            align-items: center;

            justify-content: center;

            width: 76px;

            height: 76px;

            min-width: 76px;

            border: 4px solid #BDE8E6;

            border-radius: 50%;

            background: #EDF9F8;

            color: var(--primary);

        }



        .child-avatar svg {

            width: 45px;

            height: 45px;

        }



        .records-title {

            margin: 0 0 12px;

            color: var(--dark);

            font-size: 17px;

            font-weight: 700;

        }



        .records-grid {

            display: grid;

            grid-template-columns: repeat(3, minmax(0, 1fr));

            gap: 9px;

            width: 100%;

            max-width: 390px;

            margin: 0 auto;

        }



        .record-card {

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            gap: 8px;

            width: 100%;

            aspect-ratio: 1 / 1;

            min-width: 0;

            padding: 7px 4px;

            border: 1.5px solid #E2E8E9;

            border-radius: 17px;

            background: white;

            color: var(--dark);

            box-shadow: 0 3px 10px rgba(44, 74, 90, .025);

            transition: border-color .2s, transform .2s, box-shadow .2s;

        }



        .record-card:hover {

            border-color: var(--primary);

            box-shadow: 0 7px 18px rgba(44, 74, 90, .07);

            transform: translateY(-3px);

        }



        .record-card:focus-visible {

            outline: 3px solid rgba(100, 199, 196, .35);

            outline-offset: 3px;

        }



        .record-card svg {

            width: 32px;

            height: 32px;

            flex: 0 0 auto;

        }



        .record-card span {

            font-size: 12px;

            font-weight: 700;

            text-align: center;

            line-height: 1.45;

            overflow-wrap: anywhere;

        }



        .meal-card svg {

            color: var(--meal);

        }



        .activity-card svg {

            color: var(--activity);

        }



        .glucose-card svg {

            color: var(--glucose);

        }



        .session-footer {

            display: flex;

            justify-content: center;

            margin-top: 16px;

        }



        .logout-button {

            padding: 10px 18px;

            border: 1px solid #D7E7E7;

            border-radius: 13px;

            background: transparent;

            color: var(--muted);

            font-size: 14px;

        }



        .logout-button:hover {

            border-color: var(--primary);

            color: var(--dark);

        }



        /* =====================================

           نافذة التسجيل

        ===================================== */



        .modal {

            position: fixed;

            inset: 0;

            z-index: 1000;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 18px;

            overflow-y: auto;

            background: rgba(25, 43, 48, .55);

        }



        .modal-content {

            width: 100%;

            max-width: 430px;

            max-height: 92vh;

            overflow-y: auto;

            padding: 20px;

            border-radius: 24px;

            background: var(--background);

            box-shadow: 0 12px 35px rgba(0, 0, 0, .14);

        }



        .modal-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 27px;

        }



        .modal-header h3 {

            margin: 0;

            color: var(--dark);

            font-size: 26px;

            line-height: 1.5;

        }



        .close-button {

            display: grid;

            place-items: center;

            width: 46px;

            height: 46px;

            min-width: 46px;

            border: 0;

            border-radius: 50%;

            background: #E5F1F1;

            color: var(--dark);

            font-size: 27px;

        }



        .form-section-title {

            display: flex;

            align-items: center;

            gap: 10px;

            margin: 0 0 20px;

            color: var(--dark);

            font-size: 21px;

            font-weight: 700;

        }



        .form-section-title svg {

            width: 28px;

            height: 28px;

            color: var(--primary);

        }



        .form-grid {

            display: grid;

            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 18px;

        }



        .form-group {

            min-width: 0;

            margin-bottom: 18px;

        }



        .form-group.full-width {

            grid-column: 1 / -1;

        }



        .form-group label {

            display: block;

            margin: 0 0 10px;

            color: #496879;

            font-size: 17px;

            font-weight: 700;

        }



        .form-control {

            display: block;

            width: 100%;

            min-height: 58px;

            padding: 13px 15px;

            border: 1.5px solid var(--border);

            border-radius: 16px;

            outline: none;

            background: white;

            color: var(--dark);

            font-size: 15px;

            transition: border-color .2s, box-shadow .2s;

        }



        .form-control:focus {

            border-color: var(--primary);

            box-shadow: 0 0 0 4px rgba(100, 199, 196, .10);

        }



        .form-control::placeholder {

            color: #9AAAB0;

        }



        textarea.form-control {

            min-height: 110px;

            resize: vertical;

            line-height: 1.8;

        }



        .form-actions {

            display: flex;

            gap: 12px;

            margin-top: 24px;

        }



        .form-actions button {

            flex: 1;

            min-height: 52px;

            padding: 11px 14px;

            border: 0;

            border-radius: 15px;

            font-size: 15px;

            font-weight: 700;

        }



        .save-button {

            background: var(--primary);

            color: white;

        }



        .save-button:hover {

            background: var(--primary-dark);

        }



        .cancel-button {

            background: #E4EEEE;

            color: var(--dark);

        }



        .toast {

            position: fixed;

            right: 50%;

            bottom: 24px;

            z-index: 2000;

            width: max-content;

            max-width: calc(100% - 32px);

            padding: 15px 22px;

            border-radius: 16px;

            background: #237A68;

            color: white;

            text-align: center;

            line-height: 1.7;

            box-shadow: 0 5px 20px rgba(0, 0, 0, .15);

            transform: translateX(50%);

        }



        /* =====================================

           الشاشات الصغيرة

        ===================================== */



        @media (max-width: 600px) {

            body {

                padding: 18px 14px 30px;

            }



            .verify-view {

                padding-top: 12px;

            }



            .page-title {

                margin-bottom: 34px;

                font-size: 27px;

            }



            .verify-description {

                margin: -15px 0 23px;

                font-size: 14px;

            }



            .code-field {

                height: 70px;

                margin-bottom: 16px;

            }



            .code-field input {

                padding: 12px 62px 12px 15px;

                border-radius: 24px;

                font-size: 27px;

                letter-spacing: 10px;

            }



            .code-icon {

                right: 20px;

                width: 30px;

                height: 30px;

            }



            .verify-options {

                margin: 0 0 30px;

                align-items: flex-start;

            }



            .remember-label {

                gap: 9px;

                font-size: 16px;

            }



            .remember-label input {

                width: 29px;

                height: 29px;

            }



            .remember-label input:checked::after {

                width: 8px;

                height: 15px;

                border-width: 0 3px 3px 0;

            }



            .remember-hint {

                max-width: 145px;

                font-size: 12px;

            }



            .main-button {

                min-height: 60px;

                border-radius: 18px;

                font-size: 18px;

            }



            .dashboard {

                padding-top: 4px;

            }



            .dashboard-heading {

                margin-bottom: 17px;

                font-size: 21px;

            }



            .child-card {

                min-height: 118px;

                gap: 10px;

                margin-bottom: 19px;

                padding: 14px 14px;

                border-radius: 20px;

            }



            .child-name {

                font-size: 19px;

                line-height: 1.5;

            }



            .child-age {

                gap: 6px;

                margin-top: 7px;

                font-size: 13px;

            }



            .child-age svg {

                width: 18px;

                height: 18px;

            }



            .child-avatar {

                width: 70px;

                height: 70px;

                min-width: 70px;

                border-width: 3px;

            }



            .child-avatar svg {

                width: 42px;

                height: 42px;

            }



            .records-title {

                margin-bottom: 10px;

                font-size: 16px;

            }



            .records-grid {

                gap: 8px;

            }



            .record-card {

                gap: 7px;

                padding: 6px 3px;

                border-width: 1.5px;

                border-radius: 15px;

            }



            .record-card span {

                font-size: 11px;

            }



            .record-card svg {

                width: 29px;

                height: 29px;

            }



            .session-footer {

                margin-top: 18px;

            }



            .modal {

                align-items: flex-end;

                padding: 8px;

            }



            .modal-content {

                max-height: 92vh;

                padding: 17px 14px;

                border-radius: 22px 22px 16px 16px;

            }



            .modal-header {

                margin-bottom: 20px;

            }



            .modal-header h3 {

                font-size: 22px;

            }



            .form-grid {

                grid-template-columns: 1fr;

                gap: 0;

            }



            .form-group.full-width {

                grid-column: auto;

            }



            .form-group label {

                font-size: 15px;

            }



            .form-control {

                min-height: 54px;

                padding: 12px 14px;

                border-radius: 15px;

                font-size: 14px;

            }



            .form-actions button {

                min-height: 49px;

                border-radius: 14px;

                font-size: 14px;

            }

        }



        @media (max-width: 350px) {

            body {

                padding-right: 10px;

                padding-left: 10px;

            }



            .records-grid {

                gap: 6px;

            }



            .record-card {

                border-radius: 15px;

            }



            .record-card span {

                font-size: 10px;

            }



            .record-card svg {

                width: 26px;

                height: 26px;

            }



            .child-avatar {

                width: 70px;

                height: 70px;

                min-width: 70px;

            }

        }

    
        /* =====================================
           TinyGuide Flutter sizing adjustments
        ===================================== */

        body {
            padding: 18px 14px 24px;
            font-family: Roboto, "Noto Naskh Arabic", Tahoma, Arial, sans-serif;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        .verify-view {
            padding-top: 10px;
        }

        .page-title {
            margin: 0 0 18px;
            font-size: 29px;
        }

        .verify-description {
            margin: 0 0 18px;
            font-size: 14px;
            line-height: 1.8;
        }

        .code-field {
            height: 58px;
            margin-bottom: 14px;
        }

        .code-field input {
            padding: 12px 52px 12px 16px;
            border: 1.3px solid var(--border);
            border-radius: 15px;
            font-size: 16px;
            letter-spacing: 8px;
        }

        .code-icon {
            right: 16px;
            width: 24px;
            height: 24px;
        }

        .verify-options {
            gap: 10px;
            margin: 0 0 16px;
        }

        .remember-label {
            gap: 6px;
            font-size: 13px;
        }

        .remember-label input {
            width: 20px;
            height: 20px;
            border-width: 1.5px;
            border-radius: 5px;
        }

        .remember-label input:checked::after {
            width: 6px;
            height: 10px;
            border-width: 0 2px 2px 0;
        }

        .remember-hint {
            max-width: 160px;
            font-size: 11px;
        }

        .main-button {
            min-height: 56px;
            padding: 12px 18px;
            border-radius: 15px;
            font-size: 18px;
        }

        .message {
            margin-top: 14px;
            padding: 11px 14px;
            border-radius: 12px;
            font-size: 13px;
        }

        .dashboard {
            padding-top: 8px;
        }

        .dashboard-heading {
            margin-bottom: 18px;
            font-size: 22px;
        }

        .child-card {
            min-height: 108px;
            margin-bottom: 18px;
            padding: 14px;
            border-radius: 18px;
        }

        .child-name {
            font-size: 18px;
        }

        .child-age {
            font-size: 13px;
        }

        .child-avatar {
            width: 64px;
            height: 64px;
            min-width: 64px;
            border-width: 3px;
        }

        .child-avatar svg {
            width: 38px;
            height: 38px;
        }

        .records-title {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .records-grid {
            gap: 8px;
        }

        .record-card {
            gap: 6px;
            padding: 6px 4px;
            border-radius: 15px;
        }

        .record-card svg {
            width: 28px;
            height: 28px;
        }

        .record-card span {
            font-size: 11px;
        }

        .logout-button {
            padding: 9px 16px;
            font-size: 13px;
        }

        .modal-content {
            padding: 18px;
            border-radius: 20px;
        }

        .modal-header {
            margin-bottom: 18px;
        }

        .modal-header h3 {
            font-size: 21px;
        }

        .close-button {
            width: 38px;
            height: 38px;
            min-width: 38px;
            font-size: 23px;
        }

        .form-section-title {
            margin-bottom: 14px;
            font-size: 17px;
        }

        .form-section-title svg {
            width: 23px;
            height: 23px;
        }

        .form-grid {
            gap: 12px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            margin-bottom: 7px;
            font-size: 14px;
        }

        .form-control {
            min-height: 54px;
            padding: 12px 14px;
            border-radius: 15px;
            font-size: 14px;
        }

        textarea.form-control {
            min-height: 90px;
        }

        .form-actions {
            gap: 10px;
            margin-top: 18px;
        }

        .form-actions button {
            min-height: 50px;
            border-radius: 14px;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px 28px 24px;
            }

            .page-title {
                margin-bottom: 16px;
                font-size: 29px;
            }

            .verify-description {
                margin-bottom: 16px;
                font-size: 14px;
            }

            .code-field {
                height: 56px;
                margin-bottom: 12px;
            }

            .code-field input {
                padding: 10px 48px 10px 14px;
                font-size: 16px;
                letter-spacing: 7px;
            }

            .code-icon {
                right: 15px;
                width: 23px;
                height: 23px;
            }

            .verify-options {
                margin-bottom: 14px;
            }

            .main-button {
                min-height: 52px;
                font-size: 16px;
            }

            .dashboard-heading {
                margin-bottom: 16px;
                font-size: 21px;
            }

            .child-card {
                min-height: 102px;
                margin-bottom: 16px;
                padding: 12px;
            }

            .child-name {
                font-size: 18px;
            }

            .child-avatar {
                width: 62px;
                height: 62px;
                min-width: 62px;
            }

            .child-avatar svg {
                width: 36px;
                height: 36px;
            }

            .records-grid {
                gap: 7px;
            }

            .record-card svg {
                width: 27px;
                height: 27px;
            }

            .record-card span {
                font-size: 10.5px;
            }

            .modal {
                padding: 8px;
            }

            .modal-content {
                padding: 16px 14px;
                border-radius: 20px 20px 16px 16px;
            }

            .modal-header h3 {
                font-size: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-group.full-width {
                grid-column: auto;
            }

            .form-group label {
                font-size: 14px;
            }

            .form-control {
                min-height: 52px;
                padding: 11px 13px;
                font-size: 14px;
            }

            .form-actions button {
                min-height: 48px;
                font-size: 14px;
            }
        }

        @media (max-width: 359px) {
            body {
                padding: 14px 18px 22px;
            }

            .page-title {
                font-size: 25px;
            }

            .verify-description {
                font-size: 13px;
            }

            .child-card {
                min-height: 96px;
            }

            .child-avatar {
                width: 58px;
                height: 58px;
                min-width: 58px;
            }

            .child-avatar svg {
                width: 34px;
                height: 34px;
            }

            .records-grid {
                gap: 6px;
            }

            .record-card {
                padding: 5px 2px;
            }

            .record-card svg {
                width: 25px;
                height: 25px;
            }

            .record-card span {
                font-size: 10px;
            }
        }

    
        /* =====================================
           تحسين الخط والمسافات - تسجيل الدخول والصفحة الرئيسية
        ===================================== */

        body,
        button,
        input,
        select,
        textarea {
            font-family: Tahoma, Arial, sans-serif;
            font-weight: 400;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        strong,
        b,
        .page-title,
        .main-button,
        .dashboard-heading,
        .child-name,
        .records-title,
        .record-card span,
        .remember-label,
        .form-section-title,
        .form-group label,
        .form-actions button {
            font-weight: 400 !important;
        }

        /* شاشة إدخال رمز المشاركة */
        .verify-view {
            padding-top: 20px;
        }

        .page-title {
            margin: 0 0 24px;
            font-size: 24px;
            line-height: 1.7;
        }

        .verify-description {
            margin: 0 0 28px;
            font-size: 14px;
            line-height: 2;
        }

        .code-field {
            height: 52px;
            margin-bottom: 22px;
        }

        .code-field input {
            padding: 10px 48px 10px 14px;
            border-width: 1.3px;
            border-radius: 14px;
            font-size: 16px;
            letter-spacing: 7px;
        }

        .code-icon {
            right: 15px;
            width: 23px;
            height: 23px;
        }

        .verify-options {
            gap: 16px;
            margin: 0 0 24px;
        }

        .remember-label {
            gap: 7px;
            font-size: 13px;
        }

        .remember-label input {
            width: 20px;
            height: 20px;
        }

        .remember-hint {
            max-width: 155px;
            font-size: 11px;
            line-height: 1.9;
        }

        .main-button {
            min-height: 48px;
            padding: 10px 16px;
            border-radius: 14px;
            font-size: 16px;
        }

        .message {
            margin-top: 24px;
            padding: 12px 15px;
            border-radius: 13px;
            font-size: 13px;
            line-height: 1.9;
        }

        /* الصفحة الرئيسية */
        .dashboard {
            padding-top: 18px;
        }

        .dashboard-heading {
            margin: 0 0 26px;
            font-size: 21px;
            line-height: 1.7;
        }

        .child-card {
            min-height: 96px;
            margin-bottom: 26px;
            padding: 12px 14px;
            gap: 14px;
            border-radius: 17px;
        }

        .child-name {
            font-size: 17px;
            line-height: 1.8;
        }

        .child-age {
            gap: 7px;
            margin-top: 10px;
            font-size: 12px;
            line-height: 1.8;
        }

        .child-avatar {
            width: 60px;
            height: 60px;
            min-width: 60px;
            border-width: 2px;
        }

        .child-avatar svg {
            width: 36px;
            height: 36px;
        }

        .records-title {
            margin: 0 0 18px;
            font-size: 15px;
            line-height: 1.8;
        }

        .records-grid {
            width: 100%;
            max-width: 360px;
            gap: 12px;
        }

        .record-card {
            aspect-ratio: 1 / 1;
            gap: 8px;
            padding: 8px 5px;
            border-radius: 15px;
        }

        .record-card svg {
            width: 26px;
            height: 26px;
        }

        .record-card span {
            font-size: 11px;
            line-height: 1.6;
        }

        .session-footer {
            margin-top: 24px;
        }

        .logout-button {
            padding: 9px 16px;
            font-size: 13px;
        }

        /* نوافذ التسجيل */
        .modal-content {
            padding: 18px 16px;
        }

        .modal-header {
            margin-bottom: 24px;
        }

        .modal-header h3 {
            font-size: 20px;
            line-height: 1.7;
            font-weight: 400;
        }

        .form-section-title {
            margin-bottom: 18px;
            font-size: 17px;
            line-height: 1.8;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            margin-bottom: 9px;
            font-size: 14px;
        }

        .form-control {
            min-height: 52px;
            padding: 11px 14px;
            font-size: 14px;
        }

        .form-actions {
            gap: 12px;
            margin-top: 22px;
        }

        .form-actions button {
            min-height: 48px;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            body {
                padding: 18px 20px 26px;
            }

            .verify-view {
                padding-top: 18px;
            }

            .page-title {
                margin-bottom: 22px;
                font-size: 24px;
            }

            .verify-description {
                margin-bottom: 26px;
                font-size: 14px;
            }

            .code-field {
                height: 52px;
                margin-bottom: 20px;
            }

            .verify-options {
                margin-bottom: 22px;
            }

            .main-button {
                min-height: 48px;
                font-size: 16px;
            }

            .dashboard {
                padding-top: 14px;
            }

            .dashboard-heading {
                margin-bottom: 24px;
                font-size: 20px;
            }

            .child-card {
                min-height: 94px;
                margin-bottom: 24px;
                padding: 11px 12px;
            }

            .child-name {
                font-size: 16px;
            }

            .child-age {
                margin-top: 9px;
                font-size: 12px;
            }

            .child-avatar {
                width: 58px;
                height: 58px;
                min-width: 58px;
            }

            .records-title {
                margin-bottom: 17px;
                font-size: 15px;
            }

            .records-grid {
                max-width: 350px;
                gap: 10px;
            }

            .record-card {
                gap: 7px;
                padding: 7px 4px;
            }

            .record-card svg {
                width: 25px;
                height: 25px;
            }

            .record-card span {
                font-size: 10.5px;
            }

            .session-footer {
                margin-top: 22px;
            }

            .modal-content {
                padding: 16px 14px;
            }

            .modal-header {
                margin-bottom: 21px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-actions {
                margin-top: 20px;
            }
        }

        @media (max-width: 359px) {
            body {
                padding-right: 15px;
                padding-left: 15px;
            }

            .page-title {
                font-size: 22px;
            }

            .verify-description {
                font-size: 13px;
            }

            .remember-hint {
                max-width: 135px;
                font-size: 10px;
            }

            .records-grid {
                gap: 7px;
            }

            .record-card svg {
                width: 23px;
                height: 23px;
            }

            .record-card span {
                font-size: 10px;
            }
        }


        /* TinyGuide Flutter-aligned visual layer. Functional forms/API remain unchanged. */
        :root { --primary:#64C7C4; --dark:#2C4A5A; --background:#F1F8F8; --border:#D7E7E7; --meal:#469E55; --activity:#6654B8; --glucose:#D95C5C; }
        body,button,input,select,textarea { font-family:'Noto Sans Arabic',Tahoma,Arial,sans-serif !important; }
        body { padding:20px 18px 36px; }
        .page { max-width:560px; }
        .material-icons-round { font-family:'Material Icons Round' !important; font-weight:normal !important; font-style:normal; font-size:24px; line-height:1; letter-spacing:normal; text-transform:none; display:inline-block; white-space:nowrap; word-wrap:normal; direction:ltr; -webkit-font-feature-settings:'liga'; -webkit-font-smoothing:antialiased; font-feature-settings:'liga'; }
        .verify-view { max-width:560px; padding-top:clamp(56px,12vh,110px); }
        .page-title { font-size:29px; font-weight:700 !important; line-height:1.7; margin:0 0 32px; text-align:center; }
        .verify-description { font-size:14px; font-weight:400 !important; line-height:1.9; text-align:center; margin:0 0 34px; color:#607D83; }
        .verify-form { width:100%; }
        .code-field { height:56px; margin:0 0 24px; }
        .code-field input { height:56px; border:1.3px solid #D7E7E7; border-radius:15px; background:#fff; font-size:16px; font-weight:400 !important; letter-spacing:5px; padding:12px 52px 12px 18px; }
        .code-icon { right:16px; width:24px; height:24px; color:var(--primary); }
        .verify-options { display:none !important; }
        .main-button { min-height:56px; border-radius:15px; font-size:18px; font-weight:600 !important; margin-top:0; }
        .dashboard { padding-top:24px; }
        .dashboard-heading { font-size:24px; font-weight:600 !important; text-align:right; margin:0 0 24px; line-height:1.7; }
        .child-card { min-height:0; padding:16px 18px; margin-bottom:34px; border-radius:20px; gap:16px; display:flex; flex-direction:row; align-items:center; background:#fff; border:1px solid rgba(44,74,90,.1); box-shadow:0 5px 12px rgba(0,0,0,.04); }
        .child-info { min-width:0; flex:1; }
        .child-name { font-size:19px; font-weight:600 !important; line-height:1.6; margin:0 0 10px; overflow-wrap:anywhere; }
        .child-meta { display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
        .child-age,.child-gender { display:flex; align-items:center; gap:5px; margin:0; font-size:13px; font-weight:400 !important; color:#607D83; }
        .child-age svg { width:19px; height:19px; color:var(--primary); }
        .child-gender .material-icons-round { font-size:19px; color:var(--primary); }
        .child-avatar { flex:0 0 64px; width:64px; height:64px; min-width:64px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:rgba(100,199,196,.12); border:2px solid rgba(100,199,196,.35); color:var(--primary); }
        .child-avatar .material-icons-round { font-size:40px; }
        .records-title { font-size:17px; font-weight:600 !important; margin:0 0 18px; line-height:1.7; text-align:right; }
        .records-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:9px; align-items:stretch; }
        .record-card { min-width:0; width:100%; min-height:104px; padding:14px 4px; border-radius:16px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:9px; background:#fff; border:1.2px solid rgba(44,74,90,.12); box-shadow:none; }
        .record-card .record-icon { font-size:34px; }
        .meal-card .record-icon { color:#469E55; }
        .glucose-card .record-icon { color:#D95C5C; }
        .activity-card .record-icon { color:#6654B8; }
        .record-card span:not(.record-icon) { font-size:12px; font-weight:600 !important; line-height:1.5; text-align:center; }
        .session-footer { margin-top:38px; }
        .form-section-title,.form-group label { font-weight:500 !important; }
        @media(max-width:380px) {
          body { padding-left:14px; padding-right:14px; }
          .child-card { padding:13px 12px; gap:10px; }
          .child-name { font-size:17px; }
          .child-avatar { flex-basis:56px; width:56px; height:56px; min-width:56px; }
          .child-avatar .material-icons-round { font-size:35px; }
          .record-card { min-height:94px; padding:12px 2px; }
          .record-card .record-icon { font-size:31px; }
          .record-card span:not(.record-icon) { font-size:11px; }
          .records-grid { gap:8px; }
        }

    
/* Flutter-inspired Care Share forms */
.flutter-form{display:flex;flex-direction:column;gap:17px;text-align:right;direction:rtl;padding-top:12px}
.flutter-form .form-group{margin:0;min-width:0}
.flutter-form .form-group label,.share-food-sheet label{display:block;font-size:13px;font-weight:600;margin-bottom:8px;color:var(--text-color,#2c4a5a)}
.flutter-form .form-control,.share-food-sheet .form-control{width:100%;min-width:0;box-sizing:border-box;border:1.3px solid #d7e7e7;border-radius:14px;min-height:52px;padding:12px 15px;background:#fff;font-size:14px;font-weight:400;direction:rtl;color:#2c4a5a}
.flutter-form .form-control:focus,.share-food-sheet .form-control:focus{outline:0;border-color:#168e88;box-shadow:0 0 0 2px #168e8820}
.flutter-form .form-control[hidden],.share-food-overlay[hidden],.flutter-form [hidden]{display:none!important}
.share-pair{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.share-input-icon{position:relative;display:flex;align-items:center}.share-input-icon>.material-icons-round{position:absolute;right:12px;z-index:1;color:#138f88;font-size:21px;pointer-events:none}.share-input-icon>.form-control{padding-right:42px}
.share-section{display:flex;align-items:center;gap:8px;font-size:18px;font-weight:700;margin:5px 0 3px;color:#2c4a5a}.share-section .material-icons-round{color:#138f88;font-size:24px}
.share-subtitle{text-align:center;color:#8a989d;font-size:13px;margin:0 0 10px;font-weight:400}
.share-notice{display:flex;align-items:center;justify-content:center;gap:8px;padding:13px;background:#eef7fa;border-radius:13px;font-size:12px;font-weight:400;color:#2c4a5a;margin-top:20px}.share-notice .material-icons-round{color:#138f88;font-size:20px}
.share-actions{margin-top:8px;display:flex;gap:10px}.share-actions button{flex:1;border-radius:12px;min-height:44px;font-size:15px}
.share-custom{margin-top:8px!important}.share-food-trigger{display:flex;align-items:center;gap:10px;text-align:right;width:100%;border:1.3px solid #d7e7e7;background:white;border-radius:12px;padding:14px;color:#8a989d;font-size:13px;min-height:52px}.share-food-trigger span:nth-child(2){flex:1}.share-food-trigger .material-icons-round{color:#138f88;font-size:21px}
.share-radio-row{display:flex;gap:8px;justify-content:space-between}.share-radio{display:flex!important;flex:1;align-items:center;justify-content:center;gap:5px;font-size:12px!important;font-weight:500!important;cursor:pointer;white-space:normal}.share-radio input{accent-color:#138f88;width:18px;height:18px;flex-shrink:0}
.share-tips{background:#fff9e8;border:1px solid #f1e4b8;border-radius:15px;padding:15px}.share-tips strong{display:flex;align-items:center;gap:8px;font-size:15px;color:#2c4a5a}.share-tips strong .material-icons-round{color:#d7a82c}.share-tips p{font-size:12.5px;line-height:1.9;color:#66777d;margin:8px 0 0}
.share-meal-help{font-size:12px;line-height:1.8;padding:13px;margin-top:10px;background:#eef7fa;border-radius:12px;color:#607d83}.share-validation{color:#c62828;font-size:12px;margin:3px 0}
.share-food-overlay{position:fixed;inset:0;background:#0007;z-index:99999;display:flex;align-items:flex-end;justify-content:center}.share-food-sheet{background:white;width:min(100%,620px);max-height:85dvh;overflow:auto;border-radius:22px 22px 0 0;padding:18px;box-sizing:border-box;display:flex;flex-direction:column;gap:12px;direction:rtl}.share-sheet-header{display:flex;justify-content:space-between;align-items:center}.share-sheet-header strong{display:flex;gap:8px;align-items:center}.share-sheet-header button{border:0;background:transparent}.share-food-choices{display:flex;flex-wrap:wrap;gap:8px;padding:10px 0;min-height:35px}.share-chip{border:1px solid #d7e7e7;border-radius:18px;padding:7px 12px;background:#f7f9f9;color:#2c4a5a;font-size:13px}.share-chip.chosen{background:#e1f3f1;border-color:#138f88;color:#137c75}.share-add-food{display:flex;gap:8px}.share-add-food button{border:0;border-radius:10px;background:#e1f3f1;color:#138f88;padding:0 15px}.share-food-sheet h4{font-size:14px;font-weight:600;margin:8px 0 0}.share-food-sheet h4 small{font-size:12px;font-weight:400;margin-right:8px}.share-confirm{min-height:45px;border-radius:12px}
@media(max-width:389px){.share-pair{grid-template-columns:1fr}.flutter-form{gap:16px}.share-section{font-size:17px}.share-radio{font-size:11px!important}}

/* Exact layout reference: Flutter ActivityScreen, MealScreen, GlucoseReadingScreen */
body:has(#recordModal:not([hidden])){overflow:hidden}
#recordModal.modal:not([hidden]){position:fixed;inset:0;z-index:1000;background:#f5faf9;padding:0;display:block;overflow:hidden}
#recordModal .modal-content{position:relative;box-sizing:border-box;width:100%;max-width:720px;height:100dvh;max-height:100dvh;margin:0 auto;padding:0 20px 28px;border-radius:0;background:#f5faf9;box-shadow:none;overflow-y:auto;overscroll-behavior:contain}
#recordModal .modal-header{position:sticky;top:0;z-index:20;display:grid;grid-template-columns:90px minmax(0,1fr) 90px;align-items:center;min-height:76px;margin:0 0 27px;padding:10px 0;background:#f5faf9;gap:0}
#recordModal .modal-header h3{grid-column:2;grid-row:1;text-align:center;font-size:21px!important;font-weight:700!important;color:#2c4a5a;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
#recordModal .close-button{grid-column:1;grid-row:1;justify-self:start;background:transparent!important;width:44px;height:44px;min-width:44px;border-radius:10px;font-size:0!important;transform:none}
#recordModal .close-button::before{content:'chevron_right';font-family:'Material Icons Round';font-size:30px;color:#2c4a5a}
#recordModal .share-top-save{grid-column:3;grid-row:1;justify-self:end;min-width:82px;height:44px;padding:0 14px;border:0;border-radius:12px;background:#3ba9a3;color:white;font-size:15px!important;font-weight:700!important;cursor:pointer}
#recordModal .share-top-save:disabled{opacity:.6}
#recordModal .flutter-form{padding-top:0;gap:18px;min-height:calc(100dvh - 155px);display:flex;flex-direction:column}
#recordModal .share-section{font-size:18px!important;font-weight:700!important;margin:12px 0 0;gap:7px;line-height:1.7}
#recordModal .share-section .material-icons-round{font-size:24px;color:#3ba9a3}
#recordModal .share-pair{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
#recordModal .flutter-form .form-group{min-width:0}
#recordModal .flutter-form .form-group label{font-size:13px!important;font-weight:600!important;color:#2c4a5a;margin:0 0 9px}
#recordModal .flutter-form .form-control{min-height:56px;border:1.3px solid #d7e7e7!important;border-radius:14px!important;background:#fff;color:#2c4a5a;font-size:13px!important;font-weight:400!important;padding:13px 13px;box-shadow:none;max-width:100%}
#recordModal .share-input-icon>.form-control{padding-right:43px!important}
#recordModal .share-input-icon>.material-icons-round{right:14px;font-size:22px;color:#3ba9a3}
#recordModal .share-food-trigger{min-height:57px;border:1.3px solid #d7e7e7;border-radius:13px;background:#fff;padding:13px 14px;font-size:13px;gap:10px}
#recordModal .share-food-trigger .material-icons-round{color:#3ba9a3}
#recordModal .share-subtitle{font-size:13px!important;color:#8a989d;margin:0 0 22px;text-align:center}
#recordModal .share-tips{padding:15px 15px 18px;margin:0 0 12px;background:#fff9e8;border:1px solid #f1e4b8;border-radius:15px}
#recordModal .share-tips strong{font-size:15px;font-weight:700!important}
#recordModal .share-tips p{line-height:2.1;font-size:12.5px;margin-top:8px}
#recordModal .share-radio-row{gap:4px;justify-content:space-between;direction:rtl}
#recordModal .share-radio{display:flex!important;flex-direction:row-reverse;gap:6px;justify-content:center;min-width:0;padding:8px 0;line-height:1.6;font-size:12px!important;font-weight:500!important}
#recordModal .share-radio input{accent-color:#3ba9a3;width:19px;height:19px}
#recordModal .share-notice{margin-top:auto;margin-bottom:18px;background:transparent;font-size:12px;color:#71868b;padding:28px 0 15px}
#recordModal .share-notice .material-icons-round{color:#3ba9a3}
#recordModal .share-food-overlay:not([hidden]){z-index:1005;align-items:flex-end;background:#0007}
#recordModal .share-food-sheet{width:min(100%,720px);height:80dvh;max-height:85dvh;border-radius:22px 22px 0 0;padding:15px 16px 18px;gap:12px;box-shadow:0 -8px 25px #0002}
#recordModal .share-sheet-header{min-height:55px;border-bottom:1px solid #e0e7e7;margin:0 -16px;padding:0 16px 10px}
#recordModal .share-sheet-header strong{font-size:17px;font-weight:700!important}
#recordModal .share-sheet-header .material-icons-round{color:#3ba9a3}
#recordModal .share-food-choices{gap:8px}
#recordModal .share-chip{border-radius:20px;padding:7px 12px}
#recordModal .share-confirm{margin-top:auto;background:#3ba9a3;color:#fff;border:0;font-weight:700!important;min-height:50px}
#recordModal .share-meal-help{line-height:1.9}
#recordModal input[type=date],#recordModal input[type=time]{color-scheme:light}
@media(max-width:389px){#recordModal .modal-content{padding:0 14px 22px}#recordModal .modal-header{grid-template-columns:65px minmax(0,1fr) 65px}#recordModal .modal-header h3{font-size:17px!important}#recordModal .share-top-save{min-width:65px;padding:0 9px;font-size:13px!important}#recordModal .share-pair{grid-template-columns:1fr}#recordModal .share-radio{font-size:11px!important}}


/* Flutter screenshot matching, 382px responsive preview */
#recordModal .modal-content{padding:0 20px 20px!important;background:#f7fbfa!important}
#recordModal .modal-header{min-height:66px!important;margin-bottom:20px!important;padding:6px 0!important;background:#f7fbfa!important}
#recordModal .flutter-form{gap:13px!important;min-height:calc(100dvh - 130px)!important}
#recordModal .share-section{margin:6px 0 5px!important;font-size:17px!important}
#recordModal .flutter-form .form-control,#recordModal .share-food-trigger{min-height:52px!important;height:52px!important;border-radius:12px!important;font-size:13px!important}
#recordModal .share-food-trigger{padding:10px 13px!important}
#recordModal .share-pair{grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important;gap:10px!important}
#recordModal .share-date-time{margin-top:0!important}
#recordModal .share-picker-group{margin:0!important}
#recordModal .share-picker-shell{position:relative;display:flex;align-items:center;gap:10px;height:53px;padding:0 12px;border:1.2px solid #d7e7e7;border-radius:12px;background:white;cursor:pointer;color:#8a989d;overflow:hidden}
#recordModal .share-picker-shell>.material-icons-round{font-size:21px;flex:none;color:#469e9b}
#recordModal .share-picker-text{font-size:12px;color:#8a989d;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex:1;text-align:right}
#recordModal .share-picker-text.selected{color:#2c4a5a}
#recordModal .share-picker-shell input{position:absolute!important;inset:0!important;width:100%!important;height:100%!important;opacity:0!important;cursor:pointer!important;z-index:2!important}
#recordModal .share-saved-meals{display:flex;align-items:center;gap:9px;min-height:58px;padding:8px 12px;border:1.2px solid #d7e7e7;border-radius:12px;background:white;font-size:13px;font-weight:600;color:#2c4a5a}
#recordModal .share-saved-meals .material-icons-round{font-size:21px;color:#469e9b}
#recordModal .share-saved-meals span:nth-child(2){flex:1}
#recordModal .share-saved-meals small{display:block;font-size:10px;color:#8a989d;font-weight:400}
#recordModal .share-notice{margin-top:auto!important;padding:14px 0!important;margin-bottom:4px!important;font-size:11px!important}
#recordModal .share-top-save{height:44px!important;min-width:74px!important}
@media(max-width:389px){#recordModal .modal-content{padding:0 14px 18px!important}#recordModal .share-pair{grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important}#recordModal .share-picker-shell{padding:0 8px;gap:5px}#recordModal .share-picker-text{font-size:11px}}

/* Final Flutter-aligned layout: three compact cards in one row */
#dashboardView .records-grid{display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:9px!important;direction:rtl;align-items:stretch}
#dashboardView .records-grid .record-card:not([hidden]){display:flex!important;flex-direction:column!important;justify-content:center!important;align-items:center!important;min-width:0!important;width:100%!important;height:94px!important;min-height:94px!important;padding:10px 3px!important;border-radius:16px!important;gap:6px!important;background:#fff!important;border:1.2px solid rgba(44,74,90,.12)!important;box-shadow:none!important}
#dashboardView .record-card .record-icon{font-size:32px!important;line-height:1!important;flex:none}
#dashboardView .record-card span:not(.record-icon){font-size:12px!important;line-height:1.4!important;font-weight:400!important;white-space:nowrap}
#dashboardView .meal-card .record-icon{color:#469e55!important}
#dashboardView .activity-card .record-icon{color:#6654b8!important}
#dashboardView .glucose-card .record-icon{color:#d95c5c!important}
@media(max-width:380px){#dashboardView .records-grid{gap:7px!important}#dashboardView .records-grid .record-card:not([hidden]){height:88px!important;min-height:88px!important}#dashboardView .record-card span:not(.record-icon){font-size:11px!important}}
/* Consistent lighter Arabic typography across all three recording forms */
#recordModal .modal-header h3{font-weight:500!important;font-size:20px!important}
#recordModal .share-section{font-weight:500!important;font-size:17px!important}
#recordModal .flutter-form .form-group label{font-weight:400!important}
#recordModal .flutter-form .form-control,#recordModal .share-picker-text,#recordModal .share-food-trigger{font-weight:400!important}
/* Hide native dropdown arrow and draw one Material arrow at the left */
#recordModal .share-input-icon select.form-control{appearance:none!important;-webkit-appearance:none!important;-moz-appearance:none!important;padding-left:34px!important;padding-right:39px!important;background-image:none!important}
#recordModal .share-input-icon:has(select)::after{content:'keyboard_arrow_down';font-family:'Material Icons Round';font-weight:normal;font-style:normal;font-size:22px;line-height:1;position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#469e9b;pointer-events:none;z-index:2}
#recordModal .share-food-trigger>.material-icons-round:last-child{font-size:22px!important;color:#469e9b!important}
#recordModal .share-picker-shell{min-width:0}
#recordModal .share-picker-text.selected{color:#607d83!important}

/* Final consistency pass: matching child card and three record cards */
#dashboardView .child-card{
    height:94px!important;min-height:94px!important;max-height:94px!important;
    margin:0 0 22px!important;padding:10px 14px!important;gap:12px!important;
    border-radius:16px!important;box-shadow:none!important;
    display:flex!important;align-items:center!important;overflow:hidden;
}
#dashboardView .child-info{min-width:0;display:flex;flex-direction:column;justify-content:center;gap:3px}
#dashboardView .child-name{font-size:16px!important;line-height:1.45!important;font-weight:400!important;margin:0!important;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
#dashboardView .child-meta{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
#dashboardView .child-age,#dashboardView .child-gender{font-size:11px!important;line-height:1.4!important;font-weight:400!important;gap:4px}
#dashboardView .child-avatar{flex:0 0 58px!important;width:58px!important;height:58px!important;min-width:58px!important}
#dashboardView .records-grid{gap:12px!important}
#dashboardView .records-grid .record-card:not([hidden]){height:94px!important;min-height:94px!important;max-height:94px!important}
#dashboardView .records-title{font-size:15px!important;font-weight:400!important;margin:0 0 13px!important}
#dashboardView .record-card span:not(.record-icon){font-weight:400!important}
@media(max-width:380px){
  #dashboardView .child-card{height:88px!important;min-height:88px!important;max-height:88px!important;padding:8px 10px!important}
  #dashboardView .records-grid{gap:8px!important}
  #dashboardView .records-grid .record-card:not([hidden]){height:88px!important;min-height:88px!important;max-height:88px!important}
}

/* Custom date and time pickers: same teal/white visual language as Flutter */
#recordModal .share-picker-shell{cursor:pointer!important}
#recordModal .share-picker-value{
    position:absolute!important;width:1px!important;height:1px!important;
    opacity:0!important;pointer-events:none!important;inset:auto!important;
}
#recordModal .share-picker-shell>.material-icons-round{color:#469e9b!important}
#recordModal .share-picker-text{font-family:'Noto Sans Arabic',Tahoma,Arial,sans-serif!important;font-weight:400!important}
.share-native-picker-overlay{
    position:fixed;inset:0;z-index:1000000;display:flex;align-items:center;justify-content:center;
    padding:18px;background:rgba(28,43,48,.48);direction:rtl;
}
.share-native-picker-overlay[hidden]{display:none!important}
.share-native-picker{
    width:min(100%,390px);max-height:min(88dvh,680px);overflow:auto;
    padding:22px 20px 18px;border-radius:25px;background:#fff;
    box-shadow:0 18px 50px rgba(0,0,0,.2);color:#2c4a5a;
    font-family:'Noto Sans Arabic',Tahoma,Arial,sans-serif;
}
.share-picker-heading{display:flex;align-items:center;justify-content:space-between;gap:12px;color:#71858b;font-size:14px;font-weight:400}
.share-picker-close{width:36px;height:36px;border:0;border-radius:50%;display:grid;place-items:center;background:#f0f7f6;color:#607d83}
.share-picker-close .material-icons-round{font-size:21px}
.share-date-summary{font-size:25px;font-weight:400;color:#2c4a5a;margin:24px 0 20px;text-align:right}
.share-month-row{display:grid;grid-template-columns:42px 1fr 42px;align-items:center;gap:8px;padding:12px 0;border-top:1px solid #edf2f2;border-bottom:1px solid #edf2f2}
.share-month-row strong{text-align:center;font-size:15px;font-weight:500}
.share-month-row button{height:38px;width:38px;border:0;border-radius:50%;background:transparent;color:#607d83;display:grid;place-items:center}
.share-month-row button:disabled{opacity:.25}
.share-month-row .material-icons-round{font-size:25px}
.share-calendar-grid{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:5px 2px;padding:14px 0 18px}
.share-weekday,.share-calendar-day,.share-calendar-empty{height:42px;display:grid;place-items:center}
.share-weekday{font-size:12px;color:#84969b}
.share-calendar-day{border:0;border-radius:50%;background:transparent;color:#344e5c;font-size:14px;font-weight:400}
.share-calendar-day:disabled{color:#c8d1d3;cursor:not-allowed}
.share-calendar-day.selected{background:#138f88;color:#fff;font-weight:500}
.share-calendar-day:not(:disabled):not(.selected):hover{background:#e7f5f3}
.share-picker-actions{display:flex;justify-content:flex-end;gap:12px;padding-top:12px}
.share-picker-cancel,.share-picker-confirm{min-width:82px;height:42px;padding:0 16px;border:0;border-radius:12px;background:#eef5f4;color:#607d83;font-family:'Noto Sans Arabic',Tahoma,Arial,sans-serif;font-size:14px;font-weight:400}
.share-picker-confirm{background:#168f88;color:#fff}
.share-time-display{display:flex;align-items:center;justify-content:center;gap:12px;margin:28px auto 24px;color:#243f4c;font-size:54px;font-weight:400;direction:ltr}
.share-time-display span{min-width:82px;padding:8px 12px;border-radius:12px;background:#e1f5f2;text-align:center}
.share-time-display b{font-weight:400;color:#789096}
.share-time-selectors{display:grid;grid-template-columns:1fr 1fr auto;gap:10px;align-items:end}
.share-time-selectors label{display:flex;flex-direction:column;gap:7px;color:#71858b;font-size:12px}
.share-time-selectors select{width:100%;height:44px;border:1px solid #d7e7e7;border-radius:11px;background:#fff;color:#2c4a5a;padding:0 10px;font:400 15px 'Noto Sans Arabic',Tahoma,Arial,sans-serif}
.share-period-switch{display:flex;flex-direction:column;border:1px solid #d7e7e7;border-radius:11px;overflow:hidden}
.share-period-switch button{width:48px;height:22px;border:0;background:#fff;color:#71858b;font-size:12px}
.share-period-switch button.active{background:#dff3f0;color:#087f78;font-weight:500}
@media(max-width:420px){
 .share-native-picker{padding:19px 15px 15px;border-radius:23px}
 .share-date-summary{font-size:23px}
 .share-calendar-day,.share-weekday,.share-calendar-empty{height:39px}
 .share-time-display{font-size:48px}
 .share-time-display span{min-width:72px}
 .share-time-selectors{gap:7px}
}


/* Compact Flutter-like date/time dialogs and full-width aligned record cards */
#dashboardView .records-grid{width:100%!important;max-width:none!important;margin:0!important;gap:16px!important;grid-template-columns:repeat(3,minmax(0,1fr))!important}
#dashboardView .child-card{width:100%!important}
.share-native-picker-overlay{padding:20px!important}
.share-native-picker{width:min(100%,330px)!important;max-height:min(85dvh,560px)!important;padding:15px 16px 14px!important;border-radius:23px!important;overflow-y:auto!important}
.share-picker-heading{font-size:12px!important}
.share-picker-close{width:29px!important;height:29px!important}
.share-date-summary{font-size:21px!important;margin:13px 0 14px!important;line-height:1.5!important}
.share-month-row{padding:7px 0!important;grid-template-columns:32px 1fr 32px!important}
.share-month-row strong{font-size:13px!important;font-weight:400!important}
.share-month-row button{width:32px!important;height:32px!important}
.share-calendar-grid{gap:2px 1px!important;padding:8px 0 12px!important}
.share-weekday,.share-calendar-day,.share-calendar-empty{height:35px!important;font-size:12px!important}
.share-picker-actions{padding-top:6px!important;gap:8px!important}
.share-picker-cancel,.share-picker-confirm{height:36px!important;min-width:68px!important;border-radius:9px!important;font-size:12px!important}
.share-clock-top{display:flex;align-items:center;justify-content:center;direction:ltr;gap:4px;margin:16px 0 18px}
.share-clock-number{border:0;border-radius:10px;background:#edf1f1;color:#2c4a5a;font:400 43px Arial,sans-serif;padding:5px 9px;min-width:69px;height:68px}
.share-clock-number.active{background:#7cddd7;color:#173f43}
.share-clock-colon{font:400 42px Arial,sans-serif;color:#2c4a5a}
.share-clock-period{display:flex;flex-direction:column;margin-left:7px;border:1px solid #b9c9cd;border-radius:9px;overflow:hidden}
.share-clock-period button{width:44px;height:33px;border:0;background:white;color:#2c4a5a;font:400 13px Arial,sans-serif}
.share-clock-period button+button{border-top:1px solid #b9c9cd}
.share-clock-period button.active{background:#e1e8ff;color:#334b80}
.share-clock-face{position:relative;width:240px;height:240px;margin:0 auto 15px;border-radius:50%;background:#eef2f1;direction:ltr}
.share-clock-tick{position:absolute;transform:translate(-50%,-50%);width:34px;height:34px;border:0;border-radius:50%;background:transparent;color:#2c4a5a;font:400 13px Arial,sans-serif;z-index:2}
.share-clock-tick.active{background:#087d78;color:#fff}
.share-clock-center{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:7px;height:7px;border-radius:50%;background:#087d78;z-index:3}
.share-clock-hand{position:absolute;left:50%;top:50%;width:2px;height:39%;background:#087d78;transform-origin:center top;z-index:1}
@media(max-width:380px){#dashboardView .records-grid{gap:11px!important}.share-native-picker{width:min(100%,310px)!important}}


/* =========================================================
   Final visual alignment: Flutter-like controls and pickers
   ========================================================= */
#recordModal .share-picker-shell{
    height:50px!important;
    min-height:50px!important;
    border:1px solid #dce9e8!important;
    border-radius:12px!important;
    background:#fff!important;
    padding:0 11px!important;
    gap:8px!important;
    box-shadow:none!important;
}
#recordModal .share-picker-shell>.material-icons-round{
    color:#62bdb7!important;
    font-size:20px!important;
}
#recordModal .share-picker-text{
    color:#87999d!important;
    font-size:12px!important;
    font-weight:400!important;
}
#recordModal .share-picker-text.selected{color:#526c75!important}
#recordModal .share-picker-shell input.share-picker-value{
    position:absolute!important;
    inset:auto!important;
    right:0!important;
    bottom:0!important;
    width:1px!important;
    height:1px!important;
    min-height:0!important;
    padding:0!important;
    opacity:0!important;
    pointer-events:none!important;
    z-index:-1!important;
}
#recordModal .share-input-icon:has(select)::after{display:none!important}
#recordModal .share-input-icon{width:100%;min-width:0}
#recordModal .share-select-host{position:relative;display:block;flex:1;min-width:0;width:100%}
#recordModal .share-input-icon>.share-select-host{width:100%}
#recordModal .share-native-select-source{
    position:absolute!important;
    width:1px!important;height:1px!important;
    min-height:0!important;
    padding:0!important;margin:0!important;
    opacity:0!important;pointer-events:none!important;
    inset:auto!important;z-index:-1!important;
}
#recordModal .share-select-trigger{
    display:flex!important;
    align-items:center!important;
    justify-content:space-between!important;
    width:100%!important;
    min-height:52px!important;height:52px!important;
    padding:0 42px 0 34px!important;
    border:1px solid #dce9e8!important;
    border-radius:12px!important;
    background:#fff!important;
    color:#87999d!important;
    font-family:'Noto Sans Arabic',Tahoma,Arial,sans-serif!important;
    font-size:13px!important;font-weight:400!important;
    text-align:right!important;
    box-shadow:none!important;
    position:relative!important;
}
#recordModal .share-select-trigger::after{
    content:'keyboard_arrow_down';
    position:absolute;left:10px;top:50%;
    transform:translateY(-50%);
    font-family:'Material Icons Round';
    font-size:22px;line-height:1;
    color:#62bdb7;pointer-events:none;
}
#recordModal .share-select-trigger.has-value{color:#526c75!important}
#recordModal .share-select-trigger:focus-visible{
    outline:2px solid #b7e4e0!important;outline-offset:2px;
}
#recordModal .share-select-trigger.is-invalid{border-color:#d95c5c!important}
.share-select-menu{
    z-index:1000005;
    max-height:240px;overflow-y:auto;
    padding:5px;
    border:1px solid #dce9e8;
    border-radius:13px;
    background:#fff;
    box-shadow:0 7px 20px rgba(44,74,90,.13);
    direction:rtl;
    font-family:'Noto Sans Arabic',Tahoma,Arial,sans-serif;
}
.share-select-option{
    display:flex;align-items:center;
    width:100%;min-height:42px;
    padding:8px 13px;
    border:0;border-radius:9px;
    background:transparent;
    color:#526c75;
    font-family:inherit;font-size:13px;font-weight:400;
    text-align:right;
}
.share-select-option.selected{
    background:#e8f5f3;color:#278e88;
}
.share-select-option:active{background:#e8f5f3}
#recordModal .share-food-sheet .share-select-trigger,
#recordModal .share-food-sheet .form-control{
    min-height:48px!important;height:48px!important;
    border-radius:11px!important;
    font-size:13px!important;
}
#recordModal .share-food-sheet .share-select-trigger{padding-right:14px!important}
#recordModal .share-food-sheet .share-select-trigger::after{left:10px}
#recordModal .share-food-sheet .share-select-menu{z-index:1000007}

/* Date and time dialog scaled down to match the compact Flutter picker */
.share-native-picker-overlay{
    padding:14px!important;
    background:rgba(37,52,57,.42)!important;
}
.share-native-picker{
    width:min(100%,310px)!important;
    max-height:min(82dvh,520px)!important;
    padding:14px 15px 12px!important;
    border:1px solid #edf2f1!important;
    border-radius:20px!important;
    background:#fff!important;
    box-shadow:0 10px 30px rgba(35,60,66,.14)!important;
}
.share-picker-heading{font-size:12px!important;color:#87999d!important}
.share-picker-close{
    width:28px!important;height:28px!important;
    background:#f2f8f7!important;color:#526c75!important;
}
.share-date-summary{
    margin:11px 0 12px!important;
    font-size:18px!important;line-height:1.5!important;
    color:#2c4a5a!important;
}
.share-month-row{
    grid-template-columns:32px minmax(0,1fr) 32px!important;
    gap:5px!important;padding:6px 0!important;
    border-color:#edf2f1!important;
}
.share-month-row strong{font-size:12px!important;color:#607d83!important}
.share-month-row button{width:30px!important;height:30px!important;color:#62bdb7!important}
.share-month-row .material-icons-round{font-size:21px!important}
.share-calendar-grid{gap:1px!important;padding:7px 0 9px!important}
.share-weekday,.share-calendar-day,.share-calendar-empty{
    height:32px!important;min-height:32px!important;
}
.share-weekday{font-size:11px!important;color:#87999d!important}
.share-calendar-day{font-size:12px!important;color:#526c75!important}
.share-calendar-day.selected{background:#48b9b2!important;color:#fff!important}
.share-calendar-day:disabled{color:#d2dada!important}
.share-picker-actions{padding-top:5px!important}
.share-picker-cancel,.share-picker-confirm{
    min-width:66px!important;height:34px!important;
    border-radius:9px!important;font-size:12px!important;
}
.share-picker-cancel{background:#f0f6f5!important;color:#71858b!important}
.share-picker-confirm{background:#48b9b2!important;color:#fff!important}
.share-clock-top{margin:12px 0 13px!important;gap:3px!important}
.share-clock-number{
    min-width:58px!important;width:58px!important;height:56px!important;
    padding:4px 7px!important;border-radius:9px!important;
    background:#f0f3f2!important;color:#2c4a5a!important;
    font-size:36px!important;
}
.share-clock-number.active{background:#7cddd7!important;color:#173f43!important}
.share-clock-colon{font-size:35px!important}
.share-clock-period{margin-left:5px!important;border-color:#dce7e7!important}
.share-clock-period button{
    width:39px!important;height:27px!important;
    font-size:11px!important;background:#fff!important;color:#526c75!important;
}
.share-clock-period button.active{background:#e8edff!important;color:#52628c!important}
.share-clock-face{
    width:196px!important;height:196px!important;
    margin:0 auto 10px!important;
    background:#f0f4f3!important;
}
.share-clock-tick{width:29px!important;height:29px!important;font-size:12px!important}
.share-clock-tick.active{background:#087d78!important;color:#fff!important}
.share-clock-center{background:#087d78!important}
.share-clock-hand{background:#087d78!important}
@media(max-width:380px){
    .share-native-picker{width:min(100%,292px)!important;padding:12px 12px 10px!important}
    .share-clock-face{width:184px!important;height:184px!important}
    .share-clock-number{width:53px!important;min-width:53px!important;height:52px!important;font-size:33px!important}
    .share-clock-period button{width:36px!important}
}

/* Make all form arrows align consistently in RTL */
#recordModal .share-select-host{direction:rtl}
#recordModal .share-select-trigger{direction:rtl}
#recordModal .share-select-trigger::after{left:10px;right:auto}
#recordModal .share-input-icon>.material-icons-round{right:12px!important;left:auto!important}
#recordModal .share-picker-shell>.material-icons-round{margin-inline-start:0}



/* Final picker corrections: Flutter-like date confirmation and clock hand */
.share-native-picker-overlay { z-index: 1000000 !important; }
.share-native-picker { touch-action: pan-y; }
.share-calendar-day.selected {
    background: #48b9b2 !important;
    color: #fff !important;
}
.share-picker-confirm {
    background: #48b9b2 !important;
    color: #fff !important;
}
.share-clock-face {
    touch-action: none;
    user-select: none;
    -webkit-user-select: none;
}
.share-clock-hand {
    width: 2px !important;
    background: #168f88 !important;
    pointer-events: none;
}
.share-clock-center { background: #168f88 !important; }
.share-clock-tick.active {
    background: #168f88 !important;
    color: #fff !important;
}
.share-clock-number.active {
    background: #dff3f0 !important;
    color: #087f78 !important;
}
.share-clock-period button.active {
    background: #dff3f0 !important;
    color: #087f78 !important;
}


/* Visual corrections based on Care Share screenshots */
#recordModal .share-input-icon{position:relative!important;align-items:center!important}
#recordModal .share-input-icon>.material-icons-round{
  position:absolute!important;top:50%!important;right:14px!important;left:auto!important;
  transform:translateY(-50%)!important;display:flex!important;align-items:center!important;
  justify-content:center!important;width:24px!important;height:24px!important;
  margin:0!important;line-height:1!important;z-index:3!important;
  color:#59bdb6!important;
}
#recordModal .share-input-icon .share-select-trigger{padding-right:46px!important}
#recordModal .share-section{align-items:center!important;gap:12px!important;line-height:1.7!important}
#recordModal .share-section>.material-icons-round{
  display:inline-flex!important;align-items:center!important;justify-content:center!important;
  line-height:1!important;vertical-align:middle!important;flex-shrink:0!important;
  margin:0!important;transform:none!important;color:#59bdb6!important;
}
.share-clock-number{
  display:flex!important;align-items:center!important;justify-content:center!important;
  line-height:1!important;box-sizing:border-box!important;padding:0!important;
  font-family:Arial,sans-serif!important;font-variant-numeric:tabular-nums!important;
}
.share-clock-colon{display:flex!important;align-items:center!important;justify-content:center!important;line-height:1!important}
.share-clock-tick{
  display:flex!important;align-items:center!important;justify-content:center!important;
  padding:0!important;line-height:1!important;box-sizing:border-box!important;
}
.share-clock-minute-dot{
  position:absolute;width:29px;height:29px;border-radius:50%;
  background:#168f88;transform:translate(-50%,-50%);pointer-events:none;z-index:2;
}
#recordModal .share-top-save,#recordModal .share-confirm,
.share-picker-confirm{background:#62bdb7!important;color:#fff!important}
#recordModal .share-top-save:hover,#recordModal .share-confirm:hover,
.share-picker-confirm:hover{background:#50ada7!important}
#recordModal .share-chip{
  display:inline-flex!important;align-items:center!important;justify-content:center!important;
  gap:9px!important;min-height:36px!important;padding:7px 13px!important;
  line-height:1.5!important;font-weight:400!important;
}
#recordModal .share-chip .chip-symbol{
  display:inline-flex;align-items:center;justify-content:center;
  width:17px;height:17px;flex:0 0 17px;line-height:1;font-size:14px;
  transform:translateY(-2px);
}
#recordModal .share-chip.chosen{
  background:#e7f7f5!important;border-color:#62bdb7!important;color:#278d87!important
}


/* Date picker only: English calendar layout matching the Flutter date dialog */
.share-native-picker:has(.share-calendar-grid) {
    direction: rtl;
}
.share-native-picker:has(.share-calendar-grid) .share-date-summary {
    direction: ltr !important;
    text-align: center !important;
}
.share-native-picker:has(.share-calendar-grid) .share-month-row {
    direction: ltr !important;
}
.share-native-picker:has(.share-calendar-grid) .share-month-row strong {
    direction: ltr !important;
    text-align: center !important;
}
.share-native-picker:has(.share-calendar-grid) .share-calendar-grid {
    direction: ltr !important;
}
.share-native-picker:has(.share-calendar-grid) .share-weekday,
.share-native-picker:has(.share-calendar-grid) .share-calendar-day {
    direction: ltr !important;
}
.share-native-picker:has(.share-calendar-grid) .share-picker-actions {
    direction: ltr !important;
}
</style>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>



<body>

<div class="page">



    <!-- =====================================

         صفحة إدخال رمز الدخول

    ====================================== -->



    <section class="verify-view" id="verifyView">

        <h1 class="page-title">تسجيل الدخول</h1>



        <p class="verify-description">أدخل رمز المشاركة المكوّن من 6 أرقام</p>



        <form class="verify-form" id="verifyForm">

            <div class="code-field">

                <svg class="code-icon" viewBox="0 0 24 24" fill="none"

                     stroke="currentColor" stroke-width="2" stroke-linecap="round"

                     stroke-linejoin="round" aria-hidden="true">

                    <rect x="4" y="10" width="16" height="11" rx="2"></rect>

                    <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>

                    <circle cx="12" cy="15.5" r="1"></circle>

                </svg>



                <input

                    type="text"

                    id="accessCode"

                    name="access_code"

                    placeholder="••••••"

                    maxlength="6"

                    minlength="6"

                    inputmode="numeric"

                    pattern="[0-9]{6}"

                    autocomplete="one-time-code"

                    aria-label="رمز الدخول"

                    required

                >

            </div>



            <div class="verify-options" hidden>

                <label class="remember-label" for="rememberMe">

                    <input type="checkbox" id="rememberMe" name="remember_me">

                    <span>تذكرني</span>

                </label>



                <span class="remember-hint">

                    تبقى الجلسة محفوظة حتى انتهاء مدة المشاركة.

                </span>

            </div>



            <button class="main-button" type="submit" id="verifyButton">

                تسجيل الدخول

            </button>

        </form>



        <div id="verifyMessage" class="message" hidden></div>

    </section>





    <!-- =====================================

         الصفحة الرئيسية للمستلم

    ====================================== -->



    <main class="dashboard" id="dashboardView" hidden>

        <h2 class="dashboard-heading">مشاركة متابعة الطفل</h2>



        <section class="child-card">
            <div class="child-avatar" aria-hidden="true">
                <span class="material-icons-round" id="childAvatarIcon">face_6</span>
            </div>

            <div class="child-info">

                <h2 class="child-name" id="childName"></h2>



                <div class="child-meta"><div class="child-age">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"

                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"

                         aria-hidden="true">

                        <rect x="3" y="4" width="18" height="17" rx="2"></rect>

                        <path d="M16 2v4M8 2v4M3 10h18"></path>

                    </svg>

                    <span id="childAge"></span>

                </div><div class="child-gender" id="childGender" hidden></div></div>

            </div>




        </section>



        <h3 class="records-title">اختر نوع التسجيل</h3>



        <section class="records-grid">

            <!-- الوجبات -->

            <button type="button" class="record-card meal-card" id="mealCard"

                    hidden onclick="openRecordModal('meal')">
                <span class="material-icons-round record-icon" aria-hidden="true">restaurant</span>

                <span>الوجبات</span>

            </button>



            <!-- قراءة السكر -->

            <button type="button" class="record-card glucose-card" id="glucoseCard"

                    hidden onclick="openRecordModal('glucose_reading')">
                <span class="material-icons-round record-icon" aria-hidden="true">bloodtype</span>

                <span>قراءة السكر</span>

            </button>



            <!-- النشاط -->

            <button type="button" class="record-card activity-card" id="activityCard"

                    hidden onclick="openRecordModal('activity')">
                <span class="material-icons-round record-icon" aria-hidden="true">directions_run</span>

                <span>النشاط</span>

            </button>

        </section>





    </main>





    <!-- =====================================

         نافذة إدخال البيانات

    ====================================== -->



    <div class="modal" id="recordModal" hidden>

        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalTitle">

            <div class="modal-header">

                <h3 id="modalTitle"></h3>
                <button type="submit" form="recordForm" class="share-top-save save-button" id="topSaveButton">حفظ</button>

                <button type="button" class="close-button" onclick="closeRecordModal()"

                        aria-label="إغلاق">×</button>

            </div>



            <div id="modalBody"></div>

            <div id="recordMessage" class="message" hidden></div>

        </div>

    </div>



    <div class="toast" id="toast" hidden></div>

</div>



<script>

    // =====================================

    // بيانات المشاركة والجلسة

    // =====================================



    const shareToken = @json($token);

    const storageKey = 'tinyguide_care_share_' + shareToken;



    let accessToken = null;

    let accessTokenExpiresAt = null;

    let currentRecordType = null;

    let toastTimer = null;

    let sessionExpiryTimer = null;



    // =====================================

    // عناصر الصفحة

    // =====================================



    const verifyView = document.getElementById('verifyView');

    const dashboardView = document.getElementById('dashboardView');

    const verifyForm = document.getElementById('verifyForm');

    const verifyButton = document.getElementById('verifyButton');

    const verifyMessage = document.getElementById('verifyMessage');

    const rememberMe = document.getElementById('rememberMe');

    const recordModal = document.getElementById('recordModal');

    const modalBody = document.getElementById('modalBody');

    const modalTitle = document.getElementById('modalTitle');

    const recordMessage = document.getElementById('recordMessage');

    const toast = document.getElementById('toast');



    // =====================================

    // التاريخ والوقت المحليان

    // =====================================



    function pad(value) {

        return String(value).padStart(2, '0');

    }



    function getLocalDate() {

        const now = new Date();

        return now.getFullYear() + '-' +

            pad(now.getMonth() + 1) + '-' +

            pad(now.getDate());

    }



    function getLocalTime() {

        const now = new Date();

        return pad(now.getHours()) + ':' +

            pad(now.getMinutes()) + ':' +

            pad(now.getSeconds());

    }



    function normalizeTime(value) {

        if (!value) return value;

        return value.length === 5 ? value + ':00' : value;

    }



    // =====================================

    // حساب عمر الطفل

    // =====================================



    function calculateAge(birthDate) {

        if (!birthDate) return null;



        const birth = new Date(birthDate + 'T00:00:00');

        if (Number.isNaN(birth.getTime())) return null;



        const today = new Date();

        let age = today.getFullYear() - birth.getFullYear();



        const beforeBirthday =

            today.getMonth() < birth.getMonth() ||

            (today.getMonth() === birth.getMonth() &&

                today.getDate() < birth.getDate());



        if (beforeBirthday) age--;

        return age >= 0 ? age : null;

    }



    // =====================================

    // عرض رسائل التحقق

    // =====================================



    function showVerifyMessage(message, isSuccess = false) {

        verifyMessage.textContent = message;

        verifyMessage.className = isSuccess

            ? 'message success'

            : 'message error';

        verifyMessage.hidden = false;

    }



    function showRecordMessage(message, isSuccess = false) {

        recordMessage.textContent = message;

        recordMessage.className = isSuccess

            ? 'message success'

            : 'message error';

        recordMessage.hidden = false;

    }



    // =====================================

    // قراءة استجابة Laravel

    // =====================================



    async function readApiResponse(response) {

        const responseText = await response.text();

        let result;



        try {

            result = JSON.parse(responseText);

        } catch (error) {

            throw new Error('تعذر قراءة استجابة الخادم. يرجى المحاولة مرة أخرى.');

        }



        if (!response.ok || result.success !== true) {

            let errorMessage = result.message || 'حدث خطأ أثناء تنفيذ الطلب.';



            if (result.errors) {

                const firstError = Object.values(result.errors)[0];

                if (firstError && firstError.length) {

                    errorMessage = firstError[0];

                }

            }



            const apiError = new Error(errorMessage);

            apiError.status = response.status;

            throw apiError;

        }



        return result;

    }



    // =====================================

    // حفظ واستعادة الجلسة

    // لا نحفظ رمز الدخول المكون من 6 أرقام.

    // نحفظ رمز الوصول الذي يصدره الخادم بعد التحقق.

    // =====================================



    function removeStoredSession() {

        localStorage.removeItem(storageKey);

        sessionStorage.removeItem(storageKey);

    }



    function saveSession(sessionData, shouldRemember) {

        removeStoredSession();



        const serialized = JSON.stringify(sessionData);



        if (shouldRemember) {

            // يبقى حتى وقت انتهاء المشاركة المحدد من ولي الأمر.

            localStorage.setItem(storageKey, serialized);

        } else {

            // يبقى خلال جلسة التصفح الحالية فقط.

            sessionStorage.setItem(storageKey, serialized);

        }

    }



    function readStoredSession() {

        const persistent = localStorage.getItem(storageKey);

        const temporary = sessionStorage.getItem(storageKey);

        const stored = persistent || temporary;



        if (!stored) return null;



        try {

            const sessionData = JSON.parse(stored);

            const expiresAt = new Date(sessionData.access_token_expires_at).getTime();



            if (!sessionData.access_token ||

                !Number.isFinite(expiresAt) ||

                expiresAt <= Date.now()) {

                removeStoredSession();

                return null;

            }



            return sessionData;

        } catch (error) {

            removeStoredSession();

            return null;

        }

    }



    function renderDashboard(data) {

        accessToken = data.access_token;

        accessTokenExpiresAt = new Date(data.access_token_expires_at).getTime();



        document.getElementById('childName').textContent =

            data.child_name || 'الطفل';



        window.shareChildBirthDate = data.birth_date || null;
        const age = calculateAge(data.birth_date);

        document.getElementById('childAge').textContent =

            age !== null ? age + ' سنوات' : 'العمر غير متاح';

        const gender = String(data.gender || data.child_gender || '').trim();
        const genderElement = document.getElementById('childGender');
        const isGirl = ['بنت', 'أنثى', 'female', 'girl', 'f'].includes(gender.toLowerCase());
        const isBoy = ['ولد', 'ذكر', 'male', 'boy', 'm'].includes(gender.toLowerCase());
        genderElement.hidden = !(isGirl || isBoy);
        genderElement.innerHTML = (isGirl || isBoy)
            ? '<span class="material-icons-round">' + (isGirl ? 'female' : 'male') + '</span><span>' + (isGirl ? 'بنت' : 'ولد') + '</span>'
            : '';
        document.getElementById('childAvatarIcon').textContent = isGirl ? 'face_3' : 'face_6';



        document.getElementById('mealCard').hidden = !Boolean(data.allow_meals);

        document.getElementById('glucoseCard').hidden = !Boolean(data.allow_glucose);

        document.getElementById('activityCard').hidden = !Boolean(data.allow_activities);



        verifyView.hidden = true;

        dashboardView.hidden = false;

        verifyMessage.hidden = true;



        scheduleSessionExpiry();

    }



    function scheduleSessionExpiry() {

        clearTimeout(sessionExpiryTimer);



        if (!accessTokenExpiresAt) return;



        const remaining = accessTokenExpiresAt - Date.now();



        if (remaining <= 0) {

            endSession('انتهت صلاحية الجلسة. يرجى إدخال رمز الدخول مجددًا.');

            return;

        }



        sessionExpiryTimer = setTimeout(function () {

            endSession('انتهت صلاحية الجلسة. يرجى إدخال رمز الدخول مجددًا.');

        }, remaining);

    }



    function endSession(message = '') {

        clearTimeout(sessionExpiryTimer);

        clearStoredSession();

        accessToken = null;

        accessTokenExpiresAt = null;

        currentRecordType = null;

        closeRecordModal();

        dashboardView.hidden = true;

        verifyView.hidden = false;

        document.getElementById('accessCode').value = '';

        rememberMe.checked = false;



        if (message) showVerifyMessage(message);

    }



    function clearStoredSession() {

        removeStoredSession();

    }



    // =====================================

    // التحقق من رمز الدخول

    // =====================================



    verifyForm.addEventListener('submit', async function (event) {

        event.preventDefault();



        const accessCode = document.getElementById('accessCode').value.trim();

        const shouldRemember = rememberMe.checked;



        verifyMessage.hidden = true;



        if (!/^\d{6}$/.test(accessCode)) {

            showVerifyMessage('يرجى إدخال رمز مكوّن من 6 أرقام.');

            return;

        }



        verifyButton.disabled = true;

        verifyButton.textContent = 'جارٍ التحقق...';



        try {

            const response = await fetch(

                '/api/shared-record-submissions/verify',

                {

                    method: 'POST',

                    headers: {

                        'Accept': 'application/json',

                        'Content-Type': 'application/json'

                    },

                    body: JSON.stringify({

                        token: shareToken,

                        access_code: accessCode,

                        remember_me: shouldRemember

                    })

                }

            );



            const result = await readApiResponse(response);

            const data = result.data;



            if (!data || !data.access_token || !data.access_token_expires_at) {

                throw new Error('لم يتم استلام بيانات الجلسة من الخادم.');

            }



            const sessionData = {

                access_token: data.access_token,

                access_token_expires_at: data.access_token_expires_at,

                child_name: data.child_name,

                birth_date: data.birth_date,
                gender: data.gender || data.child_gender || null,

                allow_meals: data.allow_meals,

                allow_glucose: data.allow_glucose,

                allow_activities: data.allow_activities

            };



            saveSession(sessionData, shouldRemember);

            renderDashboard(sessionData);



        } catch (error) {

            showVerifyMessage(error.message);

        } finally {

            verifyButton.disabled = false;

            verifyButton.textContent = 'تسجيل الدخول';

        }

    });



    // =====================================

    // إنشاء نموذج التسجيل

    // =====================================



    const shareFoodGroups = {
        'النشويات والكربوهيدرات':['أرز','خبز','بطاطس','معكرونة'],
        'البروتينات':['دجاج','سمك','لحم','بيض','بقوليات'],
        'الخضروات':['خيار','طماطم','جزر','سلطة'],
        'الفواكه':['تفاح','موز','برتقال'],
        'الحليب ومشتقاته':['حليب','زبادي','جبن'],
        'الدهون الصحية':['زيت الزيتون','أفوكادو','مكسرات']
    };
    function toggleCustomActivity() {
        const choice = document.getElementById('activityChoice');
        const field = document.getElementById('activityName');
        if (!choice || !field) return;
        field.hidden = choice.value !== 'نشاط آخر';
        field.required = choice.value === 'نشاط آخر';
        if (!field.required) field.value = '';
    }
    function toggleMealStatus() {
        const selected = document.querySelector('input[name="reading_type"]:checked');
        const group = document.getElementById('mealStatusGroup');
        const status = document.getElementById('mealStatus');
        const after = selected?.value === 'بعد الوجبة';
        group.hidden = !after;
        if (status) {
            status.dataset.shareRequired = after ? 'true' : 'false';
            status.required = false;
        }
        if (!after) { status.value = ''; updateMealStatusHelp(); updateShareSelectDisplay(status); }
    }
    function updateMealStatusHelp() {
        const status = document.getElementById('mealStatus');
        const help = document.getElementById('mealStatusHelp');
        if (!help || !status) return;
        help.hidden = !status.value;
        help.textContent = status.value === 'الوجبة غير مسجلة في التطبيق'
          ? 'سجّلي الوجبة أولًا من بطاقة الوجبات، ثم ارجعي لتسجيل القراءة. لا يمكن ربط وجبة غير محفوظة.'
          : 'تم تحديد أن الوجبة مسجلة. ربط القراءة بوجبة محددة يحتاج دعم API المشاركة.';
    }
    function openFoodPicker() { document.getElementById('foodPicker').hidden = false; renderFoodChoices(); }
    function closeFoodPicker() { document.getElementById('foodPicker').hidden = true; }
    function renderFoodChoices() {
        const group = document.getElementById('foodGroup')?.value;
        const choices = document.getElementById('foodChoices');
        if (!choices) return;
        choices.replaceChildren();
        if (!group) { choices.textContent = 'اختاري مجموعة غذائية لعرض الأطعمة المتاحة'; }
        else {
            for (const food of [...shareFoodGroups[group], ...(window.shareSelectedFoods[group] || []).filter(f => !shareFoodGroups[group].includes(f))]) {
                const chip = document.createElement('button'); chip.type='button'; chip.className='share-chip';
                const chosen = (window.shareSelectedFoods[group] || []).includes(food);
                chip.classList.toggle('chosen',chosen);
                if (chosen) {
                    const mark=document.createElement('span');mark.className='chip-symbol';mark.textContent='✓';
                    chip.append(mark);
                }
                const name=document.createElement('span');name.textContent=food;chip.append(name);
                chip.onclick=()=>{ const arr=window.shareSelectedFoods[group] || []; window.shareSelectedFoods[group]=chosen ? arr.filter(x=>x!==food) : [...arr,food]; renderFoodChoices(); };
                choices.append(chip);
            }
        }
        const all = Object.entries(window.shareSelectedFoods || {}).flatMap(([g,fs])=>fs.map(f=>({group:g,food:f})));
        const list=document.getElementById('chosenFoodChips'); list.replaceChildren();
        if (!all.length) list.textContent='لم تختاري أي طعام بعد';
        for (const {group:g,food:f} of all) {
            const chip=document.createElement('button'); chip.type='button'; chip.className='share-chip chosen';
            const mark=document.createElement('span');mark.className='chip-symbol';mark.textContent='×';
            const name=document.createElement('span');name.textContent=f;
            chip.append(mark,name);
            chip.onclick=()=>{window.shareSelectedFoods[g]=window.shareSelectedFoods[g].filter(x=>x!==f);renderFoodChoices();};list.append(chip);
        }
        document.getElementById('foodCount').textContent=`${all.length} أطعمة`;
    }
    function addCustomFood() {
        const group=document.getElementById('foodGroup').value;
        const input=document.getElementById('customFood'); const food=input.value.trim();
        const error=document.getElementById('foodPickerError'); error.hidden=false;
        if (!group) {error.textContent='اختاري المجموعة الغذائية أولًا';return;}
        if (!food) {error.textContent='اكتبي اسم الطعام أولًا';return;}
        if ([...shareFoodGroups[group],...(window.shareSelectedFoods[group]||[])].includes(food)) {error.textContent='هذا الطعام موجود مسبقًا';return;}
        error.hidden=true; window.shareSelectedFoods[group]=[...(window.shareSelectedFoods[group]||[]),food]; input.value=''; renderFoodChoices();
    }
    function confirmFoodPicker() {
        const all=Object.values(window.shareSelectedFoods).flat();
        document.getElementById('selectedFoodsLabel').textContent=all.length ? all.join('، ') : 'اختيار الأطعمة';
        document.getElementById('foodsError').hidden=all.length>0;
        closeFoodPicker();
    }

    // منتقي تاريخ ووقت موحد بتصميم قريب من Flutter
    let activeSharePicker = null;
    let pickerMonth = new Date().getMonth();
    let pickerYear = new Date().getFullYear();

    function ensureSharePickerOverlay() {
        let overlay = document.getElementById('shareNativePicker');
        if (overlay) return overlay;
        overlay = document.createElement('div');
        overlay.id = 'shareNativePicker';
        overlay.className = 'share-native-picker-overlay';
        overlay.hidden = true;
        overlay.innerHTML = '<div class="share-native-picker" role="dialog" aria-modal="true" aria-labelledby="sharePickerTitle"><div id="sharePickerContent"></div></div>';
        overlay.addEventListener('click', event => {
            if (event.target === overlay) closeSharePicker();
        });
        document.body.appendChild(overlay);
        return overlay;
    }

    function openSharePicker(shell) {
        const input = shell.querySelector('input');
        if (!input) return;
        activeSharePicker = { input, shell, pendingDate: input.type === 'date' ? (input.value || getLocalDate()) : null };
        const overlay = ensureSharePickerOverlay();
        overlay.hidden = false;
        if (input.type === 'date') {
            const selected = input.value ? new Date(input.value + 'T00:00:00') : new Date();
            pickerMonth = selected.getMonth();
            pickerYear = selected.getFullYear();
            renderShareDatePicker(input.value);
        } else {
            renderShareTimePicker(input.value || getLocalTime().slice(0, 5));
        }
        document.body.style.overflow = 'hidden';
    }

    function closeSharePicker() {
        const overlay = document.getElementById('shareNativePicker');
        if (overlay) overlay.hidden = true;
        document.body.style.overflow = '';
        activeSharePicker = null;
    }

    function updateSharePicker(input) {
        const label = input.closest('.share-picker-shell')?.querySelector('.share-picker-text');
        if (!label || !input.value) return;
        if (input.type === 'date') {
            const [y, m, d] = input.value.split('-');
            label.textContent = `${d}/${m}/${y}`;
        } else {
            const [h, m] = input.value.split(':').map(Number);
            label.textContent = `${(h % 12) || 12}:${String(m).padStart(2, '0')} ${h >= 12 ? 'م' : 'ص'}`;
        }
        label.classList.add('selected');
    }

    function renderShareDatePicker(currentValue) {
        const content = document.getElementById('sharePickerContent');
        if (!content) return;

        const firstDay = new Date(pickerYear, pickerMonth, 1).getDay();
        const daysInMonth = new Date(pickerYear, pickerMonth + 1, 0).getDate();
        const pendingValue = activeSharePicker?.pendingDate || currentValue || getLocalDate();
        const selectedDate = new Date(pendingValue + 'T00:00:00');
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const birth = window.shareChildBirthDate
            ? new Date(String(window.shareChildBirthDate).slice(0, 10) + 'T00:00:00')
            : null;
        const monthName = new Intl.DateTimeFormat('en-US', { month: 'long' })
            .format(new Date(pickerYear, pickerMonth, 1));
        const weekdays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
        let cells = '';

        for (let i = 0; i < firstDay; i++) {
            cells += '<span class="share-calendar-empty"></span>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(pickerYear, pickerMonth, day);
            const value = `${pickerYear}-${pad(pickerMonth + 1)}-${pad(day)}`;
            const disabled = date > today || (birth && date < birth);
            const selected = selectedDate.getFullYear() === pickerYear &&
                selectedDate.getMonth() === pickerMonth &&
                selectedDate.getDate() === day;

            cells += `<button type="button" class="share-calendar-day${selected ? ' selected' : ''}"
                data-date="${value}" ${disabled ? 'disabled' : ''}>${day}</button>`;
        }

        content.innerHTML = `
          <div class="share-picker-heading">
            <span id="sharePickerTitle">Select date</span>
            <button type="button" class="share-picker-close" aria-label="Close" onclick="closeSharePicker()">
              <span class="material-icons-round">close</span>
            </button>
          </div>
          <div class="share-date-summary">${new Intl.DateTimeFormat('en-US', {
              weekday: 'short', day: 'numeric', month: 'short'
          }).format(selectedDate)}</div>
          <div class="share-month-row">
            <button type="button" aria-label="Previous month" onclick="changeSharePickerMonth(-1)">
              <span class="material-icons-round">chevron_left</span>
            </button>
            <strong>${monthName} ${pickerYear}</strong>
            <button type="button" aria-label="Next month"
              onclick="changeSharePickerMonth(1)"
              ${pickerYear === today.getFullYear() && pickerMonth >= today.getMonth() ? 'disabled' : ''}>
              <span class="material-icons-round">chevron_right</span>
            </button>
          </div>
          <div class="share-calendar-grid">
            ${weekdays.map(day => `<span class="share-weekday">${day}</span>`).join('')}${cells}
          </div>
          <div class="share-picker-actions">
            <button type="button" class="share-picker-cancel" onclick="closeSharePicker()">إلغاء</button>
            <button type="button" class="share-picker-confirm" id="shareDateConfirm">اختيار</button>
          </div>`;

        content.querySelectorAll('[data-date]').forEach(button => {
            button.addEventListener('click', () => {
                if (!activeSharePicker) return;
                activeSharePicker.pendingDate = button.dataset.date;
                renderShareDatePicker(activeSharePicker.pendingDate);
            });
        });

        content.querySelector('#shareDateConfirm')?.addEventListener('click', () => {
            if (!activeSharePicker) return;
            activeSharePicker.input.value = activeSharePicker.pendingDate || pendingValue;
            updateSharePicker(activeSharePicker.input);
            closeSharePicker();
        });
    }

    function changeSharePickerMonth(amount) {
        if (!activeSharePicker) return;
        const input = activeSharePicker.input;
        const pendingValue = activeSharePicker.pendingDate || input.value || getLocalDate();
        const current = new Date(pendingValue + 'T00:00:00');
        const selected = new Date(pickerYear, pickerMonth + amount, 1);
        const today = new Date();
        if (selected > new Date(today.getFullYear(), today.getMonth(), 1)) return;
        if (window.shareChildBirthDate) {
            const birth = new Date(String(window.shareChildBirthDate).slice(0, 10) + 'T00:00:00');
            if (selected.getFullYear() < birth.getFullYear() || (selected.getFullYear() === birth.getFullYear() && selected.getMonth() < birth.getMonth())) return;
        }
        pickerYear = selected.getFullYear();
        pickerMonth = selected.getMonth();
        renderShareDatePicker(activeSharePicker.pendingDate || `${current.getFullYear()}-${pad(current.getMonth()+1)}-${pad(current.getDate())}`);
    }

    function renderShareTimePicker(value) {
        const content = document.getElementById('sharePickerContent');
        if (!content) return;

        const [hour24, minute] = value.split(':').map(Number);
        const state = {
            hour: hour24 % 12 || 12,
            minute: minute || 0,
            period: hour24 >= 12 ? 'pm' : 'am',
            mode: 'hour'
        };

        const draw = () => {
            const ticks = Array.from({ length: 12 }, (_, i) => {
                const number = state.mode === 'hour' ? (i === 0 ? 12 : i) : i * 5;
                const active = state.mode === 'hour'
                    ? number === state.hour
                    : number === state.minute;
                const tickAngle = i * Math.PI / 6 - Math.PI / 2;
                const x = 50 + 39 * Math.cos(tickAngle);
                const y = 50 + 39 * Math.sin(tickAngle);
                return `<button type="button" class="share-clock-tick${active ? ' active' : ''}"
                    data-tick="${number}" style="left:${x}%;top:${y}%">${state.mode === 'hour' ? number : pad(number)}</button>`;
            }).join('');

            const selectedStep = state.mode === 'hour' ? state.hour % 12 : state.minute / 5;
            // The hand element points down by default. Subtract 180 degrees so
            // zero is at 12 o'clock and values advance clockwise like Flutter.
            const handAngle = selectedStep * Math.PI / 6 - Math.PI;
            const minuteAngle = state.minute * Math.PI / 30 - Math.PI / 2;
            const minuteDotX = 50 + 39 * Math.cos(minuteAngle);
            const minuteDotY = 50 + 39 * Math.sin(minuteAngle);

            content.innerHTML = `
              <div class="share-picker-heading">
                <span id="sharePickerTitle">اختيار الوقت</span>
                <button type="button" class="share-picker-close" aria-label="إغلاق" onclick="closeSharePicker()">
                  <span class="material-icons-round">close</span>
                </button>
              </div>
              <div class="share-clock-top">
                <button type="button" class="share-clock-number${state.mode === 'hour' ? ' active' : ''}" data-mode="hour">${state.hour}</button>
                <span class="share-clock-colon">:</span>
                <button type="button" class="share-clock-number${state.mode === 'minute' ? ' active' : ''}" data-mode="minute">${pad(state.minute)}</button>
                <div class="share-clock-period">
                  <button type="button" data-period="am" class="${state.period === 'am' ? 'active' : ''}">AM</button>
                  <button type="button" data-period="pm" class="${state.period === 'pm' ? 'active' : ''}">PM</button>
                </div>
              </div>
              <div class="share-clock-face" id="shareClockFace">
                <div class="share-clock-hand" style="transform:translateX(-50%) rotate(${handAngle}rad)"></div>
                ${ticks}
                ${state.mode === 'minute' && state.minute % 5 !== 0 ? `<span class="share-clock-minute-dot" style="left:${minuteDotX}%;top:${minuteDotY}%"></span>` : ''}
                <span class="share-clock-center"></span>
              </div>
              <div class="share-picker-actions">
                <button type="button" class="share-picker-cancel" onclick="closeSharePicker()">إلغاء</button>
                <button type="button" class="share-picker-confirm" id="shareTimeConfirm">اختيار</button>
              </div>`;

            content.querySelectorAll('[data-mode]').forEach(button => {
                button.addEventListener('click', () => {
                    state.mode = button.dataset.mode;
                    draw();
                });
            });

            content.querySelectorAll('[data-period]').forEach(button => {
                button.addEventListener('click', () => {
                    state.period = button.dataset.period;
                    draw();
                });
            });

            content.querySelectorAll('[data-tick]').forEach(button => {
                button.addEventListener('click', () => {
                    if (state.mode === 'hour') {
                        state.hour = Number(button.dataset.tick);
                        state.mode = 'minute';
                    } else {
                        state.minute = Number(button.dataset.tick);
                    }
                    draw();
                });
            });

            // Flutter's minute dial can be adjusted between the printed
            // five-minute marks. Dragging/tapping the dial selects 1-minute steps.
            const face = content.querySelector('#shareClockFace');
            let dragging = false;
            const setFromPointer = event => {
                if (state.mode !== 'minute') return;
                const rect = face.getBoundingClientRect();
                const dx = event.clientX - (rect.left + rect.width / 2);
                const dy = event.clientY - (rect.top + rect.height / 2);
                let degrees = Math.atan2(dy, dx) * 180 / Math.PI + 90;
                degrees = (degrees + 360) % 360;
                state.minute = Math.round(degrees / 6) % 60;
                const hand = face.querySelector('.share-clock-hand');
                if (hand) {
                    const exactAngle = state.minute * Math.PI / 30 - Math.PI;
                    hand.style.transform = `translateX(-50%) rotate(${exactAngle}rad)`;
                }
                content.querySelectorAll('[data-tick]').forEach(tick => {
                    tick.classList.toggle('active', Number(tick.dataset.tick) === state.minute);
                });
                const minuteButton = content.querySelector('[data-mode="minute"]');
                if (minuteButton) minuteButton.textContent = pad(state.minute);
                let dot = face.querySelector('.share-clock-minute-dot');
                if (state.minute % 5 === 0) {
                    if (dot) dot.remove();
                } else {
                    if (!dot) {
                        dot = document.createElement('span');
                        dot.className = 'share-clock-minute-dot';
                        face.appendChild(dot);
                    }
                    const theta = state.minute * Math.PI / 30 - Math.PI / 2;
                    dot.style.left = `${50 + 39 * Math.cos(theta)}%`;
                    dot.style.top = `${50 + 39 * Math.sin(theta)}%`;
                }
            };
            face.addEventListener('pointerdown', event => {
                if (state.mode !== 'minute' || event.target.closest('[data-tick]')) return;
                dragging = true;
                face.setPointerCapture?.(event.pointerId);
                setFromPointer(event);
            });
            face.addEventListener('pointermove', event => {
                if (dragging) setFromPointer(event);
            });
            face.addEventListener('pointerup', () => { dragging = false; });
            face.addEventListener('pointercancel', () => { dragging = false; });

            content.querySelector('#shareTimeConfirm').addEventListener('click', () => {
                if (!activeSharePicker) return;
                const hour = (state.hour % 12) + (state.period === 'pm' ? 12 : 0);
                activeSharePicker.input.value = `${pad(hour)}:${pad(state.minute)}`;
                updateSharePicker(activeSharePicker.input);
                closeSharePicker();
            });
        };

        draw();
    }

    // قوائم منسدلة مخصصة بتصميم موحد مع حقول Flutter.
    // تبقى عناصر select الأصلية موجودة لإرسال القيم نفسها إلى Laravel.
    let activeShareSelect = null;

    function initializeShareSelects(container) {
        if (!container) return;

        container.querySelectorAll('select').forEach(select => {
            if (select.dataset.shareSelectReady === 'true') return;

            const wasRequired = select.required;
            select.dataset.shareRequired = select.dataset.shareRequired || (wasRequired ? 'true' : 'false');
            select.required = false;
            select.dataset.shareSelectReady = 'true';

            let host = select.parentElement;
            if (!host || (!host.classList.contains('share-input-icon') &&
                          !host.classList.contains('share-select-host'))) {
                host = document.createElement('div');
                host.className = 'share-select-host';
                select.parentNode.insertBefore(host, select);
                host.appendChild(select);
            } else {
                host.classList.add('share-select-host');
            }

            const trigger = document.createElement('button');
            trigger.type = 'button';
            trigger.className = 'share-select-trigger form-control';
            trigger.setAttribute('aria-haspopup', 'listbox');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.textContent = getShareSelectLabel(select);
            trigger.addEventListener('click', () => openShareSelect(select));

            select.classList.add('share-native-select-source');
            host.appendChild(trigger);
        });
    }

    function getShareSelectLabel(select) {
        const option = select.options[select.selectedIndex];
        return option && option.value ? option.textContent.trim() : (option?.textContent.trim() || 'اختاري');
    }

    function updateShareSelectDisplay(select) {
        if (!select) return;
        const trigger = select.closest('.share-select-host')?.querySelector('.share-select-trigger');
        if (!trigger) return;
        trigger.textContent = getShareSelectLabel(select);
        trigger.classList.toggle('has-value', Boolean(select.value));
        trigger.classList.remove('is-invalid');
    }

    function closeShareSelect() {
        if (activeShareSelect?.menu) activeShareSelect.menu.remove();
        if (activeShareSelect?.trigger) {
            activeShareSelect.trigger.setAttribute('aria-expanded', 'false');
        }
        activeShareSelect = null;
    }

    function openShareSelect(select) {
        if (!select) return;
        if (activeShareSelect?.select === select) {
            closeShareSelect();
            return;
        }
        closeShareSelect();

        const trigger = select.closest('.share-select-host')?.querySelector('.share-select-trigger');
        if (!trigger) return;

        const rect = trigger.getBoundingClientRect();
        const menu = document.createElement('div');
        menu.className = 'share-select-menu';
        menu.setAttribute('role', 'listbox');
        menu.setAttribute('aria-label', select.getAttribute('aria-label') || 'الخيارات');
        menu.style.position = 'fixed';
        menu.style.left = `${Math.max(8, rect.left)}px`;
        menu.style.width = `${Math.min(rect.width, window.innerWidth - 16)}px`;

        const estimatedHeight = Math.min(select.options.length * 46 + 8, 240);
        const spaceBelow = window.innerHeight - rect.bottom - 12;
        const placeAbove = spaceBelow < Math.min(estimatedHeight, 180) && rect.top > spaceBelow;
        menu.style.top = placeAbove
            ? `${Math.max(8, rect.top - Math.min(estimatedHeight, 240) - 6)}px`
            : `${Math.min(window.innerHeight - 50, rect.bottom + 6)}px`;

        [...select.options].forEach((option, index) => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'share-select-option';
            item.setAttribute('role', 'option');
            item.setAttribute('aria-selected', option.value === select.value ? 'true' : 'false');
            item.disabled = option.disabled;
            item.textContent = option.textContent.trim();
            if (option.value === select.value) item.classList.add('selected');
            item.addEventListener('click', () => {
                select.value = option.value;
                select.dispatchEvent(new Event('change', { bubbles: true }));
                updateShareSelectDisplay(select);
                closeShareSelect();
            });
            menu.appendChild(item);
        });

        document.body.appendChild(menu);
        trigger.setAttribute('aria-expanded', 'true');
        activeShareSelect = { select, trigger, menu };
    }

    document.addEventListener('click', event => {
        if (!activeShareSelect) return;
        if (activeShareSelect.menu.contains(event.target) ||
            activeShareSelect.trigger.contains(event.target)) return;
        closeShareSelect();
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeShareSelect();
    });

    window.addEventListener('resize', closeShareSelect);
    document.addEventListener('scroll', event => {
        if (activeShareSelect && !activeShareSelect.menu.contains(event.target)) closeShareSelect();
    }, true);

    function openRecordModal(type) {

        if (!accessToken) {

            endSession('انتهت صلاحية الجلسة. يرجى إدخال رمز الدخول مجددًا.');

            return;

        }



        currentRecordType = type;

        recordMessage.hidden = true;

        recordMessage.textContent = '';



        const today = getLocalDate();

        const currentTime = getLocalTime();



        const icon = (name, extra = '') => `<span class="material-icons-round ${extra}" aria-hidden="true">${name}</span>`;
        const pickerField = (name, label, kind, placeholder, iconName, extra = '') => `
          <div class="form-group share-picker-group ${extra}">
            <div class="share-picker-shell" onclick="openSharePicker(this)">
              ${icon(iconName)}<span class="share-picker-text">${placeholder}</span>
              <input class="share-picker-value" type="${kind}" name="${name}" ${kind === 'date' ? `max="${today}"` : ''} value="${kind === 'date' ? today : currentTime.slice(0,5)}" required aria-label="${label}" tabindex="-1" onchange="updateSharePicker(this)">
            </div>
          </div>`;
        const dateTimeFields = (prefix, label) => `<div class="share-pair share-date-time">
          ${pickerField(prefix+'_date','تاريخ '+label,'date','تاريخ '+label,'calendar_month')}
          ${pickerField(prefix+'_time','وقت '+label,'time','وقت '+label,'access_time')}
        </div>`;
        const actions = ``;
        const notice = `<div class="share-notice">${icon('info_outline')}<span>يرجى التأكد من صحة البيانات قبل الحفظ</span></div>`;
        if (type === 'meal') {
          modalTitle.textContent = 'تسجيل وجبة';
          modalBody.innerHTML = `
            <form id="recordForm" class="flutter-form">
              <h4 class="share-section">${icon('restaurant')} إضافة وجبة جديدة</h4>
              <div class="form-group"><div class="share-input-icon">${icon('list')}<select class="form-control" name="meal_type" required><option value="">نوع الوجبة</option>${['الإفطار','الغداء','العشاء','وجبة خفيفة'].map(x=>`<option>${x}</option>`).join('')}</select></div></div>
              <button class="share-food-trigger" type="button" onclick="openFoodPicker()">${icon('layers')}<span id="selectedFoodsLabel">اختيار الأطعمة</span>${icon('keyboard_arrow_down')}</button>
              <p class="share-validation" id="foodsError" hidden>اختاري طعامًا واحدًا على الأقل</p>
              ${dateTimeFields('meal','الوجبة')}
              <div class="share-saved-meals" role="note"><span class="material-icons-round">restaurant_menu</span><span>الوجبات المسجلة <small>تُعرض من تطبيق ولي الأمر</small></span><span class="material-icons-round">keyboard_arrow_down</span></div>
              ${notice}${actions}
            </form>
            <div class="share-food-overlay" id="foodPicker" hidden>
              <div class="share-food-sheet" role="dialog" aria-modal="true" aria-label="اختيار الأطعمة">
                <div class="share-sheet-header"><strong>${icon('layers')} اختيار الأطعمة</strong><button type="button" onclick="closeFoodPicker()" aria-label="إغلاق">${icon('close')}</button></div>
                <label for="foodGroup">المجموعة الغذائية</label>
                <select id="foodGroup" class="form-control" onchange="renderFoodChoices()"><option value="">اختاري المجموعة الغذائية</option>${Object.keys(shareFoodGroups).map(x=>`<option>${x}</option>`).join('')}</select>
                <div id="foodChoices" class="share-food-choices"><p>اختاري مجموعة غذائية لعرض الأطعمة المتاحة</p></div>
                <label for="customFood">أخرى</label><div class="share-add-food"><input id="customFood" class="form-control" maxlength="50" placeholder="اسم طعام غير موجود"><button type="button" onclick="addCustomFood()">${icon('add')}</button></div>
                <p id="foodPickerError" class="share-validation" hidden></p>
                <h4>الأطعمة المختارة <small id="foodCount">0 أطعمة</small></h4><div id="chosenFoodChips" class="share-food-choices"></div>
                <button type="button" class="save-button share-confirm" onclick="confirmFoodPicker()">تم الاختيار</button>
              </div>
            </div>`;
          window.shareSelectedFoods = {};
        } else if (type === 'activity') {
          modalTitle.textContent = 'تسجيل النشاط';
          modalBody.innerHTML = `<form id="recordForm" class="flutter-form">
            <h4 class="share-section">${icon('directions_run')} بيانات النشاط</h4>
            <div class="share-pair">
              <div class="form-group"><div class="share-input-icon">${icon('directions_run')}<select class="form-control" id="activityChoice" onchange="toggleCustomActivity()" required><option value="">نوع النشاط</option>${['المشي','الجري','السباحة','ركوب الدراجة','كرة القدم','نشاط آخر'].map(x=>`<option>${x}</option>`).join('')}</select></div><input id="activityName" name="activity_name" class="form-control share-custom" placeholder="اكتب اسم النشاط" minlength="2" maxlength="50" hidden></div>
              <div class="form-group"><div class="share-input-icon">${icon('access_time')}<select class="form-control" name="duration" required><option value="">مدة النشاط</option>${['15 دقيقة','30 دقيقة','45 دقيقة','ساعة','أكثر من ساعة'].map(x=>`<option>${x}</option>`).join('')}</select></div></div>
            </div><div class="share-pair"><div class="form-group"><div class="share-input-icon">${icon('bar_chart')}<select class="form-control" name="intensity" required><option value="">شدة النشاط</option>${['خفيف','متوسط','شديد'].map(x=>`<option>${x}</option>`).join('')}</select></div></div>${pickerField('activity_date','تاريخ النشاط','date','تاريخ النشاط','calendar_month')}</div>
            ${pickerField('activity_time','وقت النشاط','time','وقت النشاط','access_time')}
            ${notice}${actions}</form>`;
        } else if (type === 'glucose_reading') {
          modalTitle.textContent = 'تسجيل قراءة السكر';
          modalBody.innerHTML = `<form id="recordForm" class="flutter-form">
            <p class="share-subtitle">سجّل مستوى السكر لطفلك للحفاظ على متابعة دقيقة</p>
            <div class="share-tips"><strong>${icon('lightbulb_outline')} نصائح مهمة</strong><p>• لقياس السكر بعد الوجبة، يُستخدم غالبًا توقيت ساعتين من بداية الأكل، وفق خطة الطبيب.<br>• خذ عينة الدم من جانب الإصبع.<br>• اتبع تعليمات جهاز القياس بشأن القطرة الأولى أو الثانية.<br>• تأكد أن اليد نظيفة وجافة قبل القياس، دون بقايا ماء أو كحول.</p></div>
            <h4 class="share-section">${icon('monitor_heart')} بيانات قراءة السكر</h4>
            <div class="form-group"><label>نوع القراءة</label><div class="share-radio-row">${['قبل الوجبة','بعد الوجبة','صائم'].map(x=>`<label class="share-radio"><input type="radio" name="reading_type" value="${x}" required onchange="toggleMealStatus()"><span>${x}</span></label>`).join('')}</div></div>
            <div class="share-pair"><div class="form-group"><label for="glucoseLevel">مستوى السكر (mg/dL)</label><input class="form-control" id="glucoseLevel" name="glucose_level" type="number" min="0.1" max="999.9" step="0.1" placeholder="مثلاً 120" required></div><div class="form-group"><label>تاريخ القراءة</label>${pickerField('reading_date','تاريخ القراءة','date','اختاري التاريخ','calendar_today')}</div></div>
            <div class="form-group"><label>وقت القراءة</label>${pickerField('reading_time','وقت القراءة','time','مثلاً 04:00 م','access_time')}</div>
            <div class="form-group" id="mealStatusGroup" hidden><label for="mealStatus">حالة تسجيل الوجبة</label><select class="form-control" name="meal_status" id="mealStatus" onchange="updateMealStatusHelp()"><option value="">اختاري حالة الوجبة</option><option>الوجبة مسجلة في التطبيق</option><option>الوجبة غير مسجلة في التطبيق</option></select><div class="share-meal-help" id="mealStatusHelp" hidden></div></div>
            ${actions}</form>`;
        } else { return; }
        recordModal.hidden = false;
        document.getElementById('topSaveButton').disabled = false;

        const recordForm = document.getElementById('recordForm');

        initializeShareSelects(modalBody);
        recordForm.addEventListener('submit', submitRecord);
        recordForm.querySelectorAll('.share-picker-shell input').forEach(updateSharePicker);



        if (type === 'meal') renderFoodChoices();
        if (type === 'activity') toggleCustomActivity();

    }



    // =====================================

    // إغلاق نافذة التسجيل

    // =====================================



    function closeRecordModal() {

        closeShareSelect();
        closeSharePicker();
        recordModal.hidden = true;

        modalBody.innerHTML = '';

        recordMessage.hidden = true;

        currentRecordType = null;

    }



    // =====================================

    // إرسال التسجيل إلى Laravel

    // =====================================



    async function submitRecord(event) {

        event.preventDefault();



        if (!accessToken) {

            endSession('انتهت صلاحية الجلسة. يرجى إدخال رمز الدخول مجددًا.');

            return;

        }



        const form = event.target;

        const missingSelect = [...form.querySelectorAll('select[data-share-required="true"]')]
            .find(select => !String(select.value || '').trim());
        if (missingSelect) {
            const host = missingSelect.closest('.share-select-host');
            const trigger = host?.querySelector('.share-select-trigger');
            if (trigger) {
                trigger.classList.add('is-invalid');
                trigger.focus();
                openShareSelect(missingSelect);
            }
            showRecordMessage('يرجى اختيار ' + (missingSelect.getAttribute('aria-label') || 'قيمة من القائمة') + '.');
            return;
        }

        const saveButton = document.getElementById('topSaveButton');

        const originalButtonText = saveButton.textContent;

        const formData = new FormData(form);

        let recordData = {};



        try {

            // بيانات الوجبة

            if (currentRecordType === 'meal') {

                const ingredients = Object.fromEntries(Object.entries(window.shareSelectedFoods || {}).filter(([_, items]) => items.length));
                if (!Object.keys(ingredients).length) throw new Error('اختاري طعامًا واحدًا على الأقل.');
                recordData = {
                    meal_type: formData.get('meal_type'),
                    ingredients,
                    meal_date: formData.get('meal_date'),
                    meal_time: normalizeTime(formData.get('meal_time')),
                    notes: null
                };

            }



            // بيانات النشاط

            else if (currentRecordType === 'activity') {

                recordData = {

                    activity_name: document.getElementById('activityChoice').value === 'نشاط آخر' ? String(formData.get('activity_name') || '').trim() : document.getElementById('activityChoice').value,

                    duration: formData.get('duration'),

                    intensity: formData.get('intensity'),

                    activity_date: formData.get('activity_date'),

                    activity_time: normalizeTime(formData.get('activity_time'))

                };

            }



            // بيانات قراءة السكر

            else if (currentRecordType === 'glucose_reading') {

                const readingType = formData.get('reading_type');



                recordData = {

                    reading_type: readingType,

                    glucose_level: formData.get('glucose_level'),

                    unit: 'mg/dL',

                    reading_date: formData.get('reading_date'),

                    reading_time: normalizeTime(formData.get('reading_time'))

                };



                if (readingType === 'بعد الوجبة') {

                    const mealStatus = formData.get('meal_status');

                    if (!mealStatus) {

                        throw new Error('يرجى تحديد حالة الوجبة.');

                    }

                    if (mealStatus === 'الوجبة غير مسجلة في التطبيق') throw new Error('يرجى تسجيل الوجبة أولًا من بطاقة الوجبات، ثم حفظ قراءة السكر.');
                    recordData.meal_status = mealStatus;

                }

            }



            const dateField = form.querySelector('input[type="date"]');
            const timeField = form.querySelector('input[type="time"]');
            if (dateField && timeField && dateField.value && timeField.value) {
                const selected = new Date(`${dateField.value}T${timeField.value}`);
                if (selected > new Date()) throw new Error('لا يمكن اختيار تاريخ أو وقت مستقبلي');
                const birth = window.shareChildBirthDate;
                if (birth && dateField.value < birth.slice(0, 10)) throw new Error('لا يمكن اختيار تاريخ قبل ميلاد الطفل');
            }
            if (currentRecordType === 'activity' && !recordData.activity_name) throw new Error('يرجى اختيار نوع النشاط');
            saveButton.disabled = true;

            saveButton.textContent = 'جارٍ...';

            recordMessage.hidden = true;



            const response = await fetch('/api/shared-record-submissions', {

                method: 'POST',

                headers: {

                    'Accept': 'application/json',

                    'Content-Type': 'application/json',

                    'Authorization': 'Bearer ' + accessToken

                },

                body: JSON.stringify({

                    record_type: currentRecordType,

                    record_data: recordData

                })

            });



            let result;

            try {

                result = await readApiResponse(response);

            } catch (error) {

                if ([401, 403, 410].includes(error.status)) {

                    endSession(error.message || 'انتهت صلاحية المشاركة.');

                    return;

                }

                throw error;

            }



            closeRecordModal();

            showToast(result.message || 'تم تسجيل البيانات وحفظها بنجاح.');



        } catch (error) {

            showRecordMessage(error.message);

        } finally {

            if (saveButton) {

                saveButton.disabled = false;

                saveButton.textContent = originalButtonText;

            }

        }

    }



    // =====================================

    // رسالة نجاح مؤقتة

    // =====================================



    function showToast(message) {

        clearTimeout(toastTimer);

        toast.textContent = message;

        toast.hidden = false;



        toastTimer = setTimeout(function () {

            toast.hidden = true;

        }, 3500);

    }







    // =====================================

    // إغلاق النافذة بالضغط خارجها أو Escape

    // =====================================



    recordModal.addEventListener('click', function (event) {

        if (event.target === recordModal) closeRecordModal();

    });



    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape' && !recordModal.hidden) {

            closeRecordModal();

        }

    });



    // =====================================

    // استعادة الجلسة عند فتح الصفحة مجددًا

    // =====================================



    (function restoreExistingSession() {

        const storedSession = readStoredSession();



        if (storedSession) {

            renderDashboard(storedSession);

        }

    })();

</script>

</body>

</html>







