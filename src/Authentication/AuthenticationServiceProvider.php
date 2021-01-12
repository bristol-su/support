<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\AuthQuery\Generator;
use BristolSU\Support\Authentication\Contracts\ResourceIdGenerator;
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
        UrlGenerator::macro('getAuthQueryArray', function() {
            return app(Generator::class)->getAuthCredentials()->toArray();
        });
        UrlGenerator::macro('getAuthQueryString', function() {
            return app(Generator::class)->getAuthCredentials()->toString();
        });
    }

}
