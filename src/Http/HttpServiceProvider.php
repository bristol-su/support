<?php

namespace BristolSU\Support\Http;

use BristolSU\Support\Http\Middleware\InjectJavascriptVariables;
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
        $this->app['router']->pushMiddlewareToGroup('module', InjectJavascriptVariables::class);
    }

}