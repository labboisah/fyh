<?php
use App\Http\Controllers\Pharmacy\MedicineController;
use App\Http\Controllers\Pharmacy\StockController;
use App\Http\Controllers\Pharmacy\ExpiryController;
use App\Http\Controllers\Pharmacy\TransactionController;

Route::prefix('pharmacy')
->middleware(['auth', 'verified', 'role:pharmacist'])
->name('pharmacy.')
->group(function () {

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

Route::prefix('transactions')
->name('transactions.')
->group(function () {
    Route::get('/', [TransactionController::class,'index'])->name('index');
    Route::get('/create', [TransactionController::class,'create'])->name('create');
    Route::post('/store', [TransactionController::class,'store'])->name('store');
});

Route::prefix('expiries')
->name('expiries.')
->group(function () {
    Route::get('/', [ExpiryController::class,'index'])->name('index');
    
});


});