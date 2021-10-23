<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['module', 'activity'])->group(function() {
    Route::middleware(['participant', 'participant-activity', 'participant-module'])->get(
        '/p/{activity_slug}/{module_instance_slug}',
        [\BristolSU\Support\Http\Controllers\ModuleInstanceRedirectController::class, 'index']
    )->name('module.participant');
    Route::middleware('administrator')->get(
        '/a/{activity_slug}/{module_instance_slug}',
        [\BristolSU\Support\Http\Controllers\ModuleInstanceRedirectController::class, 'index']
    )->name('module.admin');
});
