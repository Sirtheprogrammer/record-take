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
        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            /* ── DARK THEME (default) ── */
            :root,
            [data-theme="dark"] {
                --bg:       #07111f;
                --bg-2:     #0c1c30;
                --bg-3:     #112240;
                --gold:     #c9a84c;
                --gold-lt:  #e2c97e;
                --border:   rgba(201,168,76,0.18);
                --border-2: rgba(255,255,255,0.05);
                --txt:      #e8eaf0;
                --txt-2:    #c8cfe0;
                --muted:    #6b7a99;
                --muted-2:  #4a5a72;
                --input-bg: #07111f;
                --card-bg:  #0c1c30;
                --table-head: #07111f;
                --row-hover: rgba(255,255,255,0.02);
                --shadow:   0 4px 20px rgba(0,0,0,0.4);
            }

            /* ── LIGHT THEME ── */
            [data-theme="light"] {
                --bg:       #f0f4f8;
                --bg-2:     #ffffff;
                --bg-3:     #e8edf3;
                --gold:     #a07820;
                --gold-lt:  #c9a84c;
                --border:   rgba(160,120,32,0.2);
                --border-2: rgba(0,0,0,0.06);
                --txt:      #1a2332;
                --txt-2:    #2d3a50;
                --muted:    #5a6a82;
                --muted-2:  #8a9ab8;
                --input-bg: #f8fafc;
                --card-bg:  #ffffff;
                --table-head: #f1f5f9;
                --row-hover: rgba(0,0,0,0.02);
                --shadow:   0 2px 12px rgba(0,0,0,0.08);
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--bg);
                color: var(--txt);
                min-height: 100vh;
                transition: background-color 0.2s, color 0.2s;
            }

            /* ── TOPNAV ── */
            .app-nav {
                background-color: var(--bg-2);
                border-bottom: 1px solid var(--border);
                position: sticky;
                top: 0;
                z-index: 50;
                box-shadow: var(--shadow);
            }
            .app-nav-inner {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1.5rem;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
            }
            .app-nav-brand {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--gold);
                letter-spacing: 0.03em;
                white-space: nowrap;
                text-decoration: none;
            }
            .app-nav-links {
                display: flex;
                align-items: center;
                gap: 0.25rem;
                flex: 1;
                padding-left: 1.5rem;
            }
            .app-nav-link {
                font-size: 0.8rem;
                font-weight: 500;
                color: var(--muted);
                text-decoration: none;
                padding: 0.4rem 0.75rem;
                border-radius: 4px;
                transition: color 0.15s, background-color 0.15s;
                white-space: nowrap;
            }
            .app-nav-link:hover { color: var(--txt); background-color: var(--row-hover); }
            .app-nav-link.active { color: var(--gold); background-color: rgba(201,168,76,0.08); }
            .app-nav-right {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .app-nav-user {
                font-size: 0.78rem;
                color: var(--muted);
            }
            .app-nav-logout {
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                color: var(--bg);
                background-color: var(--gold);
                text-decoration: none;
                padding: 0.38rem 1rem;
                border-radius: 3px;
                border: none;
                cursor: pointer;
                font-family: 'Inter', sans-serif;
                transition: background-color 0.15s;
            }
            .app-nav-logout:hover { background-color: var(--gold-lt); }

            /* ── THEME TOGGLE ── */
            .theme-toggle {
                background: none;
                border: 1px solid var(--border);
                border-radius: 4px;
                cursor: pointer;
                padding: 0.35rem 0.5rem;
                color: var(--muted);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: color 0.15s, border-color 0.15s;
                font-size: 0.85rem;
                line-height: 1;
            }
            .theme-toggle:hover { color: var(--gold); border-color: var(--gold); }
            .icon-sun, .icon-moon { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
            [data-theme="dark"] .icon-sun  { display: block; }
            [data-theme="dark"] .icon-moon { display: none; }
            [data-theme="light"] .icon-sun  { display: none; }
            [data-theme="light"] .icon-moon { display: block; }

            /* Mobile hamburger */
            .nav-mobile-toggle {
                display: none;
                background: none;
                border: none;
                cursor: pointer;
                padding: 0.4rem;
                color: var(--muted);
            }
            .nav-mobile-toggle svg { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; }
            .nav-mobile-menu {
                display: none;
                background-color: var(--bg-2);
                border-top: 1px solid var(--border);
                padding: 0.75rem 1.5rem 1rem;
            }
            .nav-mobile-menu.open { display: block; }
            .nav-mobile-menu a, .nav-mobile-menu button {
                display: block;
                width: 100%;
                text-align: left;
                font-size: 0.85rem;
                color: var(--muted);
                text-decoration: none;
                padding: 0.6rem 0;
                border: none;
                background: none;
                cursor: pointer;
                font-family: 'Inter', sans-serif;
                border-bottom: 1px solid var(--border-2);
            }
            .nav-mobile-menu a:last-child, .nav-mobile-menu button:last-child { border-bottom: none; }
            .nav-mobile-menu a:hover, .nav-mobile-menu button:hover { color: var(--gold); }

            /* ── PAGE HEADER ── */
            .page-header {
                background-color: var(--bg-2);
                border-bottom: 1px solid var(--border-2);
                padding: 1rem 1.5rem;
            }
            .page-header-inner {
                max-width: 1200px;
                margin: 0 auto;
            }
            .page-header-inner h2 {
                font-size: 1rem;
                font-weight: 600;
                color: var(--txt);
            }

            /* ── MAIN ── */
            .app-main {
                max-width: 1200px;
                margin: 0 auto;
                padding: 2rem 1.5rem;
            }

            /* ── GLOBAL COMPONENT OVERRIDES (light theme) ── */
            [data-theme="light"] .stat-card,
            [data-theme="light"] .dash-section,
            [data-theme="light"] .table-wrap,
            [data-theme="light"] .rpt-section,
            [data-theme="light"] .form-card,
            [data-theme="light"] .filter-bar {
                background-color: var(--card-bg);
                border-color: var(--border);
            }
            [data-theme="light"] .data-table thead tr,
            [data-theme="light"] .emp-table thead tr,
            [data-theme="light"] .recent-table thead tr {
                background-color: var(--table-head);
            }
            [data-theme="light"] .data-table td,
            [data-theme="light"] .emp-table td,
            [data-theme="light"] .recent-table td {
                color: var(--txt-2);
                border-bottom-color: var(--border-2);
            }
            [data-theme="light"] .data-table tbody tr:hover td,
            [data-theme="light"] .emp-table tbody tr:hover td {
                background-color: var(--row-hover);
            }
            [data-theme="light"] .form-input,
            [data-theme="light"] .form-select,
            [data-theme="light"] .form-textarea,
            [data-theme="light"] .filter-input,
            [data-theme="light"] .filter-select,
            [data-theme="light"] .emp-input {
                background-color: var(--input-bg);
                color: var(--txt);
                border-color: var(--border);
            }
            [data-theme="light"] .field-value { color: var(--txt); }
            [data-theme="light"] .section-title { color: var(--txt); }
            [data-theme="light"] .rpt-section-header { border-bottom-color: var(--border-2); }
            [data-theme="light"] .dash-section-header { border-bottom-color: var(--border-2); }
            [data-theme="light"] .dash-section-header h3 { color: var(--txt); }
            [data-theme="light"] .stat-card-value { color: var(--txt); }
            [data-theme="light"] .nav-mobile-menu { background-color: var(--bg-2); }
            [data-theme="light"] .alert-success { background-color: rgba(22,163,74,0.08); border-color: rgba(22,163,74,0.3); color: #166534; }
            [data-theme="light"] .alert-error { background-color: rgba(220,38,38,0.08); border-color: rgba(220,38,38,0.3); color: #991b1b; }
            [data-theme="light"] .form-error { color: #dc2626; }
            [data-theme="light"] .table-empty { color: var(--muted); }
            [data-theme="light"] .emp-empty { color: var(--muted); }
            [data-theme="light"] .pagination-wrap { border-top-color: var(--border-2); }

            /* ── GLOBAL MOBILE TABLE RESPONSIVITY ── */
            .table-scroll-wrap {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border-radius: 8px;
            }
            .table-scroll-wrap::-webkit-scrollbar { height: 4px; }
            .table-scroll-wrap::-webkit-scrollbar-track { background: transparent; }
            .table-scroll-wrap::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

            /* Mobile card list — replaces table rows on small screens */
            .mobile-card-list { display: none; }
            .mobile-card {
                background-color: var(--card-bg);
                border: 1px solid var(--border);
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 0.75rem;
            }
            .mobile-card-row {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 0.5rem;
                margin-bottom: 0.5rem;
            }
            .mobile-card-row:last-child { margin-bottom: 0; }
            .mobile-card-label {
                font-size: 0.65rem;
                font-weight: 600;
                letter-spacing: 0.07em;
                text-transform: uppercase;
                color: var(--muted);
                flex-shrink: 0;
                min-width: 80px;
            }
            .mobile-card-value {
                font-size: 0.82rem;
                color: var(--txt-2);
                text-align: right;
                flex: 1;
            }
            .mobile-card-actions {
                display: flex;
                gap: 0.5rem;
                margin-top: 0.75rem;
                padding-top: 0.75rem;
                border-top: 1px solid var(--border-2);
                flex-wrap: wrap;
            }
            .mobile-card-actions a,
            .mobile-card-actions button {
                font-size: 0.75rem;
                font-weight: 600;
                text-decoration: none;
                padding: 0.4rem 0.85rem;
                border-radius: 4px;
                border: 1px solid var(--border);
                color: var(--gold);
                background: none;
                cursor: pointer;
                font-family: 'Inter', sans-serif;
                transition: background-color 0.15s;
            }
            .mobile-card-actions a:hover,
            .mobile-card-actions button:hover { background-color: var(--row-hover); }
            .mobile-card-actions .btn-danger { color: #f87171; border-color: rgba(248,113,113,0.3); }

            /* Filter bar mobile stacking */
            @media (max-width: 640px) {
                .app-nav-links { display: none; }
                .nav-mobile-toggle { display: block; }
                .app-nav-user { display: none; }
                .app-main { padding: 1.25rem 1rem; }
                .page-header { padding: 0.85rem 1rem; }

                /* Show mobile cards, hide desktop table */
                .mobile-card-list { display: block; }
                .desktop-table-wrap { display: none; }

                /* Filter bar full-width stacking */
                .filter-bar { flex-direction: column; }
                .filter-group { min-width: 100% !important; }
                .btn-filter, .btn-clear { width: 100%; text-align: center; }

                /* Header action buttons stack */
                .header-actions { flex-direction: column; width: 100%; }
                .header-actions a, .header-actions button { width: 100%; text-align: center; }

                /* Stat cards single column */
                .dash-grid { grid-template-columns: 1fr !important; }

                /* Form grids single column */
                .rpt-grid-2, .rpt-grid-3, .field-grid, .field-grid-2 { grid-template-columns: 1fr !important; }
            }

            @media (min-width: 641px) and (max-width: 900px) {
                .filter-bar { flex-wrap: wrap; }
                .filter-group { min-width: 45%; }
            }
        </style>
        <script>
            // Apply saved theme before page renders to avoid flash
            (function() {
                var t = localStorage.getItem('dpr-theme') || 'dark';
                document.documentElement.setAttribute('data-theme', t);
            })();
        </script>
    </head>
    <body>
        <!-- Top Navigation -->
        <nav class="app-nav">
            <div class="app-nav-inner">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="app-nav-brand">Daily Progress Report</a>
                @elseif(Auth::user()->role === 'viewer')
                    <a href="{{ route('viewer.dashboard') }}" class="app-nav-brand">Daily Progress Report</a>
                @else
                    <a href="{{ route('dashboard') }}" class="app-nav-brand">Daily Progress Report</a>
                @endif

                <div class="app-nav-links">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="app-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.reports.index') }}" class="app-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">Reports</a>
                        <a href="{{ route('admin.users.index') }}" class="app-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                    @elseif(Auth::user()->role === 'viewer')
                        <a href="{{ route('viewer.dashboard') }}" class="app-nav-link {{ request()->routeIs('viewer.dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('viewer.reports.index') }}" class="app-nav-link {{ request()->routeIs('viewer.reports.*') ? 'active' : '' }}">View Reports</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="app-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('reports.create') }}" class="app-nav-link {{ request()->routeIs('reports.create') ? 'active' : '' }}">Create Report</a>
                        <a href="{{ route('reports.index') }}" class="app-nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">My Reports</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="app-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">Profile</a>
                </div>

                <div class="app-nav-right">
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle light/dark mode">
                        <!-- Sun icon (shown in dark mode) -->
                        <svg class="icon-sun" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                        <!-- Moon icon (shown in light mode) -->
                        <svg class="icon-moon" viewBox="0 0 24 24">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>

                    <span class="app-nav-user">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="app-nav-logout">Sign Out</button>
                    </form>
                </div>

                <button class="nav-mobile-toggle" onclick="document.getElementById('mobileMenu').classList.toggle('open')">
                    <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="nav-mobile-menu">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.reports.index') }}">Reports</a>
                <a href="{{ route('admin.users.index') }}">Users</a>
            @elseif(Auth::user()->role === 'viewer')
                <a href="{{ route('viewer.dashboard') }}">Dashboard</a>
                <a href="{{ route('viewer.reports.index') }}">View Reports</a>
            @else
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('reports.create') }}">Create Report</a>
                <a href="{{ route('reports.index') }}">My Reports</a>
            @endif
            <a href="{{ route('profile.edit') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Sign Out</button>
            </form>
        </div>

        <!-- Page Heading -->
        @isset($header)
            <div class="page-header">
                <div class="page-header-inner">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Page Content -->
        <main class="app-main">
            {{ $slot }}
        </main>

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
