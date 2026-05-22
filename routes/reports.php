<?php

use App\Http\Controllers\Report\FinancialReportController;
use Illuminate\Support\Facades\Route;

// Financial Reports Routes (for accountant and administrator roles only)
Route::middleware(['auth', 'verified', 'role:administrator'])->prefix('reports')->name('reports.')->group(function () {
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinancialReportController::class, 'index'])->name('index');
        Route::get('/search', [FinancialReportController::class, 'search'])->name('search');
        Route::get('/export', [FinancialReportController::class, 'export'])->name('export');
    });
});
