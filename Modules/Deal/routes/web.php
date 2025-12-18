<?php

use Illuminate\Support\Facades\Route;
use Modules\Deal\Http\Controllers\DealController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('deals', DealController::class)->names('deal');
});
