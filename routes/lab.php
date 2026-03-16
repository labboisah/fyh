<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Lab\InvestigationController;
use App\Http\Controllers\Lab\PatientController;
use App\Http\Controllers\Lab\RequestController;
use App\Http\Controllers\Lab\ParameterController;


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

    
});