<?php

use Illuminate\Support\Facades\Route;
use Modules\Calls\Http\Controllers\CallsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('calls', CallsController::class)->names('calls');
});
