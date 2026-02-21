@php
    $isSubmitted = $report && $report->isSubmitted();
    $v = fn($field, $default = '') => old($field, $report?->{$field} ?? $default);
@endphp

<style>
    .rpt-form { max-width: 960px; }
    .rpt-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .btn-draft {
        background-color: #112240;
        color: #c9a84c;
        font-family: 'Inter', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border: 1px solid rgba(201,168,76,0.35);
        border-radius: 3px;
        padding: 0.55rem 1.4rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-draft:hover { background-color: #1a3050; }
    .btn-submit-report {
        background-color: #c9a84c;
        color: #07111f;
        font-family: 'Inter', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border: none;
        border-radius: 3px;
        padding: 0.55rem 1.4rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-submit-report:hover { background-color: #e2c97e; }
    .rpt-section {
        background-color: #0c1c30;
        border: 1px solid rgba(201,168,76,0.15);
        border-radius: 8px;
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .rpt-section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        cursor: pointer;
        user-select: none;
    }
    .rpt-section-header:hover { background-color: rgba(255,255,255,0.02); }
    .section-bar {
        width: 3px;
        height: 18px;
        background-color: #c9a84c;
        border-radius: 2px;
        flex-shrink: 0;
    }
    .section-letter {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #c9a84c;
        background-color: rgba(201,168,76,0.1);
        padding: 0.15rem 0.5rem;
        border-radius: 3px;
    }
    .section-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #e8eaf0;
        flex: 1;
    }
    .section-toggle {
        font-size: 0.7rem;
        color: #4a5a72;
        transition: transform 0.2s;
    }
    .section-toggle.open { transform: rotate(180deg); }
    .rpt-section-body {
        padding: 1.25rem;
    }
    .rpt-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .rpt-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }
    .form-group { margin-bottom: 1rem; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: #6b7a99;
        margin-bottom: 0.35rem;
    }
    .form-label .req { color: #f87171; margin-left: 2px; }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        background-color: #07111f;
        border: 1px solid rgba(201,168,76,0.18);
        border-radius: 4px;
        color: #e8eaf0;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        padding: 0.55rem 0.8rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #c9a84c;
        box-shadow: 0 0 0 2px rgba(201,168,76,0.08);
    }
    .form-input[readonly] { opacity: 0.6; cursor: not-allowed; }
    .form-select option { background-color: #0c1c30; }
    .form-textarea { resize: vertical; min-height: 80px; line-height: 1.6; }
    .form-error { font-size: 0.7rem; color: #f87171; margin-top: 0.3rem; }
    .toggle-group {
        display: flex;
        gap: 0.5rem;
    }
    .toggle-btn {
        flex: 1;
        padding: 0.5rem;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 4px;
        border: 1px solid rgba(201,168,76,0.2);
        background-color: #07111f;
        color: #6b7a99;
        cursor: pointer;
        text-align: center;
        transition: all 0.15s;
        font-family: 'Inter', sans-serif;
    }
    .toggle-btn.selected-yes { background-color: rgba(74,222,128,0.1); border-color: rgba(74,222,128,0.4); color: #4ade80; }
    .toggle-btn.selected-no  { background-color: rgba(248,113,113,0.1); border-color: rgba(248,113,113,0.4); color: #f87171; }

    /* Employee Table */
    .emp-table-wrap {
        overflow-x: auto;
        margin-top: 0.75rem;
    }
    .emp-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    .emp-table thead tr {
        background-color: #07111f;
        border-bottom: 1px solid rgba(201,168,76,0.15);
    }
    .emp-table th {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7a99;
        padding: 0.65rem 0.75rem;
        text-align: left;
        white-space: nowrap;
    }
    .emp-table td {
        padding: 0.5rem 0.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        vertical-align: middle;
    }
    .emp-table tbody tr:last-child td { border-bottom: none; }
    .emp-input {
        width: 100%;
        background-color: #07111f;
        border: 1px solid rgba(201,168,76,0.15);
        border-radius: 3px;
        color: #e8eaf0;
        font-family: 'Inter', sans-serif;
        font-size: 0.78rem;
        padding: 0.4rem 0.55rem;
        outline: none;
        min-width: 90px;
    }
    .emp-input:focus { border-color: #c9a84c; }
    .btn-add-emp {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background-color: #112240;
        color: #c9a84c;
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(201,168,76,0.3);
        border-radius: 3px;
        padding: 0.45rem 1rem;
        cursor: pointer;
        margin-top: 0.75rem;
        transition: background-color 0.15s;
    }
    .btn-add-emp:hover { background-color: #1a3050; }
    .btn-remove-row {
        background: none;
        border: none;
        color: #f87171;
        cursor: pointer;
        font-size: 1rem;
        padding: 0.2rem 0.4rem;
        line-height: 1;
    }
    .btn-remove-row:hover { color: #fca5a5; }
    .alert-success {
        background-color: rgba(74,222,128,0.08);
        border: 1px solid rgba(74,222,128,0.25);
        color: #4ade80;
        font-size: 0.8rem;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin-bottom: 1.25rem;
    }
    .alert-error {
        background-color: rgba(248,113,113,0.08);
        border: 1px solid rgba(248,113,113,0.25);
        color: #f87171;
        font-size: 0.8rem;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 640px) {
        .rpt-grid-2, .rpt-grid-3 { grid-template-columns: 1fr; }
        .rpt-actions { flex-direction: column; align-items: stretch; }
        .btn-draft, .btn-submit-report { text-align: center; }
    }
</style>

<div class="rpt-form">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ $action }}" id="reportForm">
        @csrf
        @if($method === 'PUT') @method('PUT') @endif

        <!-- Top Action Buttons -->
        <div class="rpt-actions">
            @if(!$isSubmitted)
                <button type="submit" name="_action" value="draft" class="btn-draft">Save Draft</button>
                <button type="submit" name="_action" value="submit" class="btn-submit-report">Submit Report</button>
            @else
                <span style="font-size:0.8rem; color:#4ade80;">✓ This report has been submitted and is read-only.</span>
            @endif
            <a href="{{ route('reports.index') }}" style="font-size:0.78rem; color:#6b7a99; text-decoration:none; margin-left:auto;">← Back to Reports</a>
        </div>

        <!-- ── SECTION A: GENERAL INFORMATION ── -->
        <div class="rpt-section">
            <div class="rpt-section-header" onclick="toggleSection('secA')">
                <div class="section-bar"></div>
                <span class="section-letter">A</span>
                <span class="section-title">General Information</span>
                <span class="section-toggle open" id="secA-icon">▼</span>
            </div>
            <div class="rpt-section-body" id="secA">
                <div class="rpt-grid-3">
                    <div class="form-group">
                        <label class="form-label">Date <span class="req">*</span></label>
                        <input type="date" name="report_date" class="form-input" value="{{ $v('report_date', date('Y-m-d')) }}" required {{ $isSubmitted ? 'readonly' : '' }}>
                        @error('report_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Shift <span class="req">*</span></label>
                        <select name="shift" class="form-select" required {{ $isSubmitted ? 'disabled' : '' }}>
                            <option value="morning" {{ $v('shift') === 'morning' ? 'selected' : '' }}>Morning</option>
                            <option value="night"   {{ $v('shift') === 'night'   ? 'selected' : '' }}>Night</option>
                        </select>
                        @error('shift')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Supervisor</label>
                        <input type="text" class="form-input" value="{{ Auth::user()->name }}" readonly>
                    </div>
                </div>
                <div class="form-group" style="margin-top:1rem;">
                    <label class="form-label">Site Location</label>
                    <input type="text" name="site_location" class="form-input" value="{{ $v('site_location') }}" placeholder="Optional" {{ $isSubmitted ? 'readonly' : '' }}>
                </div>
            </div>
        </div>

        <!-- ── SECTION B: SAFETY & ATTENDANCE ── -->
        <div class="rpt-section">
            <div class="rpt-section-header" onclick="toggleSection('secB')">
                <div class="section-bar"></div>
                <span class="section-letter">B</span>
                <span class="section-title">Safety &amp; Attendance</span>
                <span class="section-toggle open" id="secB-icon">▼</span>
            </div>
            <div class="rpt-section-body" id="secB">
                <div class="rpt-grid-2">
                    <div class="form-group">
                        <label class="form-label">Any Incidents Today?</label>
                        <textarea name="incidents" class="form-textarea" placeholder="Describe any incidents, or leave blank if none." {{ $isSubmitted ? 'readonly' : '' }}>{{ $v('incidents') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Personnel Present</label>
                        <input type="number" name="total_personnel" class="form-input" value="{{ $v('total_personnel', 0) }}" min="0" {{ $isSubmitted ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="rpt-grid-2" style="margin-top:1rem;">
                    <div class="form-group">
                        <label class="form-label">Toolbox Meeting Conducted?</label>
                        <div class="toggle-group">
                            <button type="button" class="toggle-btn {{ $v('toolbox_meeting') == '1' ? 'selected-yes' : '' }}" onclick="setToggle('toolbox_meeting', 1, this, 'yes')">Yes</button>
                            <button type="button" class="toggle-btn {{ $v('toolbox_meeting') == '0' && $v('toolbox_meeting') !== '' ? 'selected-no' : '' }}" onclick="setToggle('toolbox_meeting', 0, this, 'no')">No</button>
                        </div>
                        <input type="hidden" name="toolbox_meeting" id="toolbox_meeting" value="{{ $v('toolbox_meeting', 0) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Toolbox Notes</label>
                        <textarea name="toolbox_notes" class="form-textarea" placeholder="Optional notes from toolbox meeting." {{ $isSubmitted ? 'readonly' : '' }}>{{ $v('toolbox_notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── SECTION C: PRODUCTION SUMMARY ── -->
        <div class="rpt-section">
            <div class="rpt-section-header" onclick="toggleSection('secC')">
                <div class="section-bar"></div>
                <span class="section-letter">C</span>
                <span class="section-title">Production Summary</span>
                <span class="section-toggle open" id="secC-icon">▼</span>
            </div>
            <div class="rpt-section-body" id="secC">
                <div class="rpt-grid-2">
                    <div class="form-group">
                        <label class="form-label">Machines Used</label>
                        <textarea name="machines_used" class="form-textarea" placeholder="List machines used today." {{ $isSubmitted ? 'readonly' : '' }}>{{ $v('machines_used') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Work Done <span class="req">*</span></label>
                        <textarea name="work_done" class="form-textarea" placeholder="Describe work completed today." {{ $isSubmitted ? 'readonly' : '' }}>{{ $v('work_done') }}</textarea>
                    </div>
                </div>
                <div class="rpt-grid-2" style="margin-top:1rem;">
                    <div class="form-group">
                        <label class="form-label">Total Working Hours</label>
                        <input type="number" name="total_working_hours" class="form-input" value="{{ $v('total_working_hours') }}" min="0" max="24" step="0.5" {{ $isSubmitted ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site Status <span class="req">*</span></label>
                        <select name="site_status" class="form-select" required {{ $isSubmitted ? 'disabled' : '' }}>
                            <option value="ongoing"     {{ $v('site_status', 'ongoing') === 'ongoing'     ? 'selected' : '' }}>Ongoing</option>
                            <option value="on_schedule" {{ $v('site_status') === 'on_schedule' ? 'selected' : '' }}>On Schedule</option>
                            <option value="delayed"     {{ $v('site_status') === 'delayed'     ? 'selected' : '' }}>Delayed</option>
                            <option value="completed"   {{ $v('site_status') === 'completed'   ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── SECTION D: EQUIPMENT CONDITION ── -->
        <div class="rpt-section">
            <div class="rpt-section-header" onclick="toggleSection('secD')">
                <div class="section-bar"></div>
                <span class="section-letter">D</span>
                <span class="section-title">Equipment Condition</span>
                <span class="section-toggle open" id="secD-icon">▼</span>
            </div>
            <div class="rpt-section-body" id="secD">
                <div class="rpt-grid-3">
                    <div class="form-group">
                        <label class="form-label">Machine Status <span class="req">*</span></label>
                        <select name="machine_status" class="form-select" required {{ $isSubmitted ? 'disabled' : '' }}>
                            <option value="good"         {{ $v('machine_status', 'good') === 'good'         ? 'selected' : '' }}>Good</option>
                            <option value="minor_issue"  {{ $v('machine_status') === 'minor_issue'  ? 'selected' : '' }}>Minor Issue</option>
                            <option value="critical"     {{ $v('machine_status') === 'critical'     ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fuel Level (%)</label>
                        <input type="number" name="fuel_level" class="form-input" value="{{ $v('fuel_level') }}" min="0" max="100" placeholder="0–100" {{ $isSubmitted ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Maintenance Required?</label>
                        <div class="toggle-group">
                            <button type="button" class="toggle-btn {{ $v('maintenance_required') == '1' ? 'selected-yes' : '' }}" onclick="setToggle('maintenance_required', 1, this, 'yes')">Yes</button>
                            <button type="button" class="toggle-btn {{ $v('maintenance_required') == '0' && $v('maintenance_required') !== '' ? 'selected-no' : '' }}" onclick="setToggle('maintenance_required', 0, this, 'no')">No</button>
                        </div>
                        <input type="hidden" name="maintenance_required" id="maintenance_required" value="{{ $v('maintenance_required', 0) }}">
                    </div>
                </div>
                <div class="form-group" style="margin-top:1rem;">
                    <label class="form-label">Breakdowns / Issues</label>
                    <textarea name="breakdowns" class="form-textarea" placeholder="Describe any breakdowns or equipment issues." {{ $isSubmitted ? 'readonly' : '' }}>{{ $v('breakdowns') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ── SECTION E: CHALLENGES / DELAYS ── -->
        <div class="rpt-section">
            <div class="rpt-section-header" onclick="toggleSection('secE')">
                <div class="section-bar"></div>
                <span class="section-letter">E</span>
                <span class="section-title">Challenges &amp; Delays</span>
                <span class="section-toggle open" id="secE-icon">▼</span>
            </div>
            <div class="rpt-section-body" id="secE">
                <div class="form-group">
                    <label class="form-label">Challenges Encountered</label>
                    <textarea name="challenges" class="form-textarea" style="min-height:100px;" placeholder="Describe weather issues, technical problems, or any other challenges encountered today." {{ $isSubmitted ? 'readonly' : '' }}>{{ $v('challenges') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ── SECTION F: PLAN FOR TOMORROW ── -->
        <div class="rpt-section">
            <div class="rpt-section-header" onclick="toggleSection('secF')">
                <div class="section-bar"></div>
                <span class="section-letter">F</span>
                <span class="section-title">Plan for Tomorrow</span>
                <span class="section-toggle open" id="secF-icon">▼</span>
            </div>
            <div class="rpt-section-body" id="secF">
                <div class="form-group">
                    <label class="form-label">Next Task / Planned Activities</label>
                    <textarea name="plan_for_tomorrow" class="form-textarea" style="min-height:100px;" placeholder="Describe planned activities and tasks for the next working day." {{ $isSubmitted ? 'readonly' : '' }}>{{ $v('plan_for_tomorrow') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ── SECTION G: EMPLOYEE WORK RECORDS ── -->
        <div class="rpt-section">
            <div class="rpt-section-header" onclick="toggleSection('secG')">
                <div class="section-bar"></div>
                <span class="section-letter">G</span>
                <span class="section-title">Employee Work Records</span>
                <span class="section-toggle open" id="secG-icon">▼</span>
            </div>
            <div class="rpt-section-body" id="secG">
                <div class="emp-table-wrap">
                    <table class="emp-table" id="empTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name <span style="color:#f87171;">*</span></th>
                                <th>Department</th>
                                <th>Task Performed</th>
                                <th>Role</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Hours</th>
                                <th>Comments</th>
                                @if(!$isSubmitted)<th></th>@endif
                            </tr>
                        </thead>
                        <tbody id="empTableBody">
                            @php
                                $employees = $report?->employeeRecords ?? collect();
                            @endphp
                            @forelse($employees as $i => $emp)
                                <tr>
                                    <td style="color:#4a5a72; font-size:0.72rem;">{{ $i + 1 }}</td>
                                    <td><input type="text" name="employees[{{ $i }}][employee_name]" class="emp-input" value="{{ $emp->employee_name }}" {{ $isSubmitted ? 'readonly' : '' }}></td>
                                    <td><input type="text" name="employees[{{ $i }}][department]" class="emp-input" value="{{ $emp->department }}" {{ $isSubmitted ? 'readonly' : '' }}></td>
                                    <td><input type="text" name="employees[{{ $i }}][task_performed]" class="emp-input" value="{{ $emp->task_performed }}" {{ $isSubmitted ? 'readonly' : '' }}></td>
                                    <td><input type="text" name="employees[{{ $i }}][role]" class="emp-input" value="{{ $emp->role }}" {{ $isSubmitted ? 'readonly' : '' }}></td>
                                    <td><input type="time" name="employees[{{ $i }}][start_time]" class="emp-input" value="{{ $emp->start_time }}" {{ $isSubmitted ? 'readonly' : '' }}></td>
                                    <td><input type="time" name="employees[{{ $i }}][end_time]" class="emp-input" value="{{ $emp->end_time }}" {{ $isSubmitted ? 'readonly' : '' }}></td>
                                    <td><input type="number" name="employees[{{ $i }}][total_hours]" class="emp-input" value="{{ $emp->total_hours }}" min="0" max="24" step="0.5" style="min-width:60px;" {{ $isSubmitted ? 'readonly' : '' }}></td>
                                    <td><input type="text" name="employees[{{ $i }}][comments]" class="emp-input" value="{{ $emp->comments }}" {{ $isSubmitted ? 'readonly' : '' }}></td>
                                    @if(!$isSubmitted)<td><button type="button" class="btn-remove-row" onclick="removeRow(this)">✕</button></td>@endif
                                </tr>
                            @empty
                                {{-- empty, JS will add rows --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(!$isSubmitted)
                    <button type="button" class="btn-add-emp" onclick="addEmployeeRow()">+ Add Employee</button>
                @endif
            </div>
        </div>

        <!-- Bottom Action Buttons -->
        @if(!$isSubmitted)
            <div class="rpt-actions" style="margin-top:1.5rem; margin-bottom:0;">
                <button type="submit" name="_action" value="draft" class="btn-draft">Save Draft</button>
                <button type="submit" name="_action" value="submit" class="btn-submit-report">Submit Report</button>
            </div>
        @endif

    </form>
</div>

<script>
    let empRowIndex = {{ $report ? $report->employeeRecords->count() : 0 }};

    function toggleSection(id) {
        const body = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.classList.add('open');
        } else {
            body.style.display = 'none';
            icon.classList.remove('open');
        }
    }

    function setToggle(fieldId, value, btn, type) {
        document.getElementById(fieldId).value = value;
        const group = btn.closest('.toggle-group');
        group.querySelectorAll('.toggle-btn').forEach(b => {
            b.classList.remove('selected-yes', 'selected-no');
        });
        btn.classList.add(type === 'yes' ? 'selected-yes' : 'selected-no');
    }

    function addEmployeeRow() {
        const i = empRowIndex++;
        const tbody = document.getElementById('empTableBody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="color:#4a5a72; font-size:0.72rem;">${i + 1}</td>
            <td><input type="text" name="employees[${i}][employee_name]" class="emp-input" placeholder="Full name"></td>
            <td><input type="text" name="employees[${i}][department]" class="emp-input" placeholder="Dept."></td>
            <td><input type="text" name="employees[${i}][task_performed]" class="emp-input" placeholder="Task"></td>
            <td><input type="text" name="employees[${i}][role]" class="emp-input" placeholder="Role"></td>
            <td><input type="time" name="employees[${i}][start_time]" class="emp-input"></td>
            <td><input type="time" name="employees[${i}][end_time]" class="emp-input"></td>
            <td><input type="number" name="employees[${i}][total_hours]" class="emp-input" min="0" max="24" step="0.5" style="min-width:60px;" placeholder="0"></td>
            <td><input type="text" name="employees[${i}][comments]" class="emp-input" placeholder="Notes"></td>
            <td><button type="button" class="btn-remove-row" onclick="removeRow(this)">✕</button></td>
        `;
        tbody.appendChild(tr);
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
    }

    // Handle form submit action (draft vs submit)
    document.getElementById('reportForm').addEventListener('submit', function(e) {
        const action = e.submitter?.value;
        if (action === 'submit') {
            if (!confirm('Submit this report? Once submitted, it cannot be edited.')) {
                e.preventDefault();
            }
        }
    });
</script>
