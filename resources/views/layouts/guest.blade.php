<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Daily Progress Report') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            (function() {
                var t = localStorage.getItem('dpr-theme') || 'dark';
                document.documentElement.setAttribute('data-theme', t);
            })();
        </script>
        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            :root, [data-theme="dark"] {
                --bg: #07111f;
                --bg-2: #0c1c30;
                --gold: #c9a84c;
                --gold-lt: #e2c97e;
                --border: rgba(201,168,76,0.2);
                --txt: #e8eaf0;
                --muted: #4a5a72;
                --input-bg: #07111f;
                --input-txt: #e8eaf0;
                --label-color: #8a9ab8;
                --link-color: #4a5a72;
                --link-hover: #c9a84c;
                --sub-color: #4a5a72;
                --brand-color: #c9a84c;
            }
            [data-theme="light"] {
                --bg: #f0f4f8;
                --bg-2: #ffffff;
                --gold: #a07820;
                --gold-lt: #c9a84c;
                --border: rgba(160,120,32,0.25);
                --txt: #1a2332;
                --muted: #8a9ab8;
                --input-bg: #f8fafc;
                --input-txt: #1a2332;
                --label-color: #5a6a82;
                --link-color: #5a6a82;
                --link-hover: #a07820;
                --sub-color: #8a9ab8;
                --brand-color: #a07820;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--bg);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1.5rem;
                transition: background-color 0.2s;
            }
            .login-wrap {
                display: flex;
                width: 100%;
                max-width: 900px;
                min-height: 520px;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            }
            /* Left panel — image */
            .login-panel-img {
                flex: 1;
                position: relative;
                display: none;
            }
            .login-panel-img-bg {
                position: absolute;
                inset: 0;
                background-image: url('{{ asset('work.jpg') }}');
                background-size: cover;
                background-position: center;
            }
            .login-panel-img-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(160deg, rgba(7,17,31,0.75) 0%, rgba(7,17,31,0.45) 100%);
            }
            .login-panel-img-text {
                position: absolute;
                bottom: 2.5rem;
                left: 2rem;
                right: 2rem;
                z-index: 10;
            }
            .login-panel-img-text h2 {
                font-size: 1.5rem;
                font-weight: 700;
                color: #ffffff;
                line-height: 1.3;
                margin-bottom: 0.5rem;
            }
            .login-panel-img-text h2 span { color: #c9a84c; }
            .login-panel-img-text p {
                font-size: 0.78rem;
                color: rgba(232,234,240,0.65);
                line-height: 1.6;
            }
            /* Right panel — form */
            .login-panel-form {
                width: 100%;
                background-color: var(--bg-2);
                padding: 3rem 2.5rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                position: relative;
                transition: background-color 0.2s;
            }
            .login-brand {
                font-size: 0.7rem;
                font-weight: 600;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                color: var(--brand-color);
                margin-bottom: 2rem;
            }
            .login-title {
                font-size: 1.4rem;
                font-weight: 700;
                color: var(--txt);
                margin-bottom: 0.35rem;
            }
            .login-sub {
                font-size: 0.8rem;
                color: var(--sub-color);
                margin-bottom: 2rem;
            }
            /* Theme toggle on login page */
            .login-theme-toggle {
                position: absolute;
                top: 1.25rem;
                right: 1.25rem;
                background: none;
                border: 1px solid var(--border);
                border-radius: 4px;
                cursor: pointer;
                padding: 0.3rem 0.45rem;
                color: var(--muted);
                display: flex;
                align-items: center;
                font-size: 0.85rem;
                line-height: 1;
                transition: color 0.15s, border-color 0.15s;
            }
            .login-theme-toggle:hover { color: var(--gold); border-color: var(--gold); }
            .icon-sun, .icon-moon { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
            [data-theme="dark"] .icon-sun  { display: block; }
            [data-theme="dark"] .icon-moon { display: none; }
            [data-theme="light"] .icon-sun  { display: none; }
            [data-theme="light"] .icon-moon { display: block; }
            /* Override Breeze component styles */
            .login-panel-form label {
                display: block;
                font-size: 0.75rem;
                font-weight: 500;
                color: var(--label-color);
                margin-bottom: 0.4rem;
                letter-spacing: 0.03em;
            }
            .login-panel-form input[type="email"],
            .login-panel-form input[type="password"],
            .login-panel-form input[type="text"] {
                width: 100%;
                background-color: var(--input-bg);
                border: 1px solid var(--border);
                border-radius: 4px;
                color: var(--input-txt);
                font-family: 'Inter', sans-serif;
                font-size: 0.875rem;
                padding: 0.6rem 0.85rem;
                outline: none;
                transition: border-color 0.2s, background-color 0.2s;
            }
            .login-panel-form input[type="email"]:focus,
            .login-panel-form input[type="password"]:focus,
            .login-panel-form input[type="text"]:focus {
                border-color: var(--gold);
                box-shadow: 0 0 0 2px rgba(201,168,76,0.12);
            }
            .login-panel-form input[type="checkbox"] {
                accent-color: var(--gold);
            }
            .login-panel-form .text-sm { font-size: 0.78rem; }
            .login-panel-form .text-gray-600 { color: var(--link-color); }
            .login-panel-form .text-gray-600:hover { color: var(--link-hover); }
            .login-panel-form button[type="submit"],
            .login-panel-form .btn-submit {
                background-color: var(--gold);
                color: #07111f;
                font-family: 'Inter', sans-serif;
                font-size: 0.8rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                border: none;
                border-radius: 4px;
                padding: 0.65rem 1.75rem;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            .login-panel-form button[type="submit"]:hover { background-color: var(--gold-lt); }
            /* Error messages */
            .login-panel-form .text-red-600 { color: #f87171; font-size: 0.75rem; }
            [data-theme="light"] .login-panel-form .text-red-600 { color: #dc2626; }
            /* Session status */
            .login-panel-form .text-green-600 { color: #4ade80; font-size: 0.78rem; }
            [data-theme="light"] .login-panel-form .text-green-600 { color: #166534; }
            @media (min-width: 700px) {
                .login-panel-img { display: block; }
                .login-panel-form { width: 420px; flex-shrink: 0; }
            }
            @media (max-width: 699px) {
                .login-wrap { border-radius: 8px; }
                .login-panel-form { padding: 2.5rem 1.75rem; }
            }
        </style>
    </head>
    <body>
        <div class="login-wrap">
            <!-- Left: image panel -->
            <div class="login-panel-img">
                <div class="login-panel-img-bg"></div>
                <div class="login-panel-img-overlay"></div>
                <div class="login-panel-img-text">
                    <h2>Daily Progress<br><span>Report System</span></h2>
                    <p>Secure access for authorized personnel only.</p>
                </div>
            </div>
            <!-- Right: form panel -->
            <div class="login-panel-form">
                <!-- Theme toggle -->
                <button class="login-theme-toggle" onclick="toggleTheme()" title="Toggle light/dark mode">
                    <svg class="icon-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    <svg class="icon-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>
                <p class="login-brand">Daily Progress Report</p>
                <h1 class="login-title">Sign In</h1>
                <p class="login-sub">Enter your credentials to access the system.</p>
                {{ $slot }}
            </div>
        </div>
        <script>
            function toggleTheme() {
                var html = document.documentElement;
                var current = html.getAttribute('data-theme') || 'dark';
                var next = current === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                localStorage.setItem('dpr-theme', next);
            }
        </script>
    </body>
</html>
