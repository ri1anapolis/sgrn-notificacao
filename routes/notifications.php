<?php

use App\Http\Controllers\Notifications\NotificationController;
use App\Http\Controllers\Notifications\NotificationDiligenceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('notifications/{notification}')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'show'])
            ->name('notifications.show');

        Route::get('/diligence/{address}', [NotificationDiligenceController::class, 'show'])
            ->name('notifications.diligence.show');

        Route::post('/diligence/{address}', [NotificationDiligenceController::class, 'store'])
            ->name('notifications.diligence.store');

        Route::put('/diligence/{address}/{diligence}', [NotificationDiligenceController::class, 'update'])
            ->name('notifications.diligence.update');
    });
