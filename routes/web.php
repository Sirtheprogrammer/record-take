<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ViewerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Supervisor Routes (accessible by supervisors and admins)
Route::middleware(['auth', 'supervisor'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Reports — create, edit, submit
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportBulkPdf'])->name('reports.export.bulk-pdf');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::post('/reports/{report}/submit', [ReportController::class, 'submit'])->name('reports.submit');
    Route::get('/reports/{report}/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
});

// Viewer Routes (accessible by viewers, supervisors, and admins)
Route::middleware(['auth'])->prefix('viewer')->name('viewer.')->group(function () {
    Route::get('/dashboard', [ViewerController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [ViewerController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/csv', [ViewerController::class, 'exportCsv'])->name('reports.csv');
    Route::get('/reports/export/pdf', [ViewerController::class, 'exportBulkPdf'])->name('reports.bulk-pdf');
    Route::get('/reports/{report}', [ViewerController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/pdf', [ViewerController::class, 'exportPdf'])->name('reports.pdf');
});

// Admin Routes (accessible by admins only)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class);

    // Report management
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/csv', [AdminReportController::class, 'exportCsv'])->name('reports.csv');
    Route::get('/reports/export/pdf', [AdminReportController::class, 'exportBulkPdf'])->name('reports.bulk-pdf');
    Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::delete('/reports/{report}', [AdminReportController::class, 'destroy'])->name('reports.destroy');
    Route::get('/reports/{report}/pdf', [AdminReportController::class, 'exportPdf'])->name('reports.pdf');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
