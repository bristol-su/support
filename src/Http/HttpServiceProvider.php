<?php

namespace BristolSU\Support\Http;

use BristolSU\Support\Http\View\InjectJavascriptVariables;
use BristolSU\Support\Http\View\InjectOldInput;
use BristolSU\Support\Http\View\InjectSiteSettings;
use BristolSU\Support\Http\View\InjectValidationErrors;
use BristolSU\Support\Http\View\Router\InjectNamedRoutes;
use BristolSU\Support\Http\View\Router\NamedRouteRetriever;
use BristolSU\Support\Http\View\Router\NamedRouteRetrieverCache;
use BristolSU\Support\Http\View\Router\NamedRouteRetrieverInterface;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Http Service Provider
 */
class HttpServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(NamedRouteRetrieverInterface::class, NamedRouteRetriever::class);
        $this->app->extend(NamedRouteRetrieverInterface::class, function(NamedRouteRetrieverInterface $service, Container $app) {
            return new NamedRouteRetrieverCache($service, $app->make(Cache::class));
        });
    }

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
