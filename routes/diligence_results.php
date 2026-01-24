<?php

use App\Http\Controllers\Settings\DiligenceResultController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('settings')->group(function () {
    Route::get('diligence-results', [DiligenceResultController::class, 'index'])
        ->name('diligence-results.index');
    Route::post('diligence-results', [DiligenceResultController::class, 'store'])
        ->name('diligence-results.store');
    Route::patch('diligence-results/{result}', [DiligenceResultController::class, 'update'])
        ->name('diligence-results.update');
    Route::patch('diligence-results/{result}/toggle', [DiligenceResultController::class, 'toggleActive'])
        ->name('diligence-results.toggle');
    Route::patch('diligence-results/{result}/restore', [DiligenceResultController::class, 'restore'])
        ->name('diligence-results.restore');
});
