<?php

use Illuminate\Support\Facades\Route;
use Modules\GeneralData\Http\Controllers\GeneralDataController;
use Modules\GeneralData\Http\Controllers\UnitOfMeasureController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('generaldata', GeneralDataController::class)->names('generaldata');
    Route::resource('units', UnitOfMeasureController::class)->names('generaldata.units');
});
