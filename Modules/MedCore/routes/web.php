<?php

use Illuminate\Support\Facades\Route;
use Modules\MedCore\Http\Controllers\MedCoreController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('medcores', MedCoreController::class)->names('medcore');
});
