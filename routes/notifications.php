<?php

use App\Http\Controllers\Notifications\NotificationController;
use App\Http\Controllers\Notifications\NotificationDiligenceController;
use Illuminate\Support\Facades\Route;
use App\Models\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Route as RouteObject;

Route::bind('notification', function ($value, RouteObject $route) {

    $routeName = $route->getName();

    $notificationErrorRoutes = [
        'notifications.show',
        'notifications.diligence.show',
        'notifications.diligence.store'
    ];

    if (in_array($routeName, $notificationErrorRoutes)) {

        $notification = Notification::where('protocol', $value)->first();

        if (!$notification) {
            throw ValidationException::withMessages([
                'geral' => 'Protocolo número ' . $value . ' não encontrado.'
            ]);
        }

        return $notification;
    }

    $dataProcessingRoutes = [
        'data-processing.show',
        'data-processing.update'
    ];

    if (in_array($routeName, $dataProcessingRoutes)) {
        $notification = Notification::firstOrCreate(
            ['protocol' => $value],
        );

        return $notification;
    }

    return Notification::where('protocol', $value)->firstOrFail();
});

Route::middleware(['auth', 'verified'])
    ->prefix('notifications/{notification}')
    ->where(['notification' => '[0-9]+'])
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
