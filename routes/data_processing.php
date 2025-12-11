<?php

use App\Http\Controllers\DataProcessing\DataProcessingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/data-processing/check/{protocol}', [DataProcessingController::class, 'check'])
        ->name('data-processing.check');

    Route::post('/data-processing', [DataProcessingController::class, 'store'])
        ->name('data-processing.store');

    Route::get('/data-processing/{notification}', [DataProcessingController::class, 'show'])
        ->name('data-processing.show');

    Route::put('/data-processing/{notification}', [DataProcessingController::class, 'update'])
        ->name('data-processing.update');

    Route::get('/data-processing/{notification}/download/n', [DataProcessingController::class, 'downloadDocument'])
        ->name('data-processing.download');
});
