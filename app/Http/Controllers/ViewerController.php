<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewerController extends Controller
{
    /** Viewer dashboard. */
    public function dashboard(): View
    {
        $totalReports = DailyReport::where('status', 'submitted')->count();
        $recentReports = DailyReport::query()
            ->with('user')
            ->where('status', 'submitted')
            ->latest('report_date')
            ->limit(5)
            ->get();

        return view('viewer.dashboard', compact('totalReports', 'recentReports'));
    }

    /** List all submitted reports (read-only). */
    public function index(Request $request): View
    {
        $query = DailyReport::query()
            ->with(['user', 'employeeRecords'])
            ->where('status', 'submitted')
            ->latest('report_date');

        if ($request->filled('supervisor')) {
            $query->where('user_id', $request->supervisor);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        $reports = $query->paginate(20)->withQueryString();
        $supervisors = User::whereIn('role', ['supervisor', 'admin'])->orderBy('name')->get();

        return view('viewer.reports.index', compact('reports', 'supervisors'));
    }

    /** View a single submitted report. */
    public function show(DailyReport $report): View
    {
        if ($report->status !== 'submitted') {
            abort(403, 'Only submitted reports are accessible.');
        }

        $report->load(['user', 'employeeRecords']);

        return view('viewer.reports.show', compact('report'));
    }

    /** Export a single report as PDF. */
    public function exportPdf(DailyReport $report): Response
    {
        if ($report->status !== 'submitted') {
            abort(403);
        }

        $report->load(['user', 'employeeRecords']);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('report'))
            ->setPaper('a4', 'portrait');

        $filename = 'report-'.$report->report_date->format('Y-m-d').'-'.$report->user->name.'.pdf';
        $filename = str_replace(' ', '-', strtolower($filename));

        return $pdf->download($filename);
    }

    /** Export filtered submitted reports as bulk PDF. */
    public function exportBulkPdf(Request $request): Response
    {
        $reports = $this->buildFilteredQuery($request)->get();

        $pdf = Pdf::loadView('admin.reports.pdf-bulk', compact('reports'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reports-bulk-'.now()->format('Y-m-d').'.pdf');
    }

    /** Export filtered submitted reports as CSV. */
    public function exportCsv(Request $request): StreamedResponse
    {
        $reports = $this->buildFilteredQuery($request)->get();

        return response()->streamDownload(function () use ($reports): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date', 'Shift', 'Supervisor', 'Site Location',
                'Total Personnel', 'Toolbox Meeting',
                'Machines Used', 'Work Done', 'Total Working Hours', 'Site Status',
                'Machine Status', 'Fuel Level (%)', 'Maintenance Required',
                'Challenges', 'Plan for Tomorrow',
                'Submitted At', 'Employee Count',
            ]);

            foreach ($reports as $report) {
                fputcsv($handle, [
                    $report->report_date->format('Y-m-d'),
                    ucfirst($report->shift),
                    $report->user->name,
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
                    $report->submitted_at?->format('Y-m-d H:i') ?? '',
                    $report->employeeRecords->count(),
                ]);
            }

            fclose($handle);
        }, 'reports-export-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /** Build a filtered query for submitted reports. */
    private function buildFilteredQuery(Request $request)
    {
        $query = DailyReport::query()
            ->with(['user', 'employeeRecords'])
            ->where('status', 'submitted')
            ->latest('report_date');

        if ($request->filled('supervisor')) {
            $query->where('user_id', $request->supervisor);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('report_date', '<=', $request->date_to);
        }

        return $query;
    }
}
