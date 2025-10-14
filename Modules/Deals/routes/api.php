<?php

use Illuminate\Support\Facades\Route;
use Modules\Deals\Http\Controllers\DealsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('deals', DealsController::class)->names('deals');
});
