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

    Route::get('/data-processing/{notification}/notification/download', [DataProcessingController::class, 'downloadNotification'])
        ->name('data-processing.notification.download');

    Route::get('/data-processing/{notification}/envelope/download', [DataProcessingController::class, 'downloadEnvelope'])
        ->name('data-processing.envelope.download');

    Route::get('/data-processing/{notification}/certificate/download', [DataProcessingController::class, 'downloadCertificate'])
        ->name('data-processing.certificate.download');

    Route::get('/data-processing/{notification}/adverse-possession-edital/download', [DataProcessingController::class, 'downloadAdversePossessionEdital'])
        ->name('data-processing.adverse-possession-edital.download');
});
