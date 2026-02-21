<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">Dashboard</h2>
    </x-slot>

    <style>
        .dash-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 2rem; }
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
        .recent-table { width: 100%; border-collapse: collapse; }
        .recent-table td { font-size: 0.8rem; color: #c8cfe0; padding: 0.75rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.04); }
        .recent-table tr:last-child td { border-bottom: none; }
        .recent-table tr:hover td { background-color: rgba(255,255,255,0.02); }
        .status-badge { display: inline-block; font-size: 0.65rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; padding: 0.2rem 0.6rem; border-radius: 3px; background-color: rgba(74,222,128,0.1); color: #4ade80; }
        @media (max-width: 640px) { .dash-grid { grid-template-columns: 1fr; } }
    </style>

    <div class="dash-grid">
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <div class="stat-card-label">Submitted Reports</div>
                <div class="stat-card-value">{{ $totalReports }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <div>
                <div class="stat-card-label">Access Level</div>
                <div style="font-size:1rem; font-weight:600; color:#c9a84c; margin-top:0.2rem;">Read Only</div>
            </div>
        </div>
    </div>

    <div class="dash-section">
        <div class="dash-section-header">
            <h3>Recent Reports</h3>
            <a href="{{ route('viewer.reports.index') }}">View All →</a>
        </div>
        <table class="recent-table">
            @forelse($recentReports as $report)
                <tr>
                    <td>{{ $report->report_date->format('d M Y') }}</td>
                    <td style="color:#6b7a99;">{{ $report->user->name }}</td>
                    <td style="text-transform:capitalize; color:#6b7a99;">{{ $report->shift }}</td>
                    <td><span class="status-badge">Submitted</span></td>
                    <td style="text-align:right;"><a href="{{ route('viewer.reports.show', $report) }}" style="font-size:0.75rem; color:#c9a84c; text-decoration:none;">View →</a></td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center; color:#4a5a72; padding:2rem;">No submitted reports yet.</td></tr>
            @endforelse
        </table>
    </div>

</x-app-layout>
