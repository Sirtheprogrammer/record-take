<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:0.95rem; font-weight:600; color:var(--txt);">All Reports</h2>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;" class="header-actions">
                <a href="{{ route('admin.reports.bulk-pdf', request()->query()) }}" style="display:inline-block; background-color:var(--gold); color:#07111f; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px;">
                    ↓ Bulk PDF
                </a>
                <a href="{{ route('admin.reports.csv', request()->query()) }}" style="display:inline-block; background-color:var(--bg-3); color:var(--gold); font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px; border:1px solid var(--border);">
                    ↓ Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .filter-bar { background-color: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem; margin-bottom: 1.25rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; gap: 0.3rem; min-width: 160px; flex: 1; }
        .filter-label { font-size: 0.65rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); }
        .filter-input, .filter-select { background-color: var(--input-bg); border: 1px solid var(--border); border-radius: 4px; color: var(--txt); font-family: 'Inter', sans-serif; font-size: 0.8rem; padding: 0.45rem 0.7rem; outline: none; }
        .filter-input:focus, .filter-select:focus { border-color: var(--gold); }
        .filter-select option { background-color: var(--card-bg); }
        .btn-filter { background-color: var(--gold); color: #07111f; font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; border: none; border-radius: 3px; padding: 0.5rem 1.25rem; cursor: pointer; align-self: flex-end; }
        .btn-filter:hover { background-color: var(--gold-lt); }
        .btn-clear { font-size: 0.72rem; color: var(--muted); text-decoration: none; align-self: flex-end; padding: 0.5rem 0.5rem; }
        .btn-clear:hover { color: var(--txt); }
        .table-wrap { background-color: var(--card-bg); border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; min-width: 600px; }
        .data-table thead tr { background-color: var(--table-head); border-bottom: 1px solid var(--border); }
        .data-table th { font-size: 0.68rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); padding: 0.85rem 1.25rem; text-align: left; white-space: nowrap; }
        .data-table td { font-size: 0.82rem; color: var(--txt-2); padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-2); vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background-color: var(--row-hover); }
        .status-badge { display: inline-block; font-size: 0.65rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; padding: 0.2rem 0.6rem; border-radius: 3px; }
        .status-draft     { background-color: rgba(201,168,76,0.12); color: var(--gold); }
        .status-submitted { background-color: rgba(74,222,128,0.1); color: #4ade80; }
        .action-link { font-size: 0.75rem; font-weight: 500; text-decoration: none; color: var(--gold); margin-right: 0.6rem; white-space: nowrap; }
        .action-link:hover { text-decoration: underline; }
        .action-delete { font-size: 0.75rem; font-weight: 500; color: #f87171; background: none; border: none; cursor: pointer; font-family: 'Inter', sans-serif; padding: 0; }
        [data-theme="light"] .action-delete { color: #dc2626; }
        .action-delete:hover { text-decoration: underline; }
        .table-empty { text-align: center; color: var(--muted-2); font-size: 0.82rem; padding: 3rem 1rem; }
        .pagination-wrap { padding: 1rem 1.25rem; border-top: 1px solid var(--border-2); }
        .alert-success { background-color: rgba(74,222,128,0.08); border: 1px solid rgba(74,222,128,0.25); color: #4ade80; font-size: 0.8rem; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.25rem; }
    </style>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.reports.index') }}">
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
                <label class="filter-label">Status</label>
                <select name="status" class="filter-select">
                    <option value="">All Statuses</option>
                    <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
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
            <a href="{{ route('admin.reports.index') }}" class="btn-clear">Clear</a>
        </div>
    </form>

    {{-- Desktop Table --}}
    <div class="table-wrap desktop-table-wrap">
        <div class="table-scroll-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Supervisor</th>
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
                            <td>{{ $report->user->name }}</td>
                            <td style="text-transform:capitalize;">{{ $report->shift }}</td>
                            <td style="color:var(--muted); text-transform:capitalize;">{{ str_replace('_', ' ', $report->site_status) }}</td>
                            <td style="color:var(--muted);">{{ $report->employeeRecords->count() }}</td>
                            <td><span class="status-badge status-{{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                            <td style="text-align:right; white-space:nowrap;">
                                <a href="{{ route('admin.reports.show', $report) }}" class="action-link">View</a>
                                <a href="{{ route('admin.reports.pdf', $report) }}" class="action-link">PDF</a>
                                <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="table-empty">No reports found matching the selected filters.</td></tr>
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
                    <span class="mobile-card-label">Supervisor</span>
                    <span class="mobile-card-value">{{ $report->user->name }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Shift</span>
                    <span class="mobile-card-value" style="text-transform:capitalize;">{{ $report->shift }}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Status</span>
                    <span class="mobile-card-value"><span class="status-badge status-{{ $report->status }}">{{ ucfirst($report->status) }}</span></span>
                </div>
                <div class="mobile-card-actions">
                    <a href="{{ route('admin.reports.show', $report) }}">View</a>
                    <a href="{{ route('admin.reports.pdf', $report) }}">PDF</a>
                    <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Delete this report?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="mobile-card" style="text-align:center; color:var(--muted-2);">No reports found.</div>
        @endforelse
        @if($reports->hasPages())
            <div style="margin-top:1rem;">{{ $reports->links() }}</div>
        @endif
    </div>

</x-app-layout>
