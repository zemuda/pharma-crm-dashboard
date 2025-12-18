<?php

use Illuminate\Support\Facades\Route;
use Modules\Pipeline\Http\Controllers\PipelineController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pipelines', PipelineController::class)->names('pipeline');
});
