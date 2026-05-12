<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ServiceController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('patients', PatientController::class);
    
    Route::middleware(['admin'])->group(function () {
        Route::resource('services', ServiceController::class);
        Route::post('/services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::resource('staff', \App\Http\Controllers\StaffController::class);
    });
    
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/create', [BillingController::class, 'create'])->name('billing.create');
    Route::post('/billing', [BillingController::class, 'store'])->name('billing.store');
    Route::get('/billing/{bill}', [BillingController::class, 'show'])->name('billing.show');
    Route::post('/billing/{bill}/mark-as-paid', [BillingController::class, 'markAsPaid'])->name('billing.mark-as-paid');
    Route::post('/billing/{bill}/mark-as-unpaid', [BillingController::class, 'markAsUnpaid'])->name('billing.mark-as-unpaid');

    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';
