<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports Export — {{ now()->format('d M Y') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a2e; background: #fff; }

        /* Cover page */
        .cover { padding: 60px 40px; text-align: center; page-break-after: always; }
        .cover-logo { font-size: 22px; font-weight: bold; color: #0f2744; margin-bottom: 8px; }
        .cover-sub { font-size: 11px; color: #6b7a99; margin-bottom: 40px; }
        .cover-title { font-size: 18px; font-weight: bold; color: #0f2744; margin-bottom: 6px; }
        .cover-date { font-size: 10px; color: #6b7a99; margin-bottom: 40px; }
        .cover-divider { border: none; border-top: 2px solid #c9a84c; width: 80px; margin: 20px auto; }
        .cover-meta { font-size: 9px; color: #6b7a99; line-height: 1.8; }
        .cover-meta strong { color: #0f2744; }

        /* TOC */
        .toc { padding: 30px 40px; page-break-after: always; }
        .toc-title { font-size: 13px; font-weight: bold; color: #0f2744; border-bottom: 1px solid #c9a84c; padding-bottom: 6px; margin-bottom: 14px; }
        .toc-row { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px dotted #e2e8f0; font-size: 9px; }
        .toc-row:last-child { border-bottom: none; }
        .toc-num { color: #6b7a99; width: 20px; flex-shrink: 0; }
        .toc-name { flex: 1; color: #1a1a2e; }
        .toc-date { color: #6b7a99; width: 80px; text-align: right; }
        .toc-status { width: 60px; text-align: right; }
        .badge { display: inline-block; padding: 1px 5px; border-radius: 2px; font-size: 7.5px; font-weight: bold; }
        .badge-submitted { background: #d1fae5; color: #065f46; }
        .badge-draft { background: #fef3c7; color: #92400e; }

        /* Report page */
        .report-page { padding: 28px 35px; page-break-before: always; }
        .report-header { border-bottom: 2px solid #0f2744; padding-bottom: 10px; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: flex-end; }
        .report-num { font-size: 8px; color: #c9a84c; font-weight: bold; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 3px; }
        .report-title { font-size: 14px; font-weight: bold; color: #0f2744; }
        .report-subtitle { font-size: 8.5px; color: #6b7a99; margin-top: 2px; }
        .report-meta { text-align: right; font-size: 8.5px; color: #6b7a99; }
        .report-meta strong { color: #0f2744; }

        /* Section */
        .section { margin-bottom: 10px; border: 1px solid #e2e8f0; border-radius: 4px; overflow: hidden; }
        .section-header { background: #0f2744; color: #c9a84c; padding: 5px 10px; font-size: 8px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; display: flex; align-items: center; gap: 6px; }
        .section-letter { background: rgba(201,168,76,0.2); padding: 1px 4px; border-radius: 2px; }
        .section-body { padding: 8px 10px; }

        /* Fields */
        .field-row { display: flex; flex-wrap: wrap; }
        .field { flex: 1; min-width: 100px; padding-right: 10px; margin-bottom: 5px; }
        .field-label { font-size: 7px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7a99; margin-bottom: 2px; }
        .field-value { font-size: 9px; color: #1a1a2e; line-height: 1.5; }
        .field-value.muted { color: #9ca3af; font-style: italic; }
        .badge-yes { background: #d1fae5; color: #065f46; padding: 1px 5px; border-radius: 2px; font-size: 7.5px; font-weight: bold; }
        .badge-no  { background: #fee2e2; color: #991b1b; padding: 1px 5px; border-radius: 2px; font-size: 7.5px; font-weight: bold; }
        .badge-good { background: #d1fae5; color: #065f46; padding: 1px 5px; border-radius: 2px; font-size: 7.5px; font-weight: bold; }
        .badge-minor { background: #fef3c7; color: #92400e; padding: 1px 5px; border-radius: 2px; font-size: 7.5px; font-weight: bold; }
        .badge-critical { background: #fee2e2; color: #991b1b; padding: 1px 5px; border-radius: 2px; font-size: 7.5px; font-weight: bold; }

        /* Employee table */
        .emp-table { width: 100%; border-collapse: collapse; }
        .emp-table th { background: #f1f5f9; font-size: 7px; font-weight: bold; letter-spacing: 0.06em; text-transform: uppercase; color: #6b7a99; padding: 4px 5px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .emp-table td { font-size: 8px; color: #374151; padding: 4px 5px; border-bottom: 1px solid #f1f5f9; }
        .emp-table tbody tr:last-child td { border-bottom: none; }
        .emp-empty { text-align: center; color: #9ca3af; font-style: italic; padding: 8px; font-size: 8px; }

        /* Page footer */
        .page-footer { margin-top: 14px; border-top: 1px solid #e2e8f0; padding-top: 6px; display: flex; justify-content: space-between; font-size: 7.5px; color: #9ca3af; }
    </style>
</head>
<body>

    <!-- Cover Page -->
    <div class="cover">
        <div class="cover-logo">Daily Progress Report</div>
        <div class="cover-sub">Internal Use Only</div>
        <hr class="cover-divider">
        <div class="cover-title">Bulk Reports Export</div>
        <div class="cover-date">Generated: {{ now()->format('l, d F Y \a\t H:i') }}</div>
        <hr class="cover-divider">
        <div class="cover-meta">
            <div><strong>Total Reports:</strong> {{ $reports->count() }}</div>
            <div><strong>Submitted:</strong> {{ $reports->where('status', 'submitted')->count() }}</div>
            <div><strong>Drafts:</strong> {{ $reports->where('status', 'draft')->count() }}</div>
            @if($reports->count() > 0)
                <div style="margin-top:8px;">
                    <strong>Date Range:</strong>
                    {{ $reports->min('report_date') ? \Carbon\Carbon::parse($reports->min('report_date'))->format('d M Y') : '—' }}
                    &mdash;
                    {{ $reports->max('report_date') ? \Carbon\Carbon::parse($reports->max('report_date'))->format('d M Y') : '—' }}
                </div>
            @endif
        </div>
    </div>

    <!-- Table of Contents -->
    @if($reports->count() > 0)
        <div class="toc">
            <div class="toc-title">Table of Contents</div>
            @foreach($reports as $i => $report)
                <div class="toc-row">
                    <span class="toc-num">{{ $i + 1 }}.</span>
                    <span class="toc-name">{{ $report->user->name }} — {{ ucfirst($report->shift) }} Shift</span>
                    <span class="toc-date">{{ $report->report_date->format('d M Y') }}</span>
                    <span class="toc-status"><span class="badge badge-{{ $report->status }}">{{ $report->status }}</span></span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Individual Reports -->
    @foreach($reports as $i => $report)
        <div class="report-page">

            <!-- Report Header -->
            <div class="report-header">
                <div>
                    <div class="report-num">Report {{ $i + 1 }} of {{ $reports->count() }}</div>
                    <div class="report-title">{{ $report->report_date->format('d F Y') }} — {{ ucfirst($report->shift) }} Shift</div>
                    <div class="report-subtitle">{{ $report->user->name }}{{ $report->site_location ? ' &mdash; '.$report->site_location : '' }}</div>
                </div>
                <div class="report-meta">
                    <div><span class="badge badge-{{ $report->status }}">{{ $report->status }}</span></div>
                    @if($report->submitted_at)
                        <div style="margin-top:3px;"><strong>Submitted:</strong> {{ $report->submitted_at->format('d M Y, H:i') }}</div>
                    @endif
                </div>
            </div>

            <!-- Section A -->
            <div class="section">
                <div class="section-header"><span class="section-letter">A</span> General Information</div>
                <div class="section-body">
                    <div class="field-row">
                        <div class="field"><div class="field-label">Date</div><div class="field-value">{{ $report->report_date->format('d M Y') }}</div></div>
                        <div class="field"><div class="field-label">Shift</div><div class="field-value" style="text-transform:capitalize;">{{ $report->shift }}</div></div>
                        <div class="field"><div class="field-label">Supervisor</div><div class="field-value">{{ $report->user->name }}</div></div>
                        @if($report->site_location)
                            <div class="field"><div class="field-label">Site</div><div class="field-value">{{ $report->site_location }}</div></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section B -->
            <div class="section">
                <div class="section-header"><span class="section-letter">B</span> Safety &amp; Attendance</div>
                <div class="section-body">
                    <div class="field-row">
                        <div class="field"><div class="field-label">Personnel</div><div class="field-value">{{ $report->total_personnel }}</div></div>
                        <div class="field"><div class="field-label">Toolbox Meeting</div><div class="field-value"><span class="{{ $report->toolbox_meeting ? 'badge-yes' : 'badge-no' }}">{{ $report->toolbox_meeting ? 'Yes' : 'No' }}</span></div></div>
                    </div>
                    @if($report->incidents)
                        <div class="field"><div class="field-label">Incidents</div><div class="field-value">{{ $report->incidents }}</div></div>
                    @endif
                    @if($report->toolbox_notes)
                        <div class="field" style="margin-top:4px;"><div class="field-label">Toolbox Notes</div><div class="field-value">{{ $report->toolbox_notes }}</div></div>
                    @endif
                </div>
            </div>

            <!-- Section C -->
            <div class="section">
                <div class="section-header"><span class="section-letter">C</span> Production Summary</div>
                <div class="section-body">
                    <div class="field-row">
                        <div class="field"><div class="field-label">Working Hours</div><div class="field-value">{{ $report->total_working_hours ?? '—' }}</div></div>
                        <div class="field"><div class="field-label">Site Status</div><div class="field-value" style="text-transform:capitalize;">{{ str_replace('_', ' ', $report->site_status) }}</div></div>
                    </div>
                    @if($report->machines_used)
                        <div class="field"><div class="field-label">Machines Used</div><div class="field-value">{{ $report->machines_used }}</div></div>
                    @endif
                    @if($report->work_done)
                        <div class="field" style="margin-top:4px;"><div class="field-label">Work Done</div><div class="field-value">{{ $report->work_done }}</div></div>
                    @endif
                </div>
            </div>

            <!-- Section D -->
            <div class="section">
                <div class="section-header"><span class="section-letter">D</span> Equipment Condition</div>
                <div class="section-body">
                    <div class="field-row">
                        <div class="field">
                            <div class="field-label">Machine Status</div>
                            <div class="field-value">
                                @php $ms = $report->machine_status; $cls = $ms === 'good' ? 'badge-good' : ($ms === 'minor_issue' ? 'badge-minor' : 'badge-critical'); @endphp
                                <span class="{{ $cls }}">{{ str_replace('_', ' ', $ms) }}</span>
                            </div>
                        </div>
                        <div class="field"><div class="field-label">Fuel Level</div><div class="field-value">{{ $report->fuel_level !== null ? $report->fuel_level.'%' : '—' }}</div></div>
                        <div class="field"><div class="field-label">Maintenance</div><div class="field-value"><span class="{{ $report->maintenance_required ? 'badge-no' : 'badge-yes' }}">{{ $report->maintenance_required ? 'Required' : 'Not Required' }}</span></div></div>
                    </div>
                    @if($report->breakdowns)
                        <div class="field" style="margin-top:4px;"><div class="field-label">Breakdowns</div><div class="field-value">{{ $report->breakdowns }}</div></div>
                    @endif
                </div>
            </div>

            <!-- Sections E & F side by side -->
            <div style="display:flex; gap:8px; margin-bottom:10px;">
                <div class="section" style="flex:1; margin-bottom:0;">
                    <div class="section-header"><span class="section-letter">E</span> Challenges &amp; Delays</div>
                    <div class="section-body">
                        <div class="field-value {{ !$report->challenges ? 'muted' : '' }}">{{ $report->challenges ?: 'None reported.' }}</div>
                    </div>
                </div>
                <div class="section" style="flex:1; margin-bottom:0;">
                    <div class="section-header"><span class="section-letter">F</span> Plan for Tomorrow</div>
                    <div class="section-body">
                        <div class="field-value {{ !$report->plan_for_tomorrow ? 'muted' : '' }}">{{ $report->plan_for_tomorrow ?: 'Not recorded.' }}</div>
                    </div>
                </div>
            </div>

            <!-- Section G -->
            <div class="section">
                <div class="section-header"><span class="section-letter">G</span> Employee Work Records ({{ $report->employeeRecords->count() }})</div>
                <div class="section-body" style="padding:0;">
                    <table class="emp-table">
                        <thead>
                            <tr>
                                <th>#</th><th>Name</th><th>Dept.</th><th>Task</th><th>Role</th><th>Start</th><th>End</th><th>Hrs</th><th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report->employeeRecords as $j => $emp)
                                <tr>
                                    <td>{{ $j + 1 }}</td>
                                    <td>{{ $emp->employee_name }}</td>
                                    <td>{{ $emp->department ?? '—' }}</td>
                                    <td>{{ $emp->task_performed ?? '—' }}</td>
                                    <td>{{ $emp->role ?? '—' }}</td>
                                    <td>{{ $emp->start_time ?? '—' }}</td>
                                    <td>{{ $emp->end_time ?? '—' }}</td>
                                    <td>{{ $emp->total_hours ?? '—' }}</td>
                                    <td>{{ $emp->comments ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="emp-empty">No employee records.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Page Footer -->
            <div class="page-footer">
                <span>Daily Progress Report System &mdash; Internal Use Only</span>
                <span>Report {{ $i + 1 }} of {{ $reports->count() }} &mdash; {{ $report->report_date->format('d M Y') }}</span>
            </div>

        </div>
    @endforeach

</body>
</html>
