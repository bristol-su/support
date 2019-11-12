<?php

namespace BristolSU\Support\Control;

use BristolSU\Support\Control\Client\CachedClientDecorator;
use BristolSU\Support\Control\Client\GuzzleClient;
use BristolSU\Support\Control\Contracts\Client\Client as ClientContract;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Class ControlClientServiceProvider
 * @package BristolSU\Support\Control
 */
class ControlClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Client
        $this->app->singleton(ClientContract::class, GuzzleClient::class);
        $this->app->extend(ClientContract::class, function(ClientContract $client) {
            return new CachedClientDecorator($client, $this->app->make(Repository::class));
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            ClientContract::class,
        ];
    }

}
