<?php
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Patient\VitalSignsController;
use App\Http\Controllers\Patient\InvestigationController;
use App\Http\Controllers\Patient\AdmissionController;
use App\Http\Controllers\Patient\PrescriptionController;
use App\Http\Controllers\Patient\ObservationController;

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

         // observation routes
        Route::name('observation.')
        ->prefix('observation')
        ->group(function () {
            Route::get('/{patient}/record', [ObservationController::class, 'record'])->name('record');
            Route::post('/{patient}/register', [ObservationController::class, 'register'])->name('register');
        });

        // prescription routes
        Route::name('prescription.')
        ->prefix('prescription')
        ->group(function () {
            Route::get('/{patient}/create', [PrescriptionController::class, 'create'])->name('create');
            Route::get('/{prescription}/show', [PrescriptionController::class, 'show'])->name('show');
            Route::get('/{prescription}/submit', [PrescriptionController::class, 'submit'])->name('submit');
            Route::post('/{prescription}/add-medicine', [PrescriptionController::class, 'addMedicine'])->name('add');
            Route::post('/{patient}/store', [PrescriptionController::class, 'store'])->name('store');
        });
    });
