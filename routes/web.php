<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TemporaryPermissionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\RecordOfficerController;
use App\Http\Controllers\AccountantController;
use App\Http\Controllers\VitalSignsController;
use Illuminate\Support\Facades\Route;

// ajax routes
Route::get('/ajax/investigations/{typeId}', [App\Http\Controllers\AjaxController::class, 'getInvestigations'])->name('ajax.get-investigations');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    Route::middleware('role:administrator')->group(function () {
        // Roles Management
        Route::resource('roles', RoleController::class);
        
        // Permissions Management
        Route::resource('permissions', PermissionController::class);
        
        // Users Management (full resource)
        Route::resource('users', UserController::class);
        
        Route::put('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');

        // Temporary Permissions Management
        Route::get('temporary-permissions', [TemporaryPermissionController::class, 'index'])->name('temporary-permissions.index');
        Route::get('temporary-permissions/create', [TemporaryPermissionController::class, 'create'])->name('temporary-permissions.create');
        Route::post('temporary-permissions', [TemporaryPermissionController::class, 'store'])->name('temporary-permissions.store');
        Route::put('temporary-permissions/{temporaryPermission}/revoke', [TemporaryPermissionController::class, 'revoke'])->name('temporary-permissions.revoke');
        Route::delete('temporary-permissions/{temporaryPermission}', [TemporaryPermissionController::class, 'destroy'])->name('temporary-permissions.destroy');

        // Services Management
        Route::resource('services', ServiceController::class);
        Route::put('services/{service}/restore', [ServiceController::class, 'restore'])->name('services.restore');
    });
});

// Record Officer Routes - Patient Registration and Visit Recording
Route::middleware(['auth', 'verified', 'role:record_officer'])->prefix('record-officer')->name('record_officer.')->group(function () {
    Route::get('/', [RecordOfficerController::class, 'dashboard'])->name('dashboard');
    
    // Patient Management
    Route::get('patients/list', [RecordOfficerController::class, 'listPatients'])->name('patients.list');
    Route::get('patients/search', [RecordOfficerController::class, 'search'])->name('patients.search');
    Route::get('patients/register', [RecordOfficerController::class, 'registerForm'])->name('patients.register.form');
    Route::post('patients/register', [RecordOfficerController::class, 'register'])->name('patients.register');
    Route::get('patients/{patient}', [RecordOfficerController::class, 'showPatient'])->name('patients.show');
    Route::get('patients/{patient}/edit', [RecordOfficerController::class, 'editForm'])->name('patients.edit.form');
    Route::put('patients/{patient}', [RecordOfficerController::class, 'update'])->name('patients.update');
    
    // Patient Visits - Submit to Nurse for Vital Signs
    Route::get('patients/{patient}/visits/create', [RecordOfficerController::class, 'visitForm'])->name('visits.create.form');
    Route::post('patients/{patient}/visits', [RecordOfficerController::class, 'storeVisit'])->name('visits.store');
    
    // Workflow - Create st (for dual-role users)
    Route::get('patients/{patient}/bills/create', [RecordOfficerController::class, 'createBill'])->name('bills.create.form');
    
    // Workflow - Create Payment (for dual-role users)
    Route::get('patients/{patient}/payments/create', [RecordOfficerController::class, 'createPayment'])->name('payments.create.form');
    
    // Workflow - Submit for Vital Signs
    Route::get('patients/{patient}/vital-signs/request', [RecordOfficerController::class, 'requestForVitalSigns'])->name('vital-signs.request');
    
    
    // Export
    Route::get('patients/{patient}/export', [RecordOfficerController::class, 'exportRecord'])->name('patients.export');
});

// Accountant Routes - Billing and Payment Management
Route::middleware(['auth', 'verified', 'role:accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    Route::get('/', [AccountantController::class, 'dashboard'])->name('dashboard');
    
    // Bills Management
    Route::get('bills', [AccountantController::class, 'listBills'])->name('bills.index');
    Route::get('/bills/{patient}/create', [AccountantController::class, 'createBill'])->name('bills.create');
    Route::post('bills', [AccountantController::class, 'storeBill'])->name('bills.store');
    Route::get('bills/{bill}', [AccountantController::class, 'showBill'])->name('bills.show');
    Route::get('bills/{bill}/edit', [AccountantController::class, 'editBill'])->name('bills.edit');
    Route::put('bills/{bill}', [AccountantController::class, 'updateBill'])->name('bills.update');
    Route::delete('bills/{bill}', [AccountantController::class, 'deleteBill'])->name('bills.delete');
    
    // Payments Management
    Route::get('payments', [AccountantController::class, 'listPayments'])->name('payments.index');
    Route::get('{bill}/payments/create', [AccountantController::class, 'createPayment'])->name('payments.create');
    Route::post('payments', [AccountantController::class, 'storePayment'])->name('payments.store');
    Route::get('payments/{payment}/receipt', [AccountantController::class, 'paymentReceipt'])->name('payment-receipt');
    Route::get('patients/{patient}/payment-history', [AccountantController::class, 'patientPaymentHistory'])->name('patient-payment-history');
    
    // Insurance Billing
    Route::get('insurance-billing', [AccountantController::class, 'insuranceBilling'])->name('insurance-billing');
    
    // Financial Reports
    Route::get('reports/financial', [AccountantController::class, 'financialReport'])->name('reports.financial');
    Route::get('reports/financial/export', [AccountantController::class, 'exportFinancialReport'])->name('reports.financial.export');
});

// nurse routes - Vital Signs Recording and Patient Monitoring
Route::middleware(['auth', 'verified', 'role:nurse'])->prefix('nurse')->name('nurse.')->group(function () {
    Route::get('/', [VitalSignsController::class, 'dashboard'])->name('dashboard');
    Route::get('patients/{patient}/vital-signs/submit', [RecordOfficerController::class, 'submitForVitalSigns'])->name('vital-signs.submit');
});

// Vital Signs Routes (for medical staff - Nurse/Doctor)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('vital-signs/patients/{patient}/create', [VitalSignsController::class, 'createForm'])->name('vital_signs.create');
    Route::post('vital-signs/patients/{patient}', [VitalSignsController::class, 'store'])->name('vital_signs.store');
    Route::get('vital-signs/patients/{patient}/history', [VitalSignsController::class, 'history'])->name('vital_signs.history');
});

require __DIR__.'/auth.php';
require __DIR__.'/nurse.php';
require __DIR__.'/lab.php';
