<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\Nurse\PatientController;
use App\Http\Controllers\Nurse\VitalSignsController;
use App\Http\Controllers\Nurse\InvestigationController;

Route::middleware(['auth', 'verified', 'role:doctor'])
->prefix('doctor')
->name('doctor.')
->group(function () {
   
    
    Route::name('patients.')
    ->namespace('Doctor')
    ->prefix('patients')
    ->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/{patient}/show', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/history', [PatientController::class, 'history'])->name('history');
        Route::get('/search', [PatientController::class, 'search'])->name('search');
        
    });

  
});