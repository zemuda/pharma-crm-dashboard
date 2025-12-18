<?php

use Illuminate\Support\Facades\Route;
use Modules\Deal\Http\Controllers\DealController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('deals', DealController::class)->names('deal');
});
