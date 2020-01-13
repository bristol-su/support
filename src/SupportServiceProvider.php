<?php

namespace BristolSU\Support;

use BristolSU\Support\Activity\ActivityServiceProvider;
use BristolSU\Support\ActivityInstance\ActivityInstanceServiceProvider;
use BristolSU\Support\Authentication\AuthenticationServiceProvider;
use BristolSU\Support\Action\ActionServiceProvider;
use BristolSU\Support\Authorization\AuthorizationServiceProvider;
use BristolSU\Support\Completion\CompletionConditionServiceProvider;
use BristolSU\Support\Connection\ConnectionServiceProvider;
use BristolSU\Support\Events\EventsServiceProvider;
use BristolSU\Support\Filters\FilterServiceProvider;
use BristolSU\Support\Http\HttpServiceProvider;
use BristolSU\Support\Logic\LogicServiceProvider;
use BristolSU\Support\Module\ModuleFrameworkServiceProvider;
use BristolSU\Support\ModuleInstance\ModuleInstanceServiceProvider;
use BristolSU\Support\Permissions\PermissionServiceProvider;
use BristolSU\Support\User\UserServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class SupportServiceProvider
 * @package BristolSU\Support
 */
class SupportServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $providers = [
        ActionServiceProvider::class,
        ActivityServiceProvider::class,
        EventsServiceProvider::class,
        ActivityInstanceServiceProvider::class,
        AuthorizationServiceProvider::class,
        CompletionConditionServiceProvider::class,
        ConnectionServiceProvider::class,
        FilterServiceProvider::class,
        LogicServiceProvider::class,
        PermissionServiceProvider::class,
        AuthenticationServiceProvider::class,
        ModuleFrameworkServiceProvider::class,
        ModuleInstanceServiceProvider::class,
        UserServiceProvider::class,
        HttpServiceProvider::class
    ];

    public function register()
    {
        $this->registerProviders();
        $this->registerConfig();
        $this->registerMigrations();
        $this->registerViews();
        $this->registerRoutes();
    }
    
    public function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function registerConfig()
    {
        $this->publishes([__DIR__ . '/../config/config.php' => config_path('support.php')], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'support');
    }

    public function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function registerViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/bristolsu'),
        ], 'views');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bristolsu');
    }

    public function registerRoutes()
    {
        Route::middleware(['web', 'module', 'activity'])
            ->namespace('\BristolSU\Support\Http\Controllers')
            ->group(__DIR__ . '/../routes/web.php');
    }

}
