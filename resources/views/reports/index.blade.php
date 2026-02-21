<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:0.95rem; font-weight:600; color:var(--txt);">My Reports</h2>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;" class="header-actions">
                <a href="{{ route('reports.create') }}" style="display:inline-block; background-color:var(--gold); color:#07111f; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px;">
                    + New Report
                </a>
                <a href="{{ route('reports.export.bulk-pdf') }}" style="display:inline-block; background-color:var(--bg-3); color:var(--gold); font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px; border:1px solid var(--border);">
                    ↓ Bulk PDF
                </a>
                <a href="{{ route('reports.export.csv') }}" style="display:inline-block; background-color:var(--bg-3); color:var(--gold); font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px; border:1px solid var(--border);">
                    ↓ CSV
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .table-wrap { background-color: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; min-width: 500px; }
        .data-table thead tr { background-color: var(--table-head); border-bottom: 1px solid var(--border); }
        .data-table th { font-size: 0.68rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); padding: 0.85rem 1.25rem; text-align: left; }
        .data-table td { font-size: 0.82rem; color: var(--txt-2); padding: 0.9rem 1.25rem; border-bottom: 1px solid var(--border-2); }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background-color: var(--row-hover); }
        .status-badge { display: inline-block; font-size: 0.65rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; padding: 0.2rem 0.6rem; border-radius: 3px; }
        .status-draft     { background-color: rgba(201,168,76,0.12); color: var(--gold); }
        .status-submitted { background-color: rgba(74,222,128,0.1); color: #4ade80; }
        .action-link { font-size: 0.75rem; font-weight: 500; text-decoration: none; color: var(--gold); margin-right: 0.6rem; }
        .action-link:hover { text-decoration: underline; }
        .table-empty { text-align: center; color: var(--muted-2); font-size: 0.82rem; padding: 3rem 1rem; }
        .pagination-wrap { padding: 1rem 1.25rem; border-top: 1px solid var(--border-2); }
        .alert-success { background-color: rgba(74,222,128,0.08); border: 1px solid rgba(74,222,128,0.25); color: #4ade80; font-size: 0.8rem; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Desktop Table --}}
    <div class="table-wrap desktop-table-wrap">
        <div class="table-scroll-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>Site Status</th>
                        <th>Employees</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->report_date->format('d M Y') }}</td>
                            <td style="text-transform:capitalize;">{{ $report->shift }}</td>
                            <td style="color:var(--muted); text-transform:capitalize;">{{ str_replace('_', ' ', $report->site_status) }}</td>
                            <td style="color:var(--muted);">{{ $report->employeeRecords->count() }}</td>
                            <td><span class="status-badge status-{{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                            <td style="text-align:right;">
                                <a href="{{ route('reports.edit', $report) }}" class="action-link">{{ $report->isSubmitted() ? 'View' : 'Edit' }}</a>
                                <a href="{{ route('reports.pdf', $report) }}" class="action-link">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="table-empty">No reports yet. <a href="{{ route('reports.create') }}" style="color:var(--gold);">Create your first report.</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="pagination-wrap">{{ $reports->links() }}</div>
        @endif
    </div>

    {{-- Mobile Card List --}}
    <div class="mobile-card-list">
        @forelse($reports as $report)
            <div class="mobile-card">
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Date</span>
                    <span class="mobile-card-value" style="font-weight:600;">{{ $report->report_date->format('d M Y') }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Shift</span>
                    <span class="mobile-card-value" style="text-transform:capitalize;">{{ $report->shift }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Site Status</span>
                    <span class="mobile-card-value" style="text-transform:capitalize; color:var(--muted);">{{ str_replace('_', ' ', $report->site_status) }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Status</span>
                    <span class="mobile-card-value"><span class="status-badge status-{{ $report->status }}">{{ ucfirst($report->status) }}</span></span>
                </div>
                <div class="mobile-card-actions">
                    <a href="{{ route('reports.edit', $report) }}">{{ $report->isSubmitted() ? 'View' : 'Edit' }}</a>
                    <a href="{{ route('reports.pdf', $report) }}">PDF</a>
                </div>
            </div>
        @empty
            <div class="mobile-card" style="text-align:center; color:var(--muted-2);">
                No reports yet. <a href="{{ route('reports.create') }}" style="color:var(--gold);">Create your first report.</a>
            </div>
        @endforelse
        @if($reports->hasPages())
            <div style="margin-top:1rem;">{{ $reports->links() }}</div>
        @endif
    </div>

</x-app-layout>
