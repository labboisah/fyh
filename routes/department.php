<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Department\ExpenseController;
use App\Http\Controllers\Department\ConsumableController;
use App\Http\Controllers\Department\ConsumableStockController;

Route::middleware(['auth', 'verified', 'role:head_of_department'])
->prefix('department')
->name('department.')
->group(function () {

    Route::name('expenses.')
    ->prefix('expenses')
    ->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
        Route::delete('/{expense}/destroy', [ExpenseController::class, 'destroy'])->name('destroy');
        Route::put('/{expense}/update', [ExpenseController::class, 'update'])->name('update');
        Route::post('/store', [ExpenseController::class, 'store'])->name('store');
    });

    Route::name('consumables.')
    ->prefix('consumables')
    ->group(function () {
        Route::get('/', [ConsumableController::class, 'index'])->name('index');
        Route::get('/create', [ConsumableController::class, 'create'])->name('create');
        Route::get('/{consumable}/edit', [ConsumableController::class, 'edit'])->name('edit');
        Route::delete('/{consumable}/destroy', [ConsumableController::class, 'destroy'])->name('destroy');
        Route::put('/{consumable}/update', [ConsumableController::class, 'update'])->name('update');
        Route::post('/store', [ConsumableController::class, 'store'])->name('store');
    });

    Route::name('stocks.')
    ->prefix('consumable-stocks')
    ->group(function () {
        Route::get('/', [ConsumableStockController::class, 'index'])->name('index');
        Route::get('/create', [ConsumableStockController::class, 'create'])->name('create');
        Route::get('/{consumableStock}/edit', [ConsumableStockController::class, 'edit'])->name('edit');
        Route::delete('/{consumableStock}/destroy', [ConsumableStockController::class, 'destroy'])->name('destroy');
        Route::put('/{consumableStock}/update', [ConsumableStockController::class, 'update'])->name('update');
        Route::post('/store', [ConsumableStockController::class, 'store'])->name('store');
    });
    
});