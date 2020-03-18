<?php

namespace BristolSU\Support\Authorization;

use BristolSU\Support\Authorization\Middleware\CheckActivityEnabled;
use BristolSU\Support\Authorization\Middleware\CheckActivityFor;
use BristolSU\Support\Authorization\Middleware\CheckAdditionalCredentialsOwnedByUser;
use BristolSU\Support\Authorization\Middleware\CheckAdminActivityFor;
use BristolSU\Support\Authorization\Middleware\CheckAdminIsAtLeastUser;
use BristolSU\Support\Authorization\Middleware\CheckDatabaseUserOwnsControlUser;
use BristolSU\Support\Authorization\Middleware\CheckLoggedIntoActivityForType;
use BristolSU\Support\Authorization\Middleware\CheckModuleInstanceActive;
use BristolSU\Support\Authorization\Middleware\CheckModuleInstanceEnabled;
use BristolSU\Support\Authorization\Middleware\LogoutOfExtras;
use Illuminate\Support\ServiceProvider;

/**
 * Authorization Service Provider
 */
class AuthorizationServiceProvider extends ServiceProvider
{

    /**
     * Push middleware to groups
     */
    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('participant', CheckLoggedIntoActivityForType::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckActivityFor::class);
        $this->app['router']->pushMiddlewareToGroup('participant', CheckActivityEnabled::class);
        $this->app['router']->pushMiddlewareToGroup('module', CheckModuleInstanceEnabled::class);
        $this->app['router']->pushMiddlewareToGroup('module', CheckModuleInstanceActive::class);
        $this->app['router']->pushMiddlewareToGroup('administrator', CheckAdminIsAtLeastUser::class);
        $this->app['router']->pushMiddlewareToGroup('administrator', CheckAdminActivityFor::class);
        $this->app['router']->pushMiddlewareToGroup('nonmodule', LogoutOfExtras::class);

        $this->app['router']->pushMiddlewareToGroup('activity', CheckDatabaseUserOwnsControlUser::class);
        $this->app['router']->pushMiddlewareToGroup('activity', CheckAdditionalCredentialsOwnedByUser::class);
    }
    
}