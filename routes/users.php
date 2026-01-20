<?php

use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');
    });

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/{user}/change-password', [UserController::class, 'changePasswordView'])->name('users.change-password');
    Route::put('/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');
});
