<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Nurse\NurseController;
use App\Http\Controllers\Nurse\PatientController;
use App\Http\Controllers\Nurse\VitalSignsController;
use App\Http\Controllers\Patient\InvestigationController;
use App\Livewire\Nurse\PatientManagement;

Route::middleware(['auth', 'verified', 'role:nurse'])
->prefix('nurse')
->name('nurse.')
->group(function () {
    Route::get('/', [NurseController::class, 'dashboard'])->name('dashboard');
    
    Route::name('patient.')
    ->prefix('patients')
    ->group(function () {
        Route::get('/', PatientManagement::class)->name('index');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('/{serviceRequest}/complete', [PatientController::class, 'complete'])->name('complete');
        Route::get('/{patientVisit}/close-visit', [PatientController::class, 'closeVisit'])->name('close-visit');
    });

    
});
