<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Lab\InvestigationController;
use App\Http\Controllers\Lab\PatientController;
use App\Http\Controllers\Lab\RequestController;
use App\Http\Controllers\Lab\ParameterController;
use App\Http\Controllers\Lab\ConsumableController;
use App\Http\Controllers\Lab\ConsumableStockController;

Route::middleware(['auth', 'verified', 'role:lab_technician'])
->prefix('lab')
->name('lab.')
->namespace('Lab')
->group(function () {
    
    Route::name('investigations.')
    ->prefix('investigations')
    ->group(function () {
        Route::get('/', [InvestigationController::class, 'index'])->name('index');
        Route::delete('/{investigation}/destroy', [InvestigationController::class, 'destroy'])->name('destroy');
        Route::get('/create', [InvestigationController::class, 'create'])->name('create');
        Route::post('/store', [InvestigationController::class, 'store'])->name('store');
        Route::get('/{investigation}/edit', [InvestigationController::class, 'edit'])->name('edit');
        Route::put('/{investigation}/update', [InvestigationController::class, 'update'])->name('update');
        
        Route::name('parameters.')
        ->prefix('{investigation}/parameters')
        ->group(function () {
            Route::get('/', [ParameterController::class, 'index'])->name('index');
            Route::get('/create', [ParameterController::class, 'create'])->name('create');
            Route::post('/store', [ParameterController::class, 'store'])->name('store');
            Route::get('{parameter}/edit', [ParameterController::class, 'edit'])->name('edit');
            Route::put('{parameter}/update', [ParameterController::class, 'update'])->name('update');
            Route::delete('{parameter}/destroy', [ParameterController::class, 'destroy'])->name('destroy');
        });
    });
    Route::name('requests.')
    ->prefix('requests')
    ->group(function () {
        Route::get('/', [RequestController::class, 'index'])->name('index');
        
        Route::name('results.')
            ->prefix('{investigationRequest}/results')
            ->group(function () {
            Route::get('/create', [RequestController::class, 'createResult'])->name('create');
            Route::get('/show/print', [RequestController::class, 'showResult'])->name('show');
            Route::post('/store', [RequestController::class, 'storeResult'])->name('store');
        });
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