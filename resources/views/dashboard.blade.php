<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">Dashboard</h2>
    </x-slot>

    <style>
        .dash-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background-color: #0c1c30; border: 1px solid rgba(201,168,76,0.18); border-radius: 8px; padding: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; }
        .stat-card-icon { width: 42px; height: 42px; background-color: rgba(201,168,76,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-card-icon svg { width: 20px; height: 20px; stroke: #c9a84c; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
        .stat-card-label { font-size: 0.72rem; font-weight: 500; letter-spacing: 0.06em; text-transform: uppercase; color: #6b7a99; margin-bottom: 0.35rem; }
        .stat-card-value { font-size: 2rem; font-weight: 700; color: #e8eaf0; line-height: 1; }
        .dash-section { background-color: #0c1c30; border: 1px solid rgba(201,168,76,0.18); border-radius: 8px; overflow: hidden; }
        .dash-section-header { padding: 1rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between; }
        .dash-section-header h3 { font-size: 0.85rem; font-weight: 600; color: #e8eaf0; }
        .dash-section-header a { font-size: 0.75rem; color: #c9a84c; text-decoration: none; }
        .dash-section-header a:hover { text-decoration: underline; }
        .dash-section-body { padding: 1.5rem; }
        @media (max-width: 640px) { .dash-grid { grid-template-columns: 1fr; } }
        @media (min-width: 641px) and (max-width: 900px) { .dash-grid { grid-template-columns: repeat(2, 1fr); } }
    </style>

    @php
        $totalReports = \App\Models\DailyReport::where('user_id', Auth::id())->count();
        $submittedReports = \App\Models\DailyReport::where('user_id', Auth::id())->where('status', 'submitted')->count();
        $draftReports = \App\Models\DailyReport::where('user_id', Auth::id())->where('status', 'draft')->count();
    @endphp

    <div class="dash-grid">
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <div class="stat-card-label">Total Reports</div>
                <div class="stat-card-value">{{ $totalReports }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <div class="stat-card-label">Submitted</div>
                <div class="stat-card-value">{{ $submittedReports }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <div class="stat-card-label">Drafts</div>
                <div class="stat-card-value">{{ $draftReports }}</div>
            </div>
        </div>
    </div>

    <div class="dash-section">
        <div class="dash-section-header">
            <h3>Quick Actions</h3>
        </div>
        <div class="dash-section-body" style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="{{ route('reports.create') }}" style="display:inline-block; background-color:#c9a84c; color:#07111f; font-size:0.78rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.55rem 1.25rem; border-radius:3px;">
                + Create Report
            </a>
            <a href="{{ route('reports.index') }}" style="display:inline-block; background-color:#112240; color:#c9a84c; font-size:0.78rem; font-weight:600; text-decoration:none; padding:0.55rem 1.25rem; border-radius:3px; border:1px solid rgba(201,168,76,0.3);">
                View My Reports
            </a>
        </div>
    </div>

</x-app-layout>
