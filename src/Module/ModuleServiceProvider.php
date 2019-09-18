<?php

namespace BristolSU\Support\Module\ServiceProvider;

use BristolSU\Support\Completion\Contracts\CompletionEventManager;
use BristolSU\Support\Module\Contracts\ModuleManager;
use BristolSU\Support\Permissions\Facade\Permission;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModuleServiceProvider
 *
 * Does not defer
 * Map web and API routes
 * Registers Translations
 * Registers Config
 * Registers Views
 * Registers Factories
 * Loads Migrations
 * Registers Permissions
 *
 *
 * @package BristolSU\Support\Module
 */
abstract class ModuleServiceProvider extends ServiceProvider
{

    protected $defer = false;

    protected $permissions = [];
    
    protected $completionEvents = [];
    
    public function boot()
    {
        $this->registerModule();
        $this->registerPermissions();
        $this->registerCompletionEvents();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrations();
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    public function registerModule()
    {
        $this->app->make(ModuleManager::class)->register($this->alias());
    }

    public function registerPermissions()
    {
        foreach($this->permissions as $ability => $permission) {
            if(!array_key_exists('name', $permission) || !array_key_exists('description', $permission)) {
                throw new \Exception('Name and description of permission required.');
            }
            if(!array_key_exists('admin', $permission)) {
                $permission['admin'] = false;
            }
            Permission::register($ability, $permission['name'], $permission['description'], $this->alias(), $permission['admin']);
        }
    }

    public function registerCompletionEvents()
    {
//        dd("here");
        $manager = $this->app->make(CompletionEventManager::class);
        foreach($this->completionEvents as $class => $meta) {
            if(!array_key_exists('name', $meta) || !array_key_exists('description', $meta)) {
                throw new \Exception('Name and description of event required.');
            }
            $manager->registerEvent($this->alias(), $meta['name'], $class, $meta['description']);
        }
    }
    
    public function registerTranslations()
    {
        $this->loadTranslationsFrom($this->baseDirectory() . '/../resources/lang', $this->alias());
    }

    protected function registerConfig()
    {
        $this->publishes([$this->baseDirectory() . '/../config/config.php' => config_path($this->alias() . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            $this->baseDirectory() . '/../config/config.php', $this->alias()
        );
    }

    public function registerViews()
    {
        $this->publishes([
            $this->baseDirectory() . '/../resources/views' => resource_path('views/vendor/' . $this->alias()),
        ], 'views');

        $this->loadViewsFrom($this->baseDirectory() . '/../resources/views', $this->alias());
    }

    public function registerFactories()
    {
        if (!app()->environment('production')) {
            $this->app->make(Factory::class)->load($this->baseDirectory() . '/../database/factories');
        }
    }

    public function loadMigrations()
    {
        $this->loadMigrationsFrom($this->baseDirectory() . '/../database/migrations');
    }


    public function mapWebRoutes()
    {
        Route::prefix('{activity_slug}/{module_instance_slug}')
            ->middleware(['web', 'module'])
            ->group($this->baseDirectory() . '/../routes/web.php');
    }

    public function mapApiRoutes()
    {
        Route::prefix('{activity_slug}/{module_instance_slug}')
            ->middleware(['api', 'module'])
            ->group($this->baseDirectory() . '/../routes/api.php');
    }

    /**
     * @return string
     */
    abstract public function baseDirectory();

    /**
     * @return string
     */
    abstract public function alias(): string;

}
