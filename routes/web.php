<?php

use App\Http\Controllers\Crm\ContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::prefix('crm')->group(function () {
        Route::resource('contacts', ContactController::class);
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
