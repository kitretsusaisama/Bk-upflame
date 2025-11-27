<?php

use Illuminate\Support\Facades\Route;
use Modules\MedCore\Http\Controllers\MedCoreController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('medcores', MedCoreController::class)->names('medcore');
});
