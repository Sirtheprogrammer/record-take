<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /** List all reports with optional filters. */
    public function index(Request $request): View
    {
        $query = DailyReport::query()
            ->with(['user', 'employeeRecords'])
            ->latest('report_date');

        if ($request->filled('supervisor')) {
            $query->where('user_id', $request->supervisor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        $reports = $query->paginate(20)->withQueryString();
        $supervisors = User::whereIn('role', ['supervisor', 'admin'])->orderBy('name')->get();

        return view('admin.reports.index', compact('reports', 'supervisors'));
    }

    /** Show a single report in full detail. */
    public function show(DailyReport $report): View
    {
        $report->load(['user', 'employeeRecords']);

        return view('admin.reports.show', compact('report'));
    }

    /** Delete a report. */
    public function destroy(DailyReport $report): RedirectResponse
    {
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report deleted successfully.');
    }

    /** Export multiple filtered reports as a single combined PDF. */
    public function exportBulkPdf(Request $request): Response
    {
        $query = DailyReport::query()
            ->with(['user', 'employeeRecords'])
            ->latest('report_date');

        if ($request->filled('supervisor')) {
            $query->where('user_id', $request->supervisor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        $reports = $query->get();

        $pdf = Pdf::loadView('admin.reports.pdf-bulk', compact('reports'))
            ->setPaper('a4', 'portrait');

        $filename = 'reports-bulk-'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }

    /** Export a single report as PDF. */
    public function exportPdf(DailyReport $report): Response
    {
        $report->load(['user', 'employeeRecords']);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('report'))
            ->setPaper('a4', 'portrait');

        $filename = 'report-'.$report->report_date->format('Y-m-d').'-'.$report->user->name.'.pdf';
        $filename = str_replace(' ', '-', strtolower($filename));

        return $pdf->download($filename);
    }

    /** Export filtered reports as CSV. */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = DailyReport::query()
            ->with(['user', 'employeeRecords'])
            ->latest('report_date');

        if ($request->filled('supervisor')) {
            $query->where('user_id', $request->supervisor);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        $reports = $query->get();

        $filename = 'reports-export-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($reports): void {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'Date', 'Shift', 'Supervisor', 'Site Location',
                'Total Personnel', 'Toolbox Meeting', 'Incidents',
                'Machines Used', 'Work Done', 'Total Working Hours', 'Site Status',
                'Machine Status', 'Fuel Level (%)', 'Maintenance Required', 'Breakdowns',
                'Challenges', 'Plan for Tomorrow',
                'Status', 'Submitted At',
                'Employee Count',
            ]);

            foreach ($reports as $report) {
                fputcsv($handle, [
                    $report->report_date->format('Y-m-d'),
                    ucfirst($report->shift),
                    $report->user->name,
                    $report->site_location ?? '',
                    $report->total_personnel,
                    $report->toolbox_meeting ? 'Yes' : 'No',
                    $report->incidents ?? '',
                    $report->machines_used ?? '',
                    $report->work_done ?? '',
                    $report->total_working_hours ?? '',
                    str_replace('_', ' ', ucfirst($report->site_status)),
                    str_replace('_', ' ', ucfirst($report->machine_status)),
                    $report->fuel_level ?? '',
                    $report->maintenance_required ? 'Yes' : 'No',
                    $report->breakdowns ?? '',
                    $report->challenges ?? '',
                    $report->plan_for_tomorrow ?? '',
                    ucfirst($report->status),
                    $report->submitted_at?->format('Y-m-d H:i') ?? '',
                    $report->employeeRecords->count(),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
