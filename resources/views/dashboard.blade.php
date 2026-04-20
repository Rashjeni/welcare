<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Patients Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full mr-4 text-blue-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Patients</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $patientCount }}</div>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                    <!-- Services Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full mr-4 text-purple-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.628.285a2 2 0 01-1.253.242l-2.387-.477a2 2 0 00-2.305 1.144l-.333 1.11a2 2 0 00.453 2.02l1.17 1.17a2 2 0 001.414.586h3.111a2 2 0 001.414-.586l1.17-1.17a2 2 0 00.453-2.02l-.333-1.11z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3L4.373 8.627A2 2 0 004 10.04V19a2 2 0 002 2h4M14 3l5.627 5.627A2 2 0 0120 10.04V19a2 2 0 00-2 2h-4m-4-18v18"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Services</div>
                                <div class="text-2xl font-bold text-gray-800">{{ $serviceCount }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-pink-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-pink-100 rounded-full mr-4 text-pink-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Staff</div>
                                <div class="text-2xl font-bold text-gray-800">{{ $staffCount }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->isAdmin())
                    <!-- Total Income Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full mr-4 text-green-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Income</div>
                                <div class="text-2xl font-bold text-gray-800">LKR {{ number_format($totalIncome, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Expenses Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-full mr-4 text-red-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Expenses</div>
                                <div class="text-2xl font-bold text-gray-800">LKR {{ number_format($totalExpenses, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Income Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 {{ $netIncome >= 0 ? 'border-indigo-500' : 'border-orange-600' }}">
                        <div class="flex items-center">
                            <div class="p-3 {{ $netIncome >= 0 ? 'bg-indigo-100 text-indigo-500' : 'bg-orange-100 text-orange-600' }} rounded-full mr-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Net Profit / Loss</div>
                                <div class="text-2xl font-bold {{ $netIncome >= 0 ? 'text-indigo-600' : 'text-red-600' }}">
                                    LKR {{ number_format($netIncome, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Pending Bills Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full mr-4 text-yellow-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 uppercase font-bold tracking-wider">Pending Bills</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $pendingBillsCount }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Welcome back, {{ Auth::user()->name }}! Use the navigation menu to manage patients, services, billing, and expenses.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
