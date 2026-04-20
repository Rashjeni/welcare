<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Patient;
use App\Models\Bill;
use App\Models\Expense;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $patientCount = Patient::count();
        $serviceCount = Service::count();
        $staffCount = User::count();
        
        // Total Income (Sum of all paid bills)
        $totalIncome = Bill::where('status', 'paid')->sum('total');
            
        // Total Expenses (Sum of all recorded expenses)
        $totalExpenses = Expense::sum('amount');

        $pendingBillsCount = Bill::where('status', 'unpaid')->count();
        $netIncome = $totalIncome - $totalExpenses;

        return view('dashboard', compact(
            'patientCount', 
            'serviceCount',
            'staffCount',
            'totalIncome', 
            'totalExpenses', 
            'pendingBillsCount',
            'netIncome'
        ));
    }
}
