<?php

use App\Http\Controllers\Report\FinancialReportController;
use App\Http\Controllers\Report\PaymentReportController;
use App\Livewire\Reports\FinanceReport;
use App\Livewire\Reports\PaymentReport;
use Illuminate\Support\Facades\Route;

// Financial Reports Routes (for accountant and administrator roles only)
Route::middleware(['auth', 'verified', 'role:administrator'])->prefix('reports')->name('reports.')->group(function () {
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', FinanceReport::class)->name('index');
        Route::get('/search', [FinancialReportController::class, 'search'])->name('search');
        Route::get('/export', [FinancialReportController::class, 'export'])->name('export');
        Route::get('/pdf', [FinancialReportController::class, 'pdf'])->name('pdf');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', PaymentReport::class)->name('index');
        Route::get('/export', [PaymentReportController::class, 'export'])->name('export');
        Route::get('/pdf', [PaymentReportController::class, 'pdf'])->name('pdf');
    });
});
