<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\InventoryCategoryController as InventoryCategoryController;
use Modules\Inventory\Http\Controllers\SupplierController;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Product\Http\Controllers\ProductCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticate'])->name('login.authenticate');
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'storeUser'])->name('register.store');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Product Categories
    Route::resource('products/categories', ProductCategoryController::class)->names([
        'index' => 'product.categories.index',
        'create' => 'product.categories.create',
        'store' => 'product.categories.store',
        'edit' => 'product.categories.edit',
        'update' => 'product.categories.update',
        'destroy' => 'product.categories.destroy',
    ]);

    // Inventory
    Route::resource('inventory', InventoryController::class);
    Route::get('inventory/usage', [InventoryController::class, 'usage'])->name('inventory.usage');
    Route::get('inventory/usage/export', [InventoryController::class, 'exportUsage'])->name('inventory.usage.export');
    Route::get('inventory/reports', [InventoryController::class, 'reports'])->name('inventory.reports');

    // Inventory Categories
    Route::resource('inventory/categories', InventoryCategoryController::class)->names([
        'index' => 'inventory.categories.index',
        'create' => 'inventory.categories.create',
        'store' => 'inventory.categories.store',
        'edit' => 'inventory.categories.edit',
        'update' => 'inventory.categories.update',
        'destroy' => 'inventory.categories.destroy',
    ]);

    // Inventory Suppliers
    Route::resource('inventory/suppliers', SupplierController::class)->names([
        'index' => 'inventory.suppliers.index',
        'create' => 'inventory.suppliers.create',
        'store' => 'inventory.suppliers.store',
        'show' => 'inventory.suppliers.show',
        'edit' => 'inventory.suppliers.edit',
        'update' => 'inventory.suppliers.update',
        'destroy' => 'inventory.suppliers.destroy',
    ]);

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
