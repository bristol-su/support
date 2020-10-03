<?php

namespace BristolSU\Support\Http;

use BristolSU\Support\Http\View\InjectJavascriptVariables;
use BristolSU\Support\Http\View\InjectOldInput;
use BristolSU\Support\Http\View\InjectValidationErrors;
use BristolSU\Support\Http\View\Router\InjectNamedRoutes;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Http Service Provider
 */
class HttpServiceProvider extends ServiceProvider
{

    /**
     * Boot
     *
     * - Push the JS middleware to the module middleware group
     */
    public function boot()
    {
        View::composer(['bristolsu::base'], InjectJavascriptVariables::class);
        View::composer(['bristolsu::base'], InjectValidationErrors::class);
        View::composer(['bristolsu::base'], InjectOldInput::class);
        View::composer(['bristolsu::base'], InjectNamedRoutes::class);
    }

}
