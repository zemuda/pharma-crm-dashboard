<?php

use Illuminate\Support\Facades\Route;
// use Modules\Product\Http\Controllers\ProductController;

Route::middleware(['auth', 'verified'])->group(function () {
    //  Route::resource('products', ProductController::class)->names('product');
    // Route::prefix('ims')->group(function () {
    //     Route::resource('products', ProductController::class)->names('product');
    // });
});
