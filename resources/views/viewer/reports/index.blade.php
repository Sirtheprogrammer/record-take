<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">View Reports</h2>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="{{ route('viewer.reports.bulk-pdf', request()->query()) }}" style="display:inline-block; background-color:#c9a84c; color:#07111f; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px;">
                    ↓ Bulk PDF
                </a>
                <a href="{{ route('viewer.reports.csv', request()->query()) }}" style="display:inline-block; background-color:#112240; color:#c9a84c; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px; border:1px solid rgba(201,168,76,0.3);">
                    ↓ Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .filter-bar { background-color: #0c1c30; border: 1px solid rgba(201,168,76,0.15); border-radius: 8px; padding: 1.25rem; margin-bottom: 1.25rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; gap: 0.3rem; min-width: 160px; flex: 1; }
        .filter-label { font-size: 0.65rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7a99; }
        .filter-input, .filter-select { background-color: #07111f; border: 1px solid rgba(201,168,76,0.18); border-radius: 4px; color: #e8eaf0; font-family: 'Inter', sans-serif; font-size: 0.8rem; padding: 0.45rem 0.7rem; outline: none; }
        .filter-input:focus, .filter-select:focus { border-color: #c9a84c; }
        .filter-select option { background-color: #0c1c30; }
        .btn-filter { background-color: #c9a84c; color: #07111f; font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; border: none; border-radius: 3px; padding: 0.5rem 1.25rem; cursor: pointer; align-self: flex-end; }
        .btn-filter:hover { background-color: #e2c97e; }
        .btn-clear { font-size: 0.72rem; color: #6b7a99; text-decoration: none; align-self: flex-end; padding: 0.5rem 0.5rem; }
        .btn-clear:hover { color: #e8eaf0; }
        .table-wrap { background-color: #0c1c30; border: 1px solid rgba(201,168,76,0.18); border-radius: 8px; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead tr { background-color: #07111f; border-bottom: 1px solid rgba(201,168,76,0.15); }
        .data-table th { font-size: 0.68rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7a99; padding: 0.85rem 1.25rem; text-align: left; white-space: nowrap; }
        .data-table td { font-size: 0.82rem; color: #c8cfe0; padding: 0.85rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background-color: rgba(255,255,255,0.02); }
        .action-link { font-size: 0.75rem; font-weight: 500; text-decoration: none; color: #c9a84c; margin-right: 0.6rem; white-space: nowrap; }
        .action-link:hover { text-decoration: underline; }
        .table-empty { text-align: center; color: #4a5a72; font-size: 0.82rem; padding: 3rem 1rem; }
        .pagination-wrap { padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.04); }
        @media (max-width: 768px) {
            .data-table th:nth-child(3), .data-table td:nth-child(3),
            .data-table th:nth-child(4), .data-table td:nth-child(4) { display: none; }
            .filter-group { min-width: 100%; }
        }
    </style>

    <!-- Filters -->
    <form method="GET" action="{{ route('viewer.reports.index') }}">
        <div class="filter-bar">
            <div class="filter-group">
                <label class="filter-label">Supervisor</label>
                <select name="supervisor" class="filter-select">
                    <option value="">All Supervisors</option>
                    @foreach($supervisors as $sup)
                        <option value="{{ $sup->id }}" {{ request('supervisor') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Date From</label>
                <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
                <label class="filter-label">Date To</label>
                <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
            </div>
            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('viewer.reports.index') }}" class="btn-clear">Clear</a>
        </div>
    </form>

    <!-- Table -->
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Supervisor</th>
                    <th>Shift</th>
                    <th>Site Status</th>
                    <th>Employees</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->report_date->format('d M Y') }}</td>
                        <td>{{ $report->user->name }}</td>
                        <td style="text-transform:capitalize;">{{ $report->shift }}</td>
                        <td style="color:#6b7a99; text-transform:capitalize;">{{ str_replace('_', ' ', $report->site_status) }}</td>
                        <td style="color:#6b7a99;">{{ $report->employeeRecords->count() }}</td>
                        <td style="text-align:right; white-space:nowrap;">
                            <a href="{{ route('viewer.reports.show', $report) }}" class="action-link">View</a>
                            <a href="{{ route('viewer.reports.pdf', $report) }}" class="action-link">PDF</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="table-empty">No submitted reports found.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reports->hasPages())
            <div class="pagination-wrap">{{ $reports->links() }}</div>
        @endif
    </div>

</x-app-layout>
