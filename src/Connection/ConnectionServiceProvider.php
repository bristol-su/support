<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Connection\Client\CachedClientDecorator;
use BristolSU\Support\Connection\Client\GuzzleClient;
use BristolSU\Support\Connection\Contracts\Client\Client;
use BristolSU\Support\Connection\Contracts\ConnectorFactory as ConnectorFactoryContract;
use BristolSU\Support\Connection\Contracts\ConnectorRepository as ConnectorRepositoryContract;
use BristolSU\Support\Connection\Contracts\ConnectorStore as ConnectorStoreContract;
use BristolSU\Support\Connection\Contracts\ConnectionRepository as ConnectionRepositoryContract;
use BristolSU\Support\Connection\Contracts\ServiceRequest as ServiceRequestContract;
use GuzzleHttp\ClientInterface;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Connection Service Provider
 */
class ConnectionServiceProvider extends ServiceProvider
{

    /**
     * Register
     * 
     * - Register the base client as a singleton
     * - Set up request caching
     * - Bind interfaces to concretions
     * - Set up the connector store and service request as singletons
     */
    public function register()
    {
        $this->app->singleton(Client::class, GuzzleClient::class);
        $this->app->extend(Client::class, function($service) {
            return new CachedClientDecorator($service, app(Repository::class));
        });
        
        $this->app->bind(ClientInterface::class, \GuzzleHttp\Client::class);

        $this->app->singleton(ConnectorStoreContract::class, ConnectorStore::class);
        $this->app->bind(ConnectorRepositoryContract::class, ConnectorRepository::class);
        $this->app->bind(ConnectionRepositoryContract::class, ConnectionRepository::class);
        $this->app->bind(ConnectorFactoryContract::class, ConnectorFactory::class);
        $this->app->singleton(ServiceRequestContract::class, ServiceRequest::class);
    }

    /**
     * Boot
     * 
     * - Set up route model binding
     */
    public function boot()
    {
        Route::bind('connection_id', function($id) {
            return Connection::findOrFail($id);
        });
    }

}
