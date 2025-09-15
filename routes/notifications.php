<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/notifications/{notification}', [NotificationController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('notifications.show');
