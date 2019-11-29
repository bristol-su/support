<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\ModuleBuilder as ModuleBuilderContract;
use BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryContract;
use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;
use BristolSU\Support\Module\Contracts\ModuleRepository as ModuleRepositoryContract;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModuleFrameworkServiceProvider
 * @package BristolSU\Support\Module
 */
class ModuleFrameworkServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ModuleContract::class, Module::class);
        $this->app->bind(ModuleBuilderContract::class, ModuleBuilder::class);
        $this->app->bind(ModuleFactoryContract::class, ModuleFactory::class);
        $this->app->singleton(ModuleManagerContract::class, ModuleManager::class);
        $this->app->bind(ModuleRepositoryContract::class, ModuleRepository::class);
    }

    public function boot()
    {
        Route::bind('module', function($alias) {
            return $this->app->make(ModuleRepositoryContract::class)->findByAlias($alias);
        });
        
    }
}