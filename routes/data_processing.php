<?php

use App\Http\Controllers\DataProcessing\DataProcessingController;
use Illuminate\Support\Facades\Route;

Route::get('/data-processing/{protocol}', [DataProcessingController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('data-processing.show');
