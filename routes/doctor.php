<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\PatientController;


Route::middleware(['auth', 'verified', 'role:doctor'])
->prefix('doctor')
->name('doctor.')
->group(function () {
   
    Route::name('patient.')
    ->prefix('patients')
    ->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/{patient}/show', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/complete', [PatientController::class, 'complete'])->name('complete');
        Route::get('/{patientVisit}/close-visit', [PatientController::class, 'closeVisit'])->name('close-visit');        
    });

  
});