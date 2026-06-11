<?php
use App\Livewire\Admin\SyncDashboard;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/sync', SyncDashboard::class)->name('sync.index');
    
});