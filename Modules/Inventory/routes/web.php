<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\InventoryController;

Route::middleware(['auth'])->group(function () {
    // Inventory Items
    Route::resource('inventory', InventoryController::class);
    
    // Inventory Usage History
    Route::get('inventory/usage', [InventoryController::class, 'usage'])->name('inventory.usage');
    Route::get('inventory/usage/export', [InventoryController::class, 'exportUsage'])->name('inventory.usage.export');
    
    // Inventory Reports
    Route::get('inventory/reports', [InventoryController::class, 'reports'])->name('inventory.reports');
});
