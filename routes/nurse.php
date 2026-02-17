<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\Nurse\PatientController;
use App\Http\Controllers\Nurse\VitalSignsController;
use App\Http\Controllers\Nurse\InvestigationController;

Route::middleware(['auth', 'verified', 'role:nurse'])
->prefix('nurse')
->name('nurse.')
->group(function () {
    Route::get('/', [NurseController::class, 'dashboard'])->name('dashboard');
    
    Route::name('patients.')
    ->namespace('Nurse')
    ->prefix('patients')
    ->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/{patient}/show', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/history', [PatientController::class, 'history'])->name('history');
        Route::get('/search', [PatientController::class, 'search'])->name('search');
        // vital signs routes
        Route::name('vitalsigns.')
        ->prefix('vital-signs')
        ->group(function () {
            Route::get('/{vitalSignsRequest}/create', [VitalSignsController::class, 'create'])->name('create');
            Route::post('/{vitalSignsRequest}/register', [VitalSignsController::class, 'register'])->name('register');
            Route::get('/{patientVisitVitalSign}/edit', [VitalSignsController::class, 'edit'])->name('edit');
            Route::put('/{patientVisitVitalSign}/update', [VitalSignsController::class, 'update'])->name('update');
        });
        // investigation request routes
        Route::name('investigations.')
        ->prefix('investigations')
        ->group(function () {
            Route::get('/{patient}/create', [InvestigationController::class, 'create'])->name('create');
            Route::post('/{patient}/store', [InvestigationController::class, 'store'])->name('store');
            Route::get('/{investigationRequest}/show', [InvestigationController::class, 'show'])->name('show');
        });
    });

    Route::get('/patients', [NurseController::class, 'patients'])->name('patients.index');
    Route::get('/patients/{patient}', [NurseController::class, 'showPatient'])->name('patients.show');
    Route::get('/patients/{patient}/vital-signs', [NurseController::class, 'vitalSignsForm'])->name('patients.vital-signs.form');
    Route::post('/patients/{patient}/vital-signs', [NurseController::class, 'storeVitalSigns'])->name('patients.vital-signs.store');
    Route::get('/patients/{patient}/monitoring', [NurseController::class, 'monitoring'])->name('patients.monitoring');
    Route::get('/patients/{patient}/monitoring/export', [NurseController::class, 'exportMonitoring'])->name('patients.monitoring.export');
});