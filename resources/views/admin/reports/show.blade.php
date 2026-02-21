<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">
                Report — {{ $report->report_date->format('d M Y') }}
                <span style="font-size:0.65rem; background:{{ $report->isSubmitted() ? 'rgba(74,222,128,0.12)' : 'rgba(201,168,76,0.12)' }}; color:{{ $report->isSubmitted() ? '#4ade80' : '#c9a84c' }}; border:1px solid {{ $report->isSubmitted() ? 'rgba(74,222,128,0.25)' : 'rgba(201,168,76,0.25)' }}; padding:0.15rem 0.6rem; border-radius:3px; margin-left:0.5rem; vertical-align:middle;">{{ strtoupper($report->status) }}</span>
            </h2>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="{{ route('admin.reports.pdf', $report) }}" style="display:inline-block; background-color:#c9a84c; color:#07111f; font-size:0.75rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px;">↓ PDF</a>
                <a href="{{ route('admin.reports.index') }}" style="display:inline-block; background-color:#112240; color:#c9a84c; font-size:0.75rem; font-weight:600; text-decoration:none; padding:0.45rem 1.1rem; border-radius:3px; border:1px solid rgba(201,168,76,0.3);">← Back</a>
            </div>
        </div>
    </x-slot>

    <style>
        .rpt-view { max-width: 960px; }
        .rpt-section { background-color: #0c1c30; border: 1px solid rgba(201,168,76,0.15); border-radius: 8px; margin-bottom: 1.25rem; overflow: hidden; }
        .rpt-section-header { display: flex; align-items: center; gap: 0.75rem; padding: 0.9rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .section-bar { width: 3px; height: 18px; background-color: #c9a84c; border-radius: 2px; flex-shrink: 0; }
        .section-letter { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; color: #c9a84c; background-color: rgba(201,168,76,0.1); padding: 0.15rem 0.5rem; border-radius: 3px; }
        .section-title { font-size: 0.875rem; font-weight: 600; color: #e8eaf0; }
        .rpt-section-body { padding: 1.25rem; }
        .field-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .field-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .field { }
        .field-label { font-size: 0.65rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7a99; margin-bottom: 0.3rem; }
        .field-value { font-size: 0.85rem; color: #e8eaf0; line-height: 1.6; }
        .field-value.muted { color: #4a5a72; font-style: italic; }
        .badge-yes { display: inline-block; font-size: 0.65rem; font-weight: 600; background-color: rgba(74,222,128,0.1); color: #4ade80; padding: 0.15rem 0.5rem; border-radius: 3px; }
        .badge-no  { display: inline-block; font-size: 0.65rem; font-weight: 600; background-color: rgba(248,113,113,0.1); color: #f87171; padding: 0.15rem 0.5rem; border-radius: 3px; }
        .badge-status { display: inline-block; font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 3px; text-transform: capitalize; }
        .badge-good { background-color: rgba(74,222,128,0.1); color: #4ade80; }
        .badge-minor { background-color: rgba(251,191,36,0.1); color: #fbbf24; }
        .badge-critical { background-color: rgba(248,113,113,0.1); color: #f87171; }
        /* Employee table */
        .emp-table-wrap { overflow-x: auto; }
        .emp-table { width: 100%; border-collapse: collapse; min-width: 700px; }
        .emp-table thead tr { background-color: #07111f; border-bottom: 1px solid rgba(201,168,76,0.15); }
        .emp-table th { font-size: 0.65rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7a99; padding: 0.65rem 0.85rem; text-align: left; white-space: nowrap; }
        .emp-table td { font-size: 0.8rem; color: #c8cfe0; padding: 0.7rem 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.04); }
        .emp-table tbody tr:last-child td { border-bottom: none; }
        .emp-table tbody tr:hover td { background-color: rgba(255,255,255,0.02); }
        .emp-empty { text-align: center; color: #4a5a72; font-size: 0.8rem; padding: 2rem; }
        @media (max-width: 640px) {
            .field-grid, .field-grid-2 { grid-template-columns: 1fr; }
        }
    </style>

    <div class="rpt-view">

        <!-- Section A -->
        <div class="rpt-section">
            <div class="rpt-section-header">
                <div class="section-bar"></div>
                <span class="section-letter">A</span>
                <span class="section-title">General Information</span>
            </div>
            <div class="rpt-section-body">
                <div class="field-grid">
                    <div class="field">
                        <div class="field-label">Date</div>
                        <div class="field-value">{{ $report->report_date->format('d M Y') }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Shift</div>
                        <div class="field-value" style="text-transform:capitalize;">{{ $report->shift }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Supervisor</div>
                        <div class="field-value">{{ $report->user->name }}</div>
                    </div>
                    @if($report->site_location)
                        <div class="field">
                            <div class="field-label">Site Location</div>
                            <div class="field-value">{{ $report->site_location }}</div>
                        </div>
                    @endif
                    @if($report->submitted_at)
                        <div class="field">
                            <div class="field-label">Submitted At</div>
                            <div class="field-value">{{ $report->submitted_at->format('d M Y, H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section B -->
        <div class="rpt-section">
            <div class="rpt-section-header">
                <div class="section-bar"></div>
                <span class="section-letter">B</span>
                <span class="section-title">Safety &amp; Attendance</span>
            </div>
            <div class="rpt-section-body">
                <div class="field-grid">
                    <div class="field">
                        <div class="field-label">Total Personnel</div>
                        <div class="field-value">{{ $report->total_personnel }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Toolbox Meeting</div>
                        <div class="field-value">
                            @if($report->toolbox_meeting)
                                <span class="badge-yes">Yes</span>
                            @else
                                <span class="badge-no">No</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($report->incidents)
                    <div class="field" style="margin-top:1rem;">
                        <div class="field-label">Incidents</div>
                        <div class="field-value">{{ $report->incidents }}</div>
                    </div>
                @endif
                @if($report->toolbox_notes)
                    <div class="field" style="margin-top:1rem;">
                        <div class="field-label">Toolbox Notes</div>
                        <div class="field-value">{{ $report->toolbox_notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section C -->
        <div class="rpt-section">
            <div class="rpt-section-header">
                <div class="section-bar"></div>
                <span class="section-letter">C</span>
                <span class="section-title">Production Summary</span>
            </div>
            <div class="rpt-section-body">
                <div class="field-grid">
                    <div class="field">
                        <div class="field-label">Total Working Hours</div>
                        <div class="field-value">{{ $report->total_working_hours ?? '—' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Site Status</div>
                        <div class="field-value" style="text-transform:capitalize;">{{ str_replace('_', ' ', $report->site_status) }}</div>
                    </div>
                </div>
                @if($report->machines_used)
                    <div class="field" style="margin-top:1rem;">
                        <div class="field-label">Machines Used</div>
                        <div class="field-value">{{ $report->machines_used }}</div>
                    </div>
                @endif
                @if($report->work_done)
                    <div class="field" style="margin-top:1rem;">
                        <div class="field-label">Work Done</div>
                        <div class="field-value">{{ $report->work_done }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section D -->
        <div class="rpt-section">
            <div class="rpt-section-header">
                <div class="section-bar"></div>
                <span class="section-letter">D</span>
                <span class="section-title">Equipment Condition</span>
            </div>
            <div class="rpt-section-body">
                <div class="field-grid">
                    <div class="field">
                        <div class="field-label">Machine Status</div>
                        <div class="field-value">
                            @php
                                $ms = $report->machine_status;
                                $cls = $ms === 'good' ? 'badge-good' : ($ms === 'minor_issue' ? 'badge-minor' : 'badge-critical');
                            @endphp
                            <span class="badge-status {{ $cls }}">{{ str_replace('_', ' ', $ms) }}</span>
                        </div>
                    </div>
                    <div class="field">
                        <div class="field-label">Fuel Level</div>
                        <div class="field-value">{{ $report->fuel_level !== null ? $report->fuel_level.'%' : '—' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Maintenance Required</div>
                        <div class="field-value">
                            @if($report->maintenance_required)
                                <span class="badge-no">Yes</span>
                            @else
                                <span class="badge-yes">No</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($report->breakdowns)
                    <div class="field" style="margin-top:1rem;">
                        <div class="field-label">Breakdowns / Issues</div>
                        <div class="field-value">{{ $report->breakdowns }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section E -->
        <div class="rpt-section">
            <div class="rpt-section-header">
                <div class="section-bar"></div>
                <span class="section-letter">E</span>
                <span class="section-title">Challenges &amp; Delays</span>
            </div>
            <div class="rpt-section-body">
                <div class="field-value {{ !$report->challenges ? 'muted' : '' }}">
                    {{ $report->challenges ?: 'No challenges reported.' }}
                </div>
            </div>
        </div>

        <!-- Section F -->
        <div class="rpt-section">
            <div class="rpt-section-header">
                <div class="section-bar"></div>
                <span class="section-letter">F</span>
                <span class="section-title">Plan for Tomorrow</span>
            </div>
            <div class="rpt-section-body">
                <div class="field-value {{ !$report->plan_for_tomorrow ? 'muted' : '' }}">
                    {{ $report->plan_for_tomorrow ?: 'No plan recorded.' }}
                </div>
            </div>
        </div>

        <!-- Section G -->
        <div class="rpt-section">
            <div class="rpt-section-header">
                <div class="section-bar"></div>
                <span class="section-letter">G</span>
                <span class="section-title">Employee Work Records ({{ $report->employeeRecords->count() }})</span>
            </div>
            <div class="rpt-section-body" style="padding:0;">
                <div class="emp-table-wrap">
                    <table class="emp-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
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
                                    <td style="color:#4a5a72;">{{ $i + 1 }}</td>
                                    <td>{{ $emp->employee_name }}</td>
                                    <td style="color:#6b7a99;">{{ $emp->department ?? '—' }}</td>
                                    <td>{{ $emp->task_performed ?? '—' }}</td>
                                    <td style="color:#6b7a99;">{{ $emp->role ?? '—' }}</td>
                                    <td>{{ $emp->start_time ?? '—' }}</td>
                                    <td>{{ $emp->end_time ?? '—' }}</td>
                                    <td>{{ $emp->total_hours ?? '—' }}</td>
                                    <td style="color:#6b7a99;">{{ $emp->comments ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="emp-empty">No employee records for this report.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
