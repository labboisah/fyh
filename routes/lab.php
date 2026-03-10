<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Lab\InvestigationController;
use App\Http\Controllers\Lab\PatientController;
use App\Http\Controllers\Lab\RequestController;

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