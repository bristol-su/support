<?php

namespace BristolSU\Support\Authorization;

use BristolSU\Support\Authorization\Middleware\CheckActivityEnabled;
use BristolSU\Support\Authorization\Middleware\CheckActivityFor;
use BristolSU\Support\Authorization\Middleware\CheckAdminActivityFor;
use BristolSU\Support\Authorization\Middleware\CheckLoggedIntoActivityForType;
use BristolSU\Support\Authorization\Middleware\CheckModuleInstanceActive;
use BristolSU\Support\Authorization\Middleware\CheckModuleInstanceEnabled;
use Illuminate\Support\ServiceProvider;

/**
 * Authorization Service Provider.
 */
class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Push middleware to groups.
     */
    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('participant', CheckLoggedIntoActivityForType::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckActivityFor::class);
        $this->app['router']->pushMiddlewareToGroup('participant-activity', CheckActivityEnabled::class);
        $this->app['router']->pushMiddlewareToGroup('module', CheckModuleInstanceEnabled::class);
        $this->app['router']->pushMiddlewareToGroup('participant-module', CheckModuleInstanceActive::class);
        $this->app['router']->pushMiddlewareToGroup('administrator', CheckAdminActivityFor::class);
    }
}
