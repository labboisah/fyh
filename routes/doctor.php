<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\PatientController;
use App\Livewire\Doctor\PatientManagement;


Route::middleware(['auth', 'verified', 'role:doctor'])
->prefix('doctor')
->name('doctor.')
->group(function () {
   
    Route::name('patient.')
    ->prefix('patients')
    ->group(function () {
        Route::get('/', PatientManagement::class)->name('index');
        Route::get('/{patient}/show', [PatientController::class, 'show'])->name('show');
        Route::get('/{serviceRequest}/complete', [PatientController::class, 'complete'])->name('complete');
        Route::get('/{patientVisit}/close-visit', [PatientController::class, 'closeVisit'])->name('close-visit');        
    });

  
});
