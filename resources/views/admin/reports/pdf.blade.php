<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Report — {{ $report->report_date->format('d M Y') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a2e; background: #fff; }
        .page { padding: 30px 35px; }

        /* Header */
        .doc-header { border-bottom: 2px solid #0f2744; padding-bottom: 12px; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: flex-end; }
        .doc-title { font-size: 16px; font-weight: bold; color: #0f2744; }
        .doc-subtitle { font-size: 9px; color: #6b7a99; margin-top: 3px; }
        .doc-meta { text-align: right; font-size: 9px; color: #6b7a99; }
        .doc-meta strong { color: #0f2744; }

        /* Status badge */
        .status-submitted { background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 3px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .status-draft { background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 3px; font-size: 8px; font-weight: bold; text-transform: uppercase; }

        /* Section */
        .section { margin-bottom: 14px; border: 1px solid #e2e8f0; border-radius: 5px; overflow: hidden; }
        .section-header { background: #0f2744; color: #c9a84c; padding: 6px 12px; font-size: 9px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; display: flex; align-items: center; gap: 8px; }
        .section-letter { background: rgba(201,168,76,0.2); padding: 1px 5px; border-radius: 2px; }
        .section-body { padding: 10px 12px; }

        /* Fields */
        .field-row { display: flex; flex-wrap: wrap; gap: 0; margin-bottom: 6px; }
        .field { flex: 1; min-width: 120px; padding-right: 12px; margin-bottom: 6px; }
        .field-label { font-size: 7.5px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7a99; margin-bottom: 2px; }
        .field-value { font-size: 9.5px; color: #1a1a2e; line-height: 1.5; }
        .field-value.muted { color: #9ca3af; font-style: italic; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 2px; font-size: 8px; font-weight: bold; }
        .badge-yes { background: #d1fae5; color: #065f46; }
        .badge-no  { background: #fee2e2; color: #991b1b; }
        .badge-good { background: #d1fae5; color: #065f46; }
        .badge-minor { background: #fef3c7; color: #92400e; }
        .badge-critical { background: #fee2e2; color: #991b1b; }

        /* Employee table */
        .emp-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .emp-table th { background: #f1f5f9; font-size: 7.5px; font-weight: bold; letter-spacing: 0.06em; text-transform: uppercase; color: #6b7a99; padding: 5px 6px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .emp-table td { font-size: 8.5px; color: #374151; padding: 5px 6px; border-bottom: 1px solid #f1f5f9; }
        .emp-table tbody tr:last-child td { border-bottom: none; }
        .emp-empty { text-align: center; color: #9ca3af; font-style: italic; padding: 10px; font-size: 8.5px; }

        /* Footer */
        .doc-footer { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; display: flex; justify-content: space-between; font-size: 8px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="page">

    <!-- Header -->
    <div class="doc-header">
        <div>
            <div class="doc-title">Daily Progress Report</div>
            <div class="doc-subtitle">{{ $report->report_date->format('l, d F Y') }} &mdash; {{ ucfirst($report->shift) }} Shift</div>
        </div>
        <div class="doc-meta">
            <div><strong>Supervisor:</strong> {{ $report->user->name }}</div>
            @if($report->site_location)
                <div><strong>Site:</strong> {{ $report->site_location }}</div>
            @endif
            <div style="margin-top:4px;">
                <span class="status-{{ $report->status }}">{{ $report->status }}</span>
            </div>
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
                    <div class="field"><div class="field-label">Site Location</div><div class="field-value">{{ $report->site_location }}</div></div>
                @endif
                @if($report->submitted_at)
                    <div class="field"><div class="field-label">Submitted At</div><div class="field-value">{{ $report->submitted_at->format('d M Y, H:i') }}</div></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section B -->
    <div class="section">
        <div class="section-header"><span class="section-letter">B</span> Safety &amp; Attendance</div>
        <div class="section-body">
            <div class="field-row">
                <div class="field"><div class="field-label">Total Personnel</div><div class="field-value">{{ $report->total_personnel }}</div></div>
                <div class="field"><div class="field-label">Toolbox Meeting</div><div class="field-value"><span class="badge {{ $report->toolbox_meeting ? 'badge-yes' : 'badge-no' }}">{{ $report->toolbox_meeting ? 'Yes' : 'No' }}</span></div></div>
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
                        <span class="badge {{ $cls }}">{{ str_replace('_', ' ', $ms) }}</span>
                    </div>
                </div>
                <div class="field"><div class="field-label">Fuel Level</div><div class="field-value">{{ $report->fuel_level !== null ? $report->fuel_level.'%' : '—' }}</div></div>
                <div class="field"><div class="field-label">Maintenance Required</div><div class="field-value"><span class="badge {{ $report->maintenance_required ? 'badge-no' : 'badge-yes' }}">{{ $report->maintenance_required ? 'Yes' : 'No' }}</span></div></div>
            </div>
            @if($report->breakdowns)
                <div class="field" style="margin-top:4px;"><div class="field-label">Breakdowns</div><div class="field-value">{{ $report->breakdowns }}</div></div>
            @endif
        </div>
    </div>

    <!-- Section E -->
    <div class="section">
        <div class="section-header"><span class="section-letter">E</span> Challenges &amp; Delays</div>
        <div class="section-body">
            <div class="field-value {{ !$report->challenges ? 'muted' : '' }}">{{ $report->challenges ?: 'No challenges reported.' }}</div>
        </div>
    </div>

    <!-- Section F -->
    <div class="section">
        <div class="section-header"><span class="section-letter">F</span> Plan for Tomorrow</div>
        <div class="section-body">
            <div class="field-value {{ !$report->plan_for_tomorrow ? 'muted' : '' }}">{{ $report->plan_for_tomorrow ?: 'No plan recorded.' }}</div>
        </div>
    </div>

    <!-- Section G -->
    <div class="section">
        <div class="section-header"><span class="section-letter">G</span> Employee Work Records ({{ $report->employeeRecords->count() }})</div>
        <div class="section-body" style="padding:0;">
            <table class="emp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Task</th>
                        <th>Role</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Hours</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report->employeeRecords as $i => $emp)
                        <tr>
                            <td>{{ $i + 1 }}</td>
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

    <!-- Footer -->
    <div class="doc-footer">
        <span>Daily Progress Report System &mdash; Internal Use Only</span>
        <span>Generated: {{ now()->format('d M Y, H:i') }}</span>
    </div>

</div>
</body>
</html>
