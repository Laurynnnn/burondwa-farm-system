<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

Route::prefix('products')->name('product.')->middleware(['web', 'auth'])->group(function () {
    // ... existing product routes ...
    Route::get('stock/sheet', [ProductController::class, 'stockSheet'])->name('stock.sheet');
    Route::get('stock/add', [ProductController::class, 'stockEntryForm'])->name('stock.add');
    Route::post('stock/add', [ProductController::class, 'storeStockEntry'])->name('stock.add');
});
