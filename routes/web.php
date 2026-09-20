<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here you can register web routes for your application. These routes
| are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Reports (Owner only)
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
});

// Services (Owner only)
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::resource('services', ServiceController::class);
});

// Customers (Owner + Staff)
Route::middleware(['auth'])->group(function () {
    Route::resource('customers', CustomerController::class)->except(['destroy']);
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
});

// Orders (Owner + Staff)
Route::middleware(['auth'])->group(function () {
    Route::resource('orders', OrderController::class)->except(['destroy']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/{order}/edit-items', [OrderController::class, 'editItems'])->name('orders.editItems');
    Route::put('orders/{order}/edit-items', [OrderController::class, 'updateItems'])->name('orders.updateItems');
    Route::get('orders/{order}/nota', [OrderController::class, 'nota'])->name('orders.nota');

    // Payments
    Route::get('orders/{order}/payment', [PaymentController::class, 'create'])->name('orders.payment.create');
    Route::post('orders/{order}/payment', [PaymentController::class, 'store'])->name('orders.payment.store');
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
});
