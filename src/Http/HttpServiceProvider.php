<?php

namespace BristolSU\Support\Http;

use BristolSU\Support\Activity\Middleware\InjectActivityInstance;
use BristolSU\Support\Http\Middleware\InjectJavascriptVariables;
use Illuminate\Support\ServiceProvider;
use BristolSU\Support\Activity\Contracts\Repository as ActivityRepositoryContract;
use BristolSU\Support\Activity\Repository as ActivityRepository;

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