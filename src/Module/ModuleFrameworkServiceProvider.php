<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Module\Contracts\ModuleBuilder as ModuleBuilderContract;
use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryContract;
use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;
use BristolSU\Support\Module\Contracts\ModuleRepository as ModuleRepositoryContract;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Module Framework service provider.
 */
class ModuleFrameworkServiceProvider extends ServiceProvider
{
    /**
     * Register.
     *
     * - Bind implementations to interfaces
     * - Set up the module manager as a singleton
     */
    public function register()
    {
        $this->app->bind(ModuleContract::class, Module::class);
        $this->app->bind(ModuleBuilderContract::class, ModuleBuilder::class);
        $this->app->bind(ModuleFactoryContract::class, ModuleFactory::class);
        $this->app->singleton(ModuleManagerContract::class, ModuleManager::class);
        $this->app->bind(ModuleRepositoryContract::class, ModuleRepository::class);
    }

    /**
     * Boot.
     *
     * - Set up route model binding for a module
     */
    public function boot()
    {
        Route::bind('module', function ($alias) {
            return $this->app->make(ModuleRepositoryContract::class)->findByAlias($alias);
        });
    }
}
