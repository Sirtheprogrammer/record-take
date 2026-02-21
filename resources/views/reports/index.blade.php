<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">My Reports</h2>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="{{ route('reports.create') }}" style="display:inline-block; background-color:#c9a84c; color:#07111f; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px;">
                    + New Report
                </a>
                <a href="{{ route('reports.export.bulk-pdf') }}" style="display:inline-block; background-color:#112240; color:#c9a84c; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px; border:1px solid rgba(201,168,76,0.3);">
                    ↓ Bulk PDF
                </a>
                <a href="{{ route('reports.export.csv') }}" style="display:inline-block; background-color:#112240; color:#c9a84c; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px; border:1px solid rgba(201,168,76,0.3);">
                    ↓ CSV
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .table-wrap {
            background-color: #0c1c30;
            border: 1px solid rgba(201,168,76,0.18);
            border-radius: 8px;
            overflow: hidden;
        }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead tr { background-color: #07111f; border-bottom: 1px solid rgba(201,168,76,0.15); }
        .data-table th { font-size: 0.68rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7a99; padding: 0.85rem 1.25rem; text-align: left; }
        .data-table td { font-size: 0.82rem; color: #c8cfe0; padding: 0.9rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.04); }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background-color: rgba(255,255,255,0.02); }
        .status-badge { display: inline-block; font-size: 0.65rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; padding: 0.2rem 0.6rem; border-radius: 3px; }
        .status-draft     { background-color: rgba(201,168,76,0.12); color: #c9a84c; }
        .status-submitted { background-color: rgba(74,222,128,0.1); color: #4ade80; }
        .action-link { font-size: 0.75rem; font-weight: 500; text-decoration: none; color: #c9a84c; margin-right: 0.75rem; }
        .action-link:hover { text-decoration: underline; }
        .table-empty { text-align: center; color: #4a5a72; font-size: 0.82rem; padding: 3rem 1rem; }
        .pagination-wrap { padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.04); }
        @media (max-width: 640px) {
            .data-table th:nth-child(3), .data-table td:nth-child(3),
            .data-table th:nth-child(4), .data-table td:nth-child(4) { display: none; }
        }
    </style>

    @if(session('success'))
        <div style="background-color:rgba(74,222,128,0.08); border:1px solid rgba(74,222,128,0.25); color:#4ade80; font-size:0.8rem; padding:0.75rem 1rem; border-radius:6px; margin-bottom:1.25rem;">{{ session('success') }}</div>
    @endif

    <div class="table-wrap">
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
                        <td style="text-transform:capitalize; color:#6b7a99;">{{ str_replace('_', ' ', $report->site_status) }}</td>
                        <td style="color:#6b7a99;">{{ $report->employeeRecords->count() }}</td>
                        <td><span class="status-badge status-{{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                        <td style="text-align:right;">
                            <a href="{{ route('reports.edit', $report) }}" class="action-link">{{ $report->isSubmitted() ? 'View' : 'Edit' }}</a>
                            <a href="{{ route('reports.pdf', $report) }}" class="action-link">PDF</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="table-empty">No reports yet. <a href="{{ route('reports.create') }}" style="color:#c9a84c;">Create your first report.</a></td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reports->hasPages())
            <div class="pagination-wrap">{{ $reports->links() }}</div>
        @endif
    </div>

</x-app-layout>
