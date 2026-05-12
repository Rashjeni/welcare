<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reports') }} - 
                @if($timeframe === 'custom')
                    {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}
                @else
                    <span class="capitalize">{{ $timeframe }}</span>
                @endif
            </h2>
            <div class="flex items-center space-x-4 no-print">
                <div class="flex bg-gray-100 p-1 rounded-lg">
                    <a href="{{ route('reports.index', ['category' => 'all', 'timeframe' => $timeframe, 'from_date' => $startDate->toDateString(), 'to_date' => $endDate->toDateString()]) }}" 
                       class="px-3 py-1 text-xs font-medium rounded-md {{ $category === 'all' ? 'bg-white shadow text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        All
                    </a>
                    <a href="{{ route('reports.index', ['category' => 'financial', 'timeframe' => $timeframe, 'from_date' => $startDate->toDateString(), 'to_date' => $endDate->toDateString()]) }}" 
                       class="px-3 py-1 text-xs font-medium rounded-md {{ $category === 'financial' ? 'bg-white shadow text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Financial
                    </a>
                    <a href="{{ route('reports.index', ['category' => 'patients', 'timeframe' => $timeframe, 'from_date' => $startDate->toDateString(), 'to_date' => $endDate->toDateString()]) }}" 
                       class="px-3 py-1 text-xs font-medium rounded-md {{ $category === 'patients' ? 'bg-white shadow text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Patients
                    </a>
                    <a href="{{ route('reports.index', ['category' => 'services', 'timeframe' => $timeframe, 'from_date' => $startDate->toDateString(), 'to_date' => $endDate->toDateString()]) }}" 
                       class="px-3 py-1 text-xs font-medium rounded-md {{ $category === 'services' ? 'bg-white shadow text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        Services
                    </a>
                </div>
                <div class="h-8 border-l border-gray-300 mx-2"></div>
                <form action="{{ route('reports.index') }}" method="GET" class="flex items-center space-x-2">
                    <input type="hidden" name="timeframe" value="custom">
                    <input type="hidden" name="category" value="{{ $category }}">
                    <div class="flex items-center space-x-1">
                        <label for="from_date" class="text-xs text-gray-500 uppercase font-bold">From:</label>
                        <input type="date" name="from_date" id="from_date" value="{{ $startDate->toDateString() }}" 
                               class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="flex items-center space-x-1">
                        <label for="to_date" class="text-xs text-gray-500 uppercase font-bold">To:</label>
                        <input type="date" name="to_date" id="to_date" value="{{ $endDate->toDateString() }}" 
                               class="text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                        Filter
                    </button>
                </form>
                <div class="h-8 border-l border-gray-300 mx-2"></div>
                <div class="flex space-x-2">
                    <a href="{{ route('reports.index', ['timeframe' => 'weekly']) }}" 
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $timeframe === 'weekly' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-300">
                        Weekly
                    </a>
                    <a href="{{ route('reports.index', ['timeframe' => 'monthly']) }}" 
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $timeframe === 'monthly' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-300">
                        Monthly
                    </a>
                    <a href="{{ route('reports.index', ['timeframe' => 'yearly']) }}" 
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $timeframe === 'yearly' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border border-gray-300">
                        Yearly
                    </a>
                </div>
                <button onclick="window.print()" class="flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 ml-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print PDF
                </button>
            </div>
        </div>
    </x-slot>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .py-12 { padding-top: 0 !important; padding-bottom: 0 !important; }
            .shadow-sm { shadow: none !important; border: 1px solid #eee !important; }
            .bg-gray-100 { background: white !important; }
            .max-w-7xl { max-width: 100% !important; }
            header { padding-top: 10px !important; padding-bottom: 10px !important; margin-bottom: 20px !important; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="hidden print:block mb-6 text-right text-xs text-gray-500">
                Report generated on: {{ now()->format('M d, Y H:i:s') }}
            </div>
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                @if($category === 'all' || $category === 'financial')
                    <!-- Income Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Income</div>
                        <div class="text-2xl font-bold text-gray-800">LKR {{ number_format($income, 2) }}</div>
                        <div class="text-xs text-gray-400 mt-1">From {{ $paidBillsCount }} paid bills</div>
                    </div>

                    <!-- Expenses Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Expenses</div>
                        <div class="text-2xl font-bold text-gray-800">LKR {{ number_format($expenses, 2) }}</div>
                    </div>

                    <!-- Net Profit Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 {{ $netProfit >= 0 ? 'border-indigo-500' : 'border-orange-600' }}">
                        <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Net Profit</div>
                        <div class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-indigo-600' : 'text-red-600' }}">
                            LKR {{ number_format($netProfit, 2) }}
                        </div>
                    </div>
                @endif

                @if($category === 'all' || $category === 'patients')
                    <!-- New Patients Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">New Patients</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $newPatients }}</div>
                    </div>
                @endif
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                @if($category === 'all' || $category === 'financial')
                    <!-- Financial Trend Chart -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Income vs Expenses</h3>
                        <canvas id="financialChart" height="200"></canvas>
                    </div>

                    <!-- Billing Status Chart -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Billing Activity</h3>
                        <canvas id="billingChart" height="200"></canvas>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @if($category === 'all' || $category === 'services')
                    <!-- Top Services -->
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Services by Revenue</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($topServices as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item->service->name ?? $item->description ?? 'Unknown Service' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $item->total_usage }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">LKR {{ number_format($item->total_revenue, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No data available for this period.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($category === 'all' || $category === 'financial')
                    <!-- Summary Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b pb-2">
                                <span class="text-gray-600">Paid Bills</span>
                                <span class="font-bold text-green-600">{{ $paidBillsCount }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b pb-2">
                                <span class="text-gray-600">Unpaid Bills</span>
                                <span class="font-bold text-red-600">{{ $unpaidBillsCount }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b pb-2">
                                <span class="text-gray-600">Total Transactions</span>
                                <span class="font-bold text-gray-800">{{ $paidBillsCount + $unpaidBillsCount }}</span>
                            </div>
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 leading-relaxed">
                                    This report summarizes all financial and patient activity for the selected timeframe. 
                                    Income is calculated from "Paid" bills only.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if($category === 'all' || $category === 'financial')
                <!-- Income Details Table -->
                <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detailed Income (Paid Bills)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill #</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($detailedBills as $bill)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bill->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $bill->bill_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bill->patient->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">LKR {{ number_format($bill->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No income records found for this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($detailedBills->count() > 0)
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="3" class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">Total Income:</td>
                                        <td class="px-6 py-3 text-right text-sm font-bold text-green-600">LKR {{ number_format($income, 2) }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Expense Details Table -->
                <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detailed Expenses</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($detailedExpenses as $expense)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $expense->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $expense->category ?? 'General' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">LKR {{ number_format($expense->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No expense records found for this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($detailedExpenses->count() > 0)
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="3" class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">Total Expenses:</td>
                                        <td class="px-6 py-3 text-right text-sm font-bold text-red-600">LKR {{ number_format($expenses, 2) }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Financial Chart
            const financialCanvas = document.getElementById('financialChart');
            if (financialCanvas) {
                const ctxFinancial = financialCanvas.getContext('2d');
                const financialLabels = @json($chartData['labels']);
                const incomeValues = @json($chartData['income']);
                const expenseValues = @json($chartData['expenses']);

                new Chart(ctxFinancial, {
                    type: 'line',
                    data: {
                        labels: financialLabels,
                        datasets: [
                            {
                                label: 'Income',
                                data: incomeValues,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Expenses',
                                data: expenseValues,
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }

            // Billing Chart
            const billingCanvas = document.getElementById('billingChart');
            if (billingCanvas) {
                const ctxBilling = billingCanvas.getContext('2d');
                new Chart(ctxBilling, {
                    type: 'doughnut',
                    data: {
                        labels: ['Paid', 'Unpaid'],
                        datasets: [{
                            data: [{{ $paidBillsCount }}, {{ $unpaidBillsCount }}],
                            backgroundColor: ['rgb(34, 197, 94)', 'rgb(239, 68, 68)'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
