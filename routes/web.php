<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\InventoryCategoryController as InventoryCategoryController;
use Modules\Inventory\Http\Controllers\SupplierController;
use Modules\Product\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes - combine existing module routes with the new dashboard redirect
Route::middleware('auth')->group(function () {
    // Dashboard - using the module controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
});

// Include Breeze authentication routes
require __DIR__.'/auth.php';
