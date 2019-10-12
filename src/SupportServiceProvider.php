<?php

namespace BristolSU\Support;

use BristolSU\Support\Activity\ActivityServiceProvider;
use BristolSU\Support\Authentication\AuthenticationServiceProvider;
use BristolSU\Support\Action\ActionServiceProvider;
use BristolSU\Support\Control\ControlClientServiceProvider;
use BristolSU\Support\Control\ControlServiceProvider;
use BristolSU\Support\DataPlatform\UnionCloudServiceProvider;
use BristolSU\Support\Filters\FilterServiceProvider;
use BristolSU\Support\Http\HttpServiceProvider;
use BristolSU\Support\Logic\LogicServiceProvider;
use BristolSU\Support\Module\ModuleFrameworkServiceProvider;
use BristolSU\Support\ModuleInstance\ModuleInstanceServiceProvider;
use BristolSU\Support\Permissions\PermissionServiceProvider;
use BristolSU\Support\User\UserServiceProvider;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{

    protected $providers = [
        ActionServiceProvider::class,
        ActivityServiceProvider::class,
        ControlClientServiceProvider::class,
        ControlServiceProvider::class,
        UnionCloudServiceProvider::class,
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
    }
    
    public function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function registerConfig()
    {
        $this->publishes([__DIR__ . '/../config/config.php' => config_path('support')], 'config');
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

}
