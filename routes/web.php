<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InvestigationController;
use App\Http\Controllers\Admin\TemporaryPermissionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\RecordOfficerController;
use App\Http\Controllers\AccountantController;
use App\Http\Controllers\VitalSignsController;
use App\Http\Controllers\Admin\BedController;
use App\Http\Controllers\Admin\WardController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\BillServiceController;
use App\Http\Controllers\Admin\BillInvestigationController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemUpdateController;
use App\Http\Controllers\SyncronizationController;


// ajax routes
Route::get('/ajax/investigations/{typeId}', [App\Http\Controllers\AjaxController::class, 'getInvestigations'])->name('ajax.get-investigations');
Route::get('/ajax/beds/{wardId}', [App\Http\Controllers\AjaxController::class, 'getWardBeds'])->name('ajax.get-ward-beds');
Route::get('/ajax/medicines/{medicineTypeId}', [App\Http\Controllers\AjaxController::class, 'getMedicines'])->name('ajax.get-type-medicines');
Route::get('/ajax/state/{stateId}/get-lgas', [App\Http\Controllers\AjaxController::class, 'getLgas'])->name('ajax.get-lgas');

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

    Route::prefix('/report')->name('report.')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::post('/generate', [ReportsController::class, 'generate'])->name('generate');
        Route::get('/show', [ReportsController::class, 'show'])->name('show');
    });
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

        Route::resource('departments', DepartmentController::class);

        Route::resource('wards', WardController::class);

        Route::resource('investigations', InvestigationController::class);
        Route::resource('expenses', ExpenseController::class)->except(['show']);
        Route::resource('revenues', RevenueController::class)->except(['show']);


        Route::prefix('wards/beds')->name('beds.')->group(function () {
            Route::get('/{ward}', [BedController::class, 'index'])->name('index');
            Route::get('/{ward}/create', [BedController::class, 'create'])->name('create');
            Route::post('/{ward}/store', [BedController::class, 'store'])->name('store');
            Route::get('/{bed}/edit', [BedController::class, 'edit'])->name('edit');
            Route::put('/{bed}/update', [BedController::class, 'update'])->name('update');
            Route::delete('/{bed}/destroy', [BedController::class, 'destroy'])->name('destroy');
        });

        // bills management
        Route::prefix('bills')->name('bills.')->group(function () {
            Route::get('/', [BillController::class, 'index'])->name('index');
            Route::get('/{bill}', [BillController::class, 'show'])->name('show');
            Route::get('/{bill}/edit', [BillController::class, 'edit'])->name('edit');  
            Route::put('/{bill}', [BillController::class, 'update'])->name('update');
            Route::delete('/{bill}', [BillController::class, 'destroy'])->name('delete');

            // investigation management within bills
            Route::get('/{bill}/investigations/create', [BillInvestigationController::class, 'create'])->name('investigations.create');
            Route::put('/investigation/{billInvestigation}', [BillInvestigationController::class, 'update'])->name('investigations.update');
            Route::delete('/investigation/{billInvestigation}', [BillInvestigationController::class, 'destroy'])->name('investigations.destroy');
            Route::get('/investigation/{billInvestigation}/edit', [BillInvestigationController::class, 'edit'])->name('investigations.edit');
            Route::post('/{bill}/investigations/', [BillInvestigationController::class, 'store'])->name('investigations.store');

            // service management within bills
            Route::get('/{bill}/service/create', [BillServiceController::class, 'create'])->name('services.create');
            Route::get('/service/{billService}/edit', [BillServiceController::class, 'edit'])->name('services.edit');
            Route::put('/service/{billService}/update', [BillServiceController::class, 'update'])->name('services.update');
            Route::post('/{bill}/services/store', [BillServiceController::class, 'store'])->name('services.store');
            Route::delete('/service/{billService}/destroy', [BillServiceController::class, 'destroy'])->name('services.destroy');

        });

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

    Route::get('/system/update', [SystemUpdateController::class, 'index'])
        ->name('system.update');

    Route::post('/system/update/run', [SystemUpdateController::class, 'update'])
        ->name('system.update.run');

    Route::get('/sync/dashboard', [SyncronizationController::class, 'index'])
        ->name('sync.dashboards');
});

// Record Officer Routes - Patient Registration and Visit Recording
Route::middleware(['auth', 'verified', 'role:record'])->prefix('record')->name('record.')->group(function () {
    Route::get('/', [RecordOfficerController::class, 'dashboard'])->name('dashboard');
    
    // Patient Management
    Route::get('patients', [RecordOfficerController::class, 'listPatients'])->name('patients.index');
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
    Route::get('patients/bills/create', [RecordOfficerController::class, 'createBill'])->name('bills.create.form');
    
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
    Route::get('/bills/create/', [AccountantController::class, 'createBill'])->name('bills.create');
    Route::get('/bills/patient-details', [AccountantController::class, 'patientDetailsByHospitalNumber'])->name('bills.patient-details');
    Route::get('/bills/walkin/create', [AccountantController::class, 'createWalkinBill'])->name('bills.create-walkin');
    Route::post('bills', [AccountantController::class, 'storeBill'])->name('bills.store');
    Route::get('bills/{bill}', [AccountantController::class, 'showBill'])->name('bills.show');
    Route::get('bills/{bill}/edit', [AccountantController::class, 'editBill'])->name('bills.edit');
    Route::put('bills/{bill}', [AccountantController::class, 'updateBill'])->name('bills.update');
    Route::delete('bills/{bill}', [AccountantController::class, 'deleteBill'])->name('bills.delete');
    
    // bill paymnt routes
    Route::get('bills/payments/verify', [AccountantController::class, 'verifyBill'])->name('bills.payments.verify');
    Route::post('bills/payments/verify', [AccountantController::class, 'verifyBillNow'])->name('bills.payments.verify-now');
    Route::get('bills/{bill}/payments/create', [AccountantController::class, 'createPaymentForBill'])->name('bills.payments.create');
    Route::post('bills/{bill}/payments', [AccountantController::class, 'storePaymentForBill'])->name('bills.payments.store');

    // Payments Management
    Route::get('payments', [AccountantController::class, 'listPayments'])->name('payments.index');
    Route::get('{patient}/payments/create', [AccountantController::class, 'createPayment'])->name('payments.create');
    Route::post('payments', [AccountantController::class, 'storePayment'])->name('payments.store');
    Route::get('payments/{payment}/receipt', [AccountantController::class, 'paymentReceipt'])->name('payments.receipt');
    Route::get('patients/{patient}/payment-history', [AccountantController::class, 'patientPaymentHistory'])->name('patient-payment-history');
    
    // Insurance Billing
    Route::get('insurance-billing', [AccountantController::class, 'insuranceBilling'])->name('insurance-billing');
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
require __DIR__.'/admin.php';
require __DIR__.'/nurse.php';
require __DIR__.'/midwife.php';
require __DIR__.'/lab.php';
require __DIR__.'/radiology.php';
require __DIR__.'/doctor.php';
require __DIR__.'/patient.php';
require __DIR__.'/pharmacy.php';
require __DIR__.'/department.php';
require __DIR__.'/reports.php';

// Reports Routes (for all authenticated users)
Route::middleware(['auth', 'verified'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [App\Http\Controllers\ReportsController::class, 'index'])->name('index');
    Route::match(['get', 'post'], '/generate', [App\Http\Controllers\ReportsController::class, 'generate'])->name('generate');
});
