<?php

use Illuminate\Support\Facades\Route;
use Modules\Pipeline\Http\Controllers\PipelineController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('pipelines', PipelineController::class)->names('pipeline');
});
