<?php

use App\Http\Controllers\DataProcessing\DataProcessingController;
use Illuminate\Support\Facades\Route;

Route::get('/data-processing/{notification}', [DataProcessingController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('data-processing.show');

Route::put('/data-processing/{notification}', [DataProcessingController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('data-processing.update');
