<?php

namespace BristolSU\Support\Authorization;

use BristolSU\Support\Authorization\Middleware\CheckActivityFor;
use BristolSU\Support\Authorization\Middleware\CheckAdminActivityFor;
use BristolSU\Support\Authorization\Middleware\CheckLoggedIntoActivityForType;
use BristolSU\Support\Authorization\Middleware\CheckModuleInstanceActive;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthenticationServiceProvider
 * @package BristolSU\Support\Authentication
 */
class AuthorizationServiceProvider extends ServiceProvider
{

    public function register()
    {
    }

    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('participant', CheckLoggedIntoActivityForType::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckActivityFor::class);
        $this->app['router']->pushMiddlewareToGroup('module', CheckModuleInstanceActive::class);
        $this->app['router']->pushMiddlewareToGroup('administrator', CheckAdminActivityFor::class);
    }
    
}