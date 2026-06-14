<?php
use App\Http\Controllers\Admin\PaymentController;
use App\Livewire\Admin\SyncDashboard;
use App\Livewire\Admin\PaymentManagement;

Route::middleware(['auth', 'verified', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/sync', SyncDashboard::class)->name('sync.index');
    Route::get('/payments', PaymentManagement::class)->name('payments.index');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    
});
