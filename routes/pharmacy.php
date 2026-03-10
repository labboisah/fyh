<?php
use App\Http\Controllers\Pharmacy\MedicineController;
use App\Http\Controllers\Pharmacy\StockController;

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

    Route::get('inventory', [PharmacyInventoryController::class,'index'])
        ->name('pharmacy.inventory');

    Route::get('stock/create', [PharmacyInventoryController::class,'create'])
        ->name('pharmacy.stock.create');

    Route::post('stock/store', [PharmacyInventoryController::class,'store'])
        ->name('pharmacy.stock.store');

    Route::get('expiry', [PharmacyInventoryController::class,'expiry'])
        ->name('pharmacy.expiry');

});