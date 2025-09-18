<?php

use App\Http\Controllers\Notifications\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/notifications/{notification}', [NotificationController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('notifications.show');
