<?php

use App\Http\Controllers\Settings\DocumentTemplateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('templates', [DocumentTemplateController::class, 'index'])->name('templates.index');
    Route::get('templates/{template}/download', [DocumentTemplateController::class, 'download'])->name('templates.download');
    Route::get('templates/{template}/download-original', [DocumentTemplateController::class, 'downloadOriginal'])->name('templates.download-original');
    Route::post('templates/{template}', [DocumentTemplateController::class, 'update'])->name('templates.update');
    Route::delete('templates/{template}/restore', [DocumentTemplateController::class, 'restore'])->name('templates.restore');
});
