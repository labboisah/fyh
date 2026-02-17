<?php
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Patient\VitalSignsController;
use App\Http\Controllers\Patient\InvestigationController;
use App\Http\Controllers\Patient\AdmissionController;

Route::name('patient.')
    ->namespace('Patient')
    ->prefix('patient')
    ->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/{patient}/show', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/history', [PatientController::class, 'history'])->name('history');
        Route::get('/search', [PatientController::class, 'search'])->name('search');
        // vital signs routes
        Route::name('vitalsign.')
        ->prefix('vital-sign')
        ->group(function () {
            Route::get('/{vitalSignsRequest}/create', [VitalSignsController::class, 'create'])->name('create');
            Route::post('/{vitalSignsRequest}/register', [VitalSignsController::class, 'register'])->name('register');
            Route::get('/{patientVisitVitalSign}/edit', [VitalSignsController::class, 'edit'])->name('edit');
            Route::put('/{patientVisitVitalSign}/update', [VitalSignsController::class, 'update'])->name('update');
        });
        // investigation request routes
        Route::name('investigation.')
        ->prefix('investigation')
        ->group(function () {
            Route::get('/{patient}/create', [InvestigationController::class, 'create'])->name('create');
            Route::post('/{patient}/store', [InvestigationController::class, 'store'])->name('store');
            Route::get('/{investigationRequest}/show', [InvestigationController::class, 'show'])->name('show');
        });

        // admission routes
        Route::name('admission.')
        ->prefix('admission')
        ->group(function () {
            Route::get('/{patient}/create', [AdmissionController::class, 'create'])->name('create');
            Route::post('/{patient}/store', [AdmissionController::class, 'store'])->name('store');
        });
    });
