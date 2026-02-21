<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalUsers = User::count();
        $totalSupervisors = User::where('role', 'supervisor')->count();
        $totalReports = DailyReport::count();

        return view('admin.dashboard', compact('totalUsers', 'totalSupervisors', 'totalReports'));
    }
}
