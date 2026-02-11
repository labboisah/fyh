<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TemporaryPermissionController;
use Illuminate\Support\Facades\Route;

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
        Route::resource('users', UserController::class)->except(['show']);
        Route::put('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        
        // Temporary Permissions Management
        Route::get('temporary-permissions', [TemporaryPermissionController::class, 'index'])->name('temporary-permissions.index');
        Route::get('temporary-permissions/create', [TemporaryPermissionController::class, 'create'])->name('temporary-permissions.create');
        Route::post('temporary-permissions', [TemporaryPermissionController::class, 'store'])->name('temporary-permissions.store');
        Route::put('temporary-permissions/{temporaryPermission}/revoke', [TemporaryPermissionController::class, 'revoke'])->name('temporary-permissions.revoke');
        Route::delete('temporary-permissions/{temporaryPermission}', [TemporaryPermissionController::class, 'destroy'])->name('temporary-permissions.destroy');
    });
});

require __DIR__.'/auth.php';
