<?php

use Illuminate\Support\Facades\Route;
use Modules\GeneralData\Http\Controllers\GeneralDataController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('generaldatas', GeneralDataController::class)->names('generaldata');
});
