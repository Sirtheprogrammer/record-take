<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daily Progress Report</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            :root {
                --dark:     #07111f;
                --dark-2:   #0c1c30;
                --dark-3:   #112240;
                --gold:     #c9a84c;
                --gold-lt:  #e2c97e;
                --gold-dim: rgba(201,168,76,0.15);
                --border:   rgba(201,168,76,0.18);
                --txt:      #e8eaf0;
                --muted:    #6b7a99;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--dark);
                color: var(--txt);
                min-height: 100vh;
            }

            /* ── TOPBAR ── */
            .topbar {
                position: absolute;
                top: 0; left: 0; right: 0;
                z-index: 50;
                background: rgba(7,17,31,0.55);
                border-bottom: 1px solid rgba(201,168,76,0.2);
            }
            .topbar-inner {
                max-width: 1100px;
                margin: 0 auto;
                padding: 0 1.5rem;
                height: 62px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .brand {
                font-size: 1rem;
                font-weight: 600;
                color: var(--gold);
                letter-spacing: 0.03em;
            }
            .btn-nav {
                display: inline-block;
                background-color: var(--gold);
                color: var(--dark);
                font-family: 'Inter', sans-serif;
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                text-decoration: none;
                padding: 0.45rem 1.25rem;
                border-radius: 3px;
                transition: background-color 0.2s;
            }
            .btn-nav:hover { background-color: var(--gold-lt); }

            /* ── HERO ── */
            .hero {
                position: relative;
                width: 100%;
                min-height: 100vh;
                display: flex;
                align-items: center;
                overflow: hidden;
            }
            .hero-bg {
                position: absolute;
                inset: 0;
                background-image: url('{{ asset('work.jpg') }}');
                background-size: cover;
                background-position: center center;
                background-repeat: no-repeat;
            }
            /* Dark overlay so text is readable */
            .hero-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    105deg,
                    rgba(7,17,31,0.88) 0%,
                    rgba(7,17,31,0.75) 45%,
                    rgba(7,17,31,0.35) 100%
                );
            }
            .hero-content {
                position: relative;
                z-index: 10;
                max-width: 1100px;
                margin: 0 auto;
                padding: 8rem 1.5rem 5rem;
                width: 100%;
            }
            .hero-eyebrow {
                font-size: 0.68rem;
                font-weight: 600;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: var(--gold);
                margin-bottom: 1.1rem;
            }
            .hero-title {
                font-size: clamp(2rem, 5vw, 3.6rem);
                font-weight: 700;
                line-height: 1.15;
                color: #ffffff;
                margin-bottom: 1.25rem;
                max-width: 600px;
            }
            .hero-title span { color: var(--gold); }
            .hero-sub {
                font-size: clamp(0.85rem, 1.5vw, 1rem);
                color: rgba(232,234,240,0.75);
                line-height: 1.75;
                max-width: 440px;
                margin-bottom: 2.5rem;
            }
            .hero-btn {
                display: inline-block;
                background-color: var(--gold);
                color: var(--dark);
                font-family: 'Inter', sans-serif;
                font-size: 0.82rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                text-decoration: none;
                padding: 0.75rem 2rem;
                border-radius: 3px;
                transition: background-color 0.2s;
            }
            .hero-btn:hover { background-color: var(--gold-lt); }

            /* ── FEATURES ── */
            .features {
                background-color: var(--dark-2);
                border-top: 1px solid var(--border);
            }
            .features-inner {
                max-width: 1100px;
                margin: 0 auto;
                padding: 4rem 1.5rem;
            }
            .features-header {
                margin-bottom: 2.5rem;
            }
            .features-header h2 {
                font-size: clamp(1.15rem, 2.5vw, 1.5rem);
                font-weight: 700;
                color: var(--txt);
            }
            .features-header p {
                font-size: 0.82rem;
                color: var(--muted);
                margin-top: 0.35rem;
            }
            .features-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1px;
                background-color: var(--border);
                border: 1px solid var(--border);
                border-radius: 8px;
                overflow: hidden;
            }
            .feat {
                background-color: var(--dark-2);
                padding: 1.6rem;
                transition: background-color 0.2s;
            }
            .feat:hover { background-color: var(--dark-3); }
            .feat-icon {
                width: 32px;
                height: 32px;
                margin-bottom: 0.9rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .feat-icon svg {
                width: 22px;
                height: 22px;
                stroke: var(--gold);
                fill: none;
                stroke-width: 1.8;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
            .feat h3 {
                font-size: 0.875rem;
                font-weight: 600;
                color: var(--txt);
                margin-bottom: 0.4rem;
            }
            .feat p {
                font-size: 0.78rem;
                color: var(--muted);
                line-height: 1.65;
            }

            /* ── FOOTER ── */
            .footer {
                background-color: var(--dark);
                border-top: 1px solid rgba(255,255,255,0.04);
                padding: 1.25rem 1.5rem;
                text-align: center;
                font-size: 0.72rem;
                color: #2d3a50;
            }

            /* ── RESPONSIVE ── */
            @media (max-width: 640px) {
                .hero-content { padding: 7rem 1.25rem 4rem; }
                .hero-btn { width: 100%; text-align: center; display: block; }
                .features-grid { grid-template-columns: 1fr; }
                .features-inner { padding: 2.5rem 1.25rem; }
            }
            @media (min-width: 641px) and (max-width: 900px) {
                .features-grid { grid-template-columns: repeat(2, 1fr); }
            }
        </style>
    </head>
    <body>

        <!-- Topbar (absolute, sits over hero) -->
        <header class="topbar">
            <div class="topbar-inner">
                <span class="brand">DAILY PROGRESS REPORT</span>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-nav">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-nav">Sign In</a>
                    @endauth
                @endif
            </div>
        </header>

        <!-- Hero — full-screen background image with text overlay -->
        <section class="hero">
            <div class="hero-bg"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <p class="hero-eyebrow">activity Management System</p>
                <h1 class="hero-title">
                    Daily Progress<br>
                    <span>Report System</span>
                </h1>
                <p class="hero-sub">
                    A secure, role-based System for recording and managing daily office activities across organization.
                </p>
                @auth
                    <a href="{{ url('/dashboard') }}" class="hero-btn">Open Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hero-btn">Sign In to System</a>
                @endauth
            </div>
        </section>

        <!-- Features -->
        <section class="features">
            <div class="features-inner">
                <div class="features-header">
                    <h2>System Modules</h2>
                    <p>Core capabilities available within the System</p>
                </div>
                <div class="features-grid">

                    <div class="feat">
                        <div class="feat-icon">
                            <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3>Daily Reports</h3>
                        <p>Create and submit structured daily activity records with timestamps and user attribution.</p>
                    </div>

                    <div class="feat">
                        <div class="feat-icon">
                            <svg viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3>User Management</h3>
                        <p>Admins create and manage user accounts, assign roles, and control system access.</p>
                    </div>

                    <div class="feat">
                        <div class="feat-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h3>Role-Based Access</h3>
                        <p>Three permission levels ensure each user only accesses what their role permits.</p>
                    </div>

                    <div class="feat">
                        <div class="feat-icon">
                            <svg viewBox="0 0 24 24"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        </div>
                        <h3>Report Archive</h3>
                        <p>All submitted reports are stored and accessible for review, filtering, and reference.</p>
                    </div>

                    <div class="feat">
                        <div class="feat-icon">
                            <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h3>Audit Trail</h3>
                        <p>Every action is logged with user identity and timestamp for full accountability.</p>
                    </div>

                    <div class="feat">
                        <div class="feat-icon">
                            <svg viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3>Admin Dashboard</h3>
                        <p>Consolidated statistics and overview of all users, reports, and system activity.</p>
                    </div>

                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            &copy; {{ date('Y') }} Daily Progress Report &mdash; Internal Use Only
        </footer>

    </body>
</html>
