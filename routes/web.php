<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;

// 1. Halaman utama (Katalog Layanan Laundry Pelanggan)
Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : redirect()->route('login'));

Route::get('/tracking', [OrderController::class, 'tracking'])->name('tracking');
Route::post('/tracking', [OrderController::class, 'track'])->name('tracking.check');

// 2. Arahkan dashboard utama Breeze langsung ke Admin Dashboard Rekap Pesanan Laundry
Route::get('/dashboard', [OrderController::class, 'adminDashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

// 3. Grup Rute Admin (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alias route untuk admin dashboard & update status pesanan
    Route::get('/admin/dashboard', [OrderController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/transactions', [OrderController::class, 'transactions'])->name('admin.transactions.index');
    Route::get('/admin/transactions/create', [OrderController::class, 'create'])->name('admin.transactions.create');
    Route::post('/admin/transactions', [OrderController::class, 'store'])->name('admin.transactions.store');
    Route::patch('/admin/transactions/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.transactions.updateStatus');
    Route::patch('/admin/transactions/{order}/payment', [OrderController::class, 'updatePayment'])->name('admin.transactions.updatePayment');
    Route::get('/admin/transactions/{order}/print', [OrderController::class, 'printReceipt'])->name('admin.transactions.print');
    Route::get('/admin/reports', [OrderController::class, 'report'])->name('admin.reports.index');
    Route::get('/admin/reports/export', [OrderController::class, 'exportReport'])->name('admin.reports.export');
    Route::resource('/admin/customers', CustomerController::class)
        ->except(['show'])
        ->middleware('role:admin');

    Route::resource('/admin/services', ServiceController::class)->middleware('role:admin');
});

require __DIR__.'/auth.php';

