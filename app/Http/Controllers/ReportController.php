<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Expense;
use App\Models\Patient;
use App\Models\BillItem;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $timeframe = $request->get('timeframe', 'monthly'); // weekly, monthly, yearly, custom
        $category = $request->get('category', 'all'); // all, financial, patients, services
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        
        $now = Carbon::now();
        $startDate = null;
        $endDate = null;

        if ($timeframe === 'custom' && $fromDate && $toDate) {
            $startDate = Carbon::parse($fromDate)->startOfDay();
            $endDate = Carbon::parse($toDate)->endOfDay();
        } elseif ($timeframe === 'weekly') {
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfDay();
        } elseif ($timeframe === 'yearly') {
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfDay();
        } else {
            // Default to monthly
            $timeframe = 'monthly';
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfDay();
        }

        // 1. Financial Summary
        $income = Bill::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        $expenses = Expense::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('amount');

        $netProfit = $income - $expenses;

        // 2. Patient Registrations
        $newPatients = Patient::whereBetween('created_at', [$startDate, $endDate])->count();

        // 3. Billing Activity
        $paidBillsCount = Bill::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        $unpaidBillsCount = Bill::where('status', 'unpaid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 4. Data for Charts (Normalized for consistent labels)
        $labels = [];
        $incomeByLabel = [];
        $expenseByLabel = [];

        // Determine labels range based on timeframe
        $tempDate = $startDate->copy();
        while ($tempDate <= $endDate) {
            $label = $timeframe === 'yearly' ? $tempDate->format('M') : $tempDate->toDateString();
            $labels[] = $label;
            $incomeByLabel[$label] = 0;
            $expenseByLabel[$label] = 0;
            
            if ($timeframe === 'yearly') {
                $tempDate->addMonth();
            } else {
                $tempDate->addDay();
            }
        }

        // Fill data
        $rawIncome = Bill::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw($timeframe === 'yearly' ? 'MONTH(created_at) as m, SUM(total) as value' : 'DATE(created_at) as d, SUM(total) as value')
            ->groupBy($timeframe === 'yearly' ? 'm' : 'd')
            ->get();

        foreach ($rawIncome as $item) {
            if ($timeframe === 'yearly') {
                $l = Carbon::create()->month($item->m)->format('M');
            } else {
                $l = $item->d;
            }
            if (isset($incomeByLabel[$l])) $incomeByLabel[$l] = (float)$item->value;
        }

        $rawExpense = Expense::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw($timeframe === 'yearly' ? 'MONTH(date) as m, SUM(amount) as value' : 'DATE(date) as d, SUM(amount) as value')
            ->groupBy($timeframe === 'yearly' ? 'm' : 'd')
            ->get();

        foreach ($rawExpense as $item) {
            if ($timeframe === 'yearly') {
                $l = Carbon::create()->month($item->m)->format('M');
            } else {
                $l = $item->d;
            }
            if (isset($expenseByLabel[$l])) $expenseByLabel[$l] = (float)$item->value;
        }

        $chartData = [
            'labels' => $labels,
            'income' => array_values($incomeByLabel),
            'expenses' => array_values($expenseByLabel),
        ];

        // 5. Top Services
        $topServices = BillItem::select('service_id', DB::raw('count(*) as total_usage'), DB::raw('sum(subtotal) as total_revenue'))
            ->whereHas('bill', function($query) use ($startDate, $endDate) {
                $query->where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('service')
            ->groupBy('service_id')
            ->orderBy('total_usage', 'desc')
            ->limit(5)
            ->get();

        // 6. Detailed Records (for Income and Expense Report)
        $detailedBills = Bill::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->get();

        $detailedExpenses = Expense::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date', 'desc')
            ->get();

        return view('reports.index', compact(
            'income', 
            'expenses', 
            'netProfit', 
            'newPatients', 
            'paidBillsCount', 
            'unpaidBillsCount',
            'chartData',
            'topServices',
            'detailedBills',
            'detailedExpenses',
            'timeframe',
            'category',
            'startDate',
            'endDate'
        ));
    }
}
