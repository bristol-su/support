<?php

namespace BristolSU\Support\Authorization;

use BristolSU\Support\Authorization\Middleware\CheckActivityFor;
use BristolSU\Support\Authorization\Middleware\CheckAdminActivityFor;
use BristolSU\Support\Authorization\Middleware\CheckLoggedIntoActivityForType;
use BristolSU\Support\Authorization\Middleware\CheckModuleInstanceActive;
use BristolSU\Support\Authorization\Middleware\LogoutOfExtras;
use Illuminate\Support\ServiceProvider;

/**
 * Authorization Service Provider
 */
class AuthorizationServiceProvider extends ServiceProvider
{

    /**
     * Register the passport service provider. The normal service provider should be disabled.
     */
    public function register()
    {
        $this->app->register(PassportServiceProvider::class);
    }

    /**
     * Push middleware to groups
     */
    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('participant', CheckLoggedIntoActivityForType::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckActivityFor::class);
        $this->app['router']->pushMiddlewareToGroup('module', CheckModuleInstanceActive::class);
        $this->app['router']->pushMiddlewareToGroup('administrator', CheckAdminActivityFor::class);
        $this->app['router']->pushMiddlewareToGroup('nonmodule', LogoutOfExtras::class);
    }
    
}