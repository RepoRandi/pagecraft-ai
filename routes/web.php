<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [SalesPageController::class, 'index'])->name('dashboard');

    Route::post('/sales-pages', [SalesPageController::class, 'store'])->name('sales-pages.store');
    Route::get('/sales-pages/{salesPage}', [SalesPageController::class, 'show'])->name('sales-pages.show');
    Route::delete('/sales-pages/{salesPage}', [SalesPageController::class, 'destroy'])->name('sales-pages.destroy');
    Route::get('/sales-pages/{salesPage}/export', [SalesPageController::class, 'exportHtml'])->name('sales-pages.export');

    Route::patch('/sales-pages/{salesPage}/regenerate-section', [SalesPageController::class, 'regenerateSection'])
        ->name('sales-pages.regenerate-section');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
