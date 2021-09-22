<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\AuthQuery\Generator;
use BristolSU\Support\Authentication\Contracts\ResourceIdGenerator;
use BristolSU\Support\Authentication\Middleware\CheckAdditionalCredentialsOwnedByUser;
use BristolSU\Support\Authentication\Middleware\HasConfirmedPassword;
use BristolSU\Support\Authentication\Middleware\IsAuthenticated;
use BristolSU\Support\Authentication\Middleware\IsGuest;
use BristolSU\Support\Authentication\Middleware\ThrottleRequests;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ResourceIdGenerator::class, AuthenticationResourceIdGenerator::class);
    }

    public function boot()
    {
        $this->app['router']->prependMiddlewareToGroup('portal-auth', IsAuthenticated::class);
        $this->app['router']->pushMiddlewareToGroup('portal-auth', CheckAdditionalCredentialsOwnedByUser::class);
        $this->app['router']->pushMiddlewareToGroup('portal-guest', IsGuest::class);
        $this->app['router']->pushMiddlewareToGroup('portal-confirmed', HasConfirmedPassword::class);
        $this->app['router']->aliasMiddleware('portal-throttle', ThrottleRequests::class);

        UrlGenerator::macro('getAuthQueryArray', function () {
            return app(Generator::class)->getAuthCredentials()->toArray();
        });
        UrlGenerator::macro('getAuthQueryString', function () {
            return app(Generator::class)->getAuthCredentials()->toQuery();
        });
    }
}
