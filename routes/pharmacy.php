<?php
use App\Http\Controllers\Pharmacy\MedicineController;
use App\Http\Controllers\Pharmacy\StockController;
use App\Http\Controllers\Pharmacy\ExpiryController;
use App\Http\Controllers\Pharmacy\TransactionController;
use App\Http\Controllers\Pharmacy\PrescriptionController;
use App\Http\Controllers\Pharmacy\FinanceController;
use App\Livewire\Pharmacy\PrescriptionDispenseWorkspace;
use App\Livewire\Pharmacy\TransactionIndex;
use App\Livewire\Pharmacy\TransactionWorkspace;

Route::prefix('pharmacy')
->middleware(['auth', 'verified', 'role:pharmacist'])
->name('pharmacy.')
->group(function () {

Route::prefix('transactions')
->name('transactions.')
->group(function () {
    Route::get('/', TransactionIndex::class)->name('index');
    Route::get('/create', TransactionWorkspace::class)->name('create');
    Route::post('/store', [TransactionController::class,'store'])->name('store');
    Route::get('/report', [TransactionController::class,'report'])->name('report');
});

Route::prefix('prescriptions')
->name('prescriptions.')
->group(function () {
    Route::get('/', [PrescriptionController::class,'index'])->name('index');
    Route::get('/{prescription}', PrescriptionDispenseWorkspace::class)->name('show');
});

Route::prefix('finance')
->name('finance.')
->group(function () {
    Route::get('/bills', [FinanceController::class,'bills'])->name('bills');
    Route::get('/payments', [FinanceController::class,'payments'])->name('payments');
    Route::get('/payments/{payment}/receipt', [FinanceController::class,'receipt'])->name('payments.receipt');
    Route::get('/report', [FinanceController::class,'report'])->name('report');
});

Route::middleware('pharmacy.manager')->group(function () {
    Route::prefix('medicines')
    ->name('medicines.')
    ->group(function () {
        Route::get('/', [MedicineController::class,'index'])->name('index');
        Route::get('/create', [MedicineController::class,'create'])->name('create');
        Route::post('/store', [MedicineController::class,'store'])->name('store');
    });

    Route::prefix('stocks')
    ->name('stocks.')
    ->group(function () {
        Route::get('/', [StockController::class,'index'])->name('index');
        Route::get('/create', [StockController::class,'create'])->name('create');
        Route::post('/store', [StockController::class,'store'])->name('store');
    });

    Route::prefix('expiries')
    ->name('expiries.')
    ->group(function () {
        Route::get('/', [ExpiryController::class,'index'])->name('index');
    });
});


});
