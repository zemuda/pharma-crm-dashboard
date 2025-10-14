<?php

use Illuminate\Support\Facades\Route;
use Modules\Calls\Http\Controllers\CallsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('calls', CallsController::class)->names('calls');
});
