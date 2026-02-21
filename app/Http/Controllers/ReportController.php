<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /** List the authenticated supervisor's reports. */
    public function index(): View
    {
        $reports = DailyReport::query()
            ->where('user_id', Auth::id())
            ->latest('report_date')
            ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    /** Show the create form. */
    public function create(): View
    {
        return view('reports.create');
    }

    /** Save as draft or submit immediately. */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateReport($request);
        $validated['user_id'] = Auth::id();

        $isSubmitting = $request->input('_action') === 'submit';
        $validated['status'] = $isSubmitting ? 'submitted' : 'draft';
        $validated['submitted_at'] = $isSubmitting ? now() : null;

        $report = DailyReport::create($validated);

        $this->syncEmployeeRecords($report, $request->input('employees', []));

        $message = $isSubmitting ? 'Report submitted successfully.' : 'Report saved as draft.';

        return redirect()->route('reports.edit', $report)->with('success', $message);
    }

    /** Show the edit form. */
    public function edit(DailyReport $report): View
    {
        $this->authorizeReport($report);

        $report->load('employeeRecords');

        return view('reports.edit', compact('report'));
    }

    /** Update draft (or submit from edit page). */
    public function update(Request $request, DailyReport $report): RedirectResponse
    {
        $this->authorizeReport($report);

        if ($report->isSubmitted()) {
            return back()->with('error', 'Submitted reports cannot be edited.');
        }

        $validated = $this->validateReport($request);

        $isSubmitting = $request->input('_action') === 'submit';
        if ($isSubmitting) {
            $validated['status'] = 'submitted';
            $validated['submitted_at'] = now();
        }

        $report->update($validated);

        $this->syncEmployeeRecords($report, $request->input('employees', []));

        $message = $isSubmitting ? 'Report submitted successfully.' : 'Report updated successfully.';

        return redirect()->route('reports.edit', $report)->with('success', $message);
    }

    /** Submit the report (draft → submitted). */
    public function submit(DailyReport $report): RedirectResponse
    {
        $this->authorizeReport($report);

        if ($report->isSubmitted()) {
            return back()->with('error', 'Report is already submitted.');
        }

        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('reports.index')
            ->with('success', 'Report submitted successfully.');
    }

    /** Validate the report form fields. */
    private function validateReport(Request $request): array
    {
        return $request->validate([
            'report_date' => ['required', 'date'],
            'shift' => ['required', 'in:morning,night'],
            'site_location' => ['nullable', 'string', 'max:255'],
            'incidents' => ['nullable', 'string'],
            'toolbox_meeting' => ['nullable', 'boolean'],
            'toolbox_notes' => ['nullable', 'string'],
            'total_personnel' => ['nullable', 'integer', 'min:0'],
            'machines_used' => ['nullable', 'string'],
            'total_working_hours' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'work_done' => ['nullable', 'string'],
            'site_status' => ['required', 'in:on_schedule,delayed,completed,ongoing'],
            'machine_status' => ['required', 'in:good,minor_issue,critical'],
            'breakdowns' => ['nullable', 'string'],
            'fuel_level' => ['nullable', 'integer', 'min:0', 'max:100'],
            'maintenance_required' => ['nullable', 'boolean'],
            'challenges' => ['nullable', 'string'],
            'plan_for_tomorrow' => ['nullable', 'string'],
        ]);
    }

    /** Sync employee records for a report. */
    private function syncEmployeeRecords(DailyReport $report, array $employees): void
    {
        $report->employeeRecords()->delete();

        foreach ($employees as $emp) {
            if (empty($emp['employee_name'])) {
                continue;
            }

            $report->employeeRecords()->create([
                'employee_name' => $emp['employee_name'] ?? null,
                'department' => $emp['department'] ?? null,
                'task_performed' => $emp['task_performed'] ?? null,
                'role' => $emp['role'] ?? null,
                'start_time' => $emp['start_time'] ?? null,
                'end_time' => $emp['end_time'] ?? null,
                'total_hours' => $emp['total_hours'] ?? null,
                'comments' => $emp['comments'] ?? null,
            ]);
        }
    }

    /** Export a single own report as PDF. */
    public function exportPdf(DailyReport $report): Response
    {
        $this->authorizeReport($report);
        $report->load(['user', 'employeeRecords']);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('report'))
            ->setPaper('a4', 'portrait');

        $filename = 'report-'.$report->report_date->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }

    /** Export all own reports as a bulk PDF. */
    public function exportBulkPdf(): Response
    {
        $reports = DailyReport::query()
            ->with(['user', 'employeeRecords'])
            ->where('user_id', Auth::id())
            ->latest('report_date')
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf-bulk', compact('reports'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('my-reports-bulk-'.now()->format('Y-m-d').'.pdf');
    }

    /** Export all own reports as CSV. */
    public function exportCsv(): StreamedResponse
    {
        $reports = DailyReport::query()
            ->with(['user', 'employeeRecords'])
            ->where('user_id', Auth::id())
            ->latest('report_date')
            ->get();

        return response()->streamDownload(function () use ($reports): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date', 'Shift', 'Site Location', 'Total Personnel', 'Toolbox Meeting',
                'Machines Used', 'Work Done', 'Total Working Hours', 'Site Status',
                'Machine Status', 'Fuel Level (%)', 'Maintenance Required',
                'Challenges', 'Plan for Tomorrow', 'Status', 'Submitted At', 'Employee Count',
            ]);

            foreach ($reports as $report) {
                fputcsv($handle, [
                    $report->report_date->format('Y-m-d'),
                    ucfirst($report->shift),
                    $report->site_location ?? '',
                    $report->total_personnel,
                    $report->toolbox_meeting ? 'Yes' : 'No',
                    $report->machines_used ?? '',
                    $report->work_done ?? '',
                    $report->total_working_hours ?? '',
                    str_replace('_', ' ', ucfirst($report->site_status)),
                    str_replace('_', ' ', ucfirst($report->machine_status)),
                    $report->fuel_level ?? '',
                    $report->maintenance_required ? 'Yes' : 'No',
                    $report->challenges ?? '',
                    $report->plan_for_tomorrow ?? '',
                    ucfirst($report->status),
                    $report->submitted_at?->format('Y-m-d H:i') ?? '',
                    $report->employeeRecords->count(),
                ]);
            }

            fclose($handle);
        }, 'my-reports-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /** Ensure the authenticated user owns the report. */
    private function authorizeReport(DailyReport $report): void
    {
        if ($report->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
    }
}
