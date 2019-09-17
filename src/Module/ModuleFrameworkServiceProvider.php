<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;
use BristolSU\Support\Module\Contracts\ModuleRepository as ModuleRepositoryContract;
use Illuminate\Support\ServiceProvider;

class ModuleFrameworkServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ModuleContract::class, Module::class);
        $this->app->bind(ModuleRepositoryContract::class, ModuleRepository::class);
        $this->app->bind(ModuleManagerContract::class, ModuleManager::class);
    }
}