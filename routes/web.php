<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['participant, administrator, module, activity, participant-activity, participant-module'])->group(function() {
    Route::get('/{a_or_p}/{activity_slug}/{module_instance_slug}', [\BristolSU\Support\Http\Controllers\ModuleInstanceRedirectController::class, 'index'])
        ->where('a_or_p', '(a | p)')
        ->name('module');
});


