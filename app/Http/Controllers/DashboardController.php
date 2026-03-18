<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Patient;
use App\Models\Bill;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $patientCount = Patient::count();
        $monthlyIncome = Bill::where('status', 'paid')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total');
        $pendingBillsCount = Bill::where('status', 'unpaid')->count();

        return view('dashboard', compact('patientCount', 'monthlyIncome', 'pendingBillsCount'));
    }
}
