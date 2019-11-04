<?php

namespace BristolSU\Support\Http;

use BristolSU\Support\Http\Middleware\InjectJavascriptVariables;
use Illuminate\Support\ServiceProvider;

/**
 * Class HttpServiceProvider
 * @package BristolSU\Support\Http
 */
class HttpServiceProvider extends ServiceProvider
{

    public function register()
    {

    }

    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('module', InjectJavascriptVariables::class);
    }

}