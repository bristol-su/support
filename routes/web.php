<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['module', 'activity', 'participant-activity', 'participant-module'])->group(function() {
    Route::middleware('participant')->get(
        '/p/{activity_slug}/{module_instance_slug}',
        [\BristolSU\Support\Http\Controllers\ModuleInstanceRedirectController::class, 'index']
    )->name('module.participant');
    Route::middleware('administrator')->get(
        '/a/{activity_slug}/{module_instance_slug}',
        [\BristolSU\Support\Http\Controllers\ModuleInstanceRedirectController::class, 'index']
    )->name('module.admin');
});
