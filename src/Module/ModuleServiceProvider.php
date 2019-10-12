<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Action\Contracts\Events\EventManager;
use BristolSU\Support\Module\Contracts\ModuleManager;
use BristolSU\Support\Permissions\Facade\Permission;
use Exception;
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

    protected $permissions = [
    ];
    
    protected $events = [];
    
    protected $commands = [];

    public function register()
    {
        
    }

    /**
     * @throws Exception
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerModule();
        $this->registerPermissions();
        $this->registerEvents();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrations();
        $this->mapParticipantRoutes();
        $this->mapAdminRoutes();
        $this->mapApiRoutes();
        $this->registerCommands();
        $this->registerAssets();
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
            Permission::register($this->alias() . '.' . $ability, $permission['name'], $permission['description'], $this->alias(), $permission['admin']);
        }
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function registerEvents()
    {
        $manager = $this->app->make(EventManager::class);
        foreach($this->events as $class => $meta) {
            if(!array_key_exists('name', $meta) || !array_key_exists('description', $meta)) {
                throw new \Exception('Name and description of event required.');
            }
            $manager->registerEvent($this->alias(), $meta['name'], $class, $meta['description']);
        }
    }
    
    public function registerTranslations()
    {
        $this->loadTranslationsFrom($this->baseDirectory() . '/resources/lang', $this->alias());
    }

    protected function registerConfig()
    {
        $this->publishes([$this->baseDirectory() . '/config/config.php' => config_path($this->alias() . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            $this->baseDirectory() . '/config/config.php', $this->alias()
        );
    }

    public function registerViews()
    {
        $this->publishes([
            $this->baseDirectory() . '/resources/views' => resource_path('views/vendor/' . $this->alias()),
        ], 'views');

        $this->loadViewsFrom($this->baseDirectory() . '/resources/views', $this->alias());
    }

    public function registerFactories()
    {
        if (!app()->environment('production')) {
            $this->app->make(Factory::class)->load($this->baseDirectory() . '/database/factories');
        }
    }

    public function loadMigrations()
    {
        $this->loadMigrationsFrom($this->baseDirectory() . '/database/migrations');
    }


    public function mapParticipantRoutes()
    {
        Route::prefix('/p/{activity_slug}/{module_instance_slug}')
            ->middleware(['web', 'module'])
            ->group($this->baseDirectory() . '/routes/participant.php');
    }

    public function mapAdminRoutes()
    {
        Route::prefix('/a/{activity_slug}/{module_instance_slug}')
            ->middleware(['web', 'module'])
            ->group($this->baseDirectory() . '/routes/admin.php');
    }

    public function mapApiRoutes()
    {
        Route::prefix('/api/' . $this->alias() . '/{activity_slug}/{module_instance_slug}')
            ->middleware(['api', 'module'])
            ->group($this->baseDirectory() . '/routes/api.php');
    }

    public function registerCommands()
    {
        if($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    public function registerAssets()
    {
        $this->publishes([$this->baseDirectory() . '/public/modules/' . $this->alias() => public_path('modules/' . $this->alias())]);
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
