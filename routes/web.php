<?php

use App\Http\Controllers\Crm\ContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\MeasurementUnitController;
// ims (Inventory Management System)
use Modules\Product\Http\Controllers\MedicineController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\TherapeuticClassController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::prefix('crm')->group(function () {
        Route::resource('contacts', ContactController::class)->names('crm.contact');
    });

    Route::prefix('ims')->group(function () {
        Route::resource('products', ProductController::class)->names('ims.product');
        Route::resource('medicines', MedicineController::class)->names('ims.medicine');
        Route::resource('therapeutic-classes', TherapeuticClassController::class)->names('ims.therapeutic-class');
    });

    Route::prefix('measurement-units')->group(function () {
        Route::get('/units', [MeasurementUnitController::class, 'index']);
        Route::post('/convert', [MeasurementUnitController::class, 'convert']);
        Route::post('/check-compatibility', [MeasurementUnitController::class, 'checkCompatibility']);
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
