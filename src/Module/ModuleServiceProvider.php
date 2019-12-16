<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Action\Contracts\Events\EventManager;
use BristolSU\Support\Module\Contracts\ModuleManager;
use BristolSU\Support\ModuleInstance\Contracts\Settings\ModuleSettingsStore;
use BristolSU\Support\Permissions\Facade\Permission;
use Exception;
use FormSchema\Schema\Form;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
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

    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @var array
     */
    protected $permissions = [
    ];

    /**
     * @var array
     */
    protected $events = [];

    /**
     * @var array
     */
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
        $this->registerCommands();
        $this->registerAssets();
        $this->registerRoutes();
        $this->registerSettings();
    }

    public function registerRoutes()
    {
        
        $this->mapParticipantRoutes();
        $this->mapAdminRoutes();
        $this->mapParticipantApiRoutes();
        $this->mapAdminApiRoutes();
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
        Route::prefix('/p/{activity_slug}/{module_instance_slug}/' . $this->alias())
            ->middleware(['web', 'auth', 'verified', 'module', 'activity', 'participant'])
            ->namespace($this->namespace())
            ->group($this->baseDirectory() . '/routes/participant/web.php');
    }

    public function mapAdminRoutes()
    {
        Route::prefix('/a/{activity_slug}/{module_instance_slug}/' . $this->alias())
            ->middleware(['web', 'auth', 'verified', 'module', 'activity', 'administrator'])
            ->namespace($this->namespace())
            ->group($this->baseDirectory() . '/routes/admin/web.php');
    }

    public function mapParticipantApiRoutes()
    {
        Route::prefix('/api/p/{activity_slug}/{module_instance_slug}/' . $this->alias())
            ->middleware(['api', 'auth', 'verified', 'module', 'activity', 'participant'])
            ->namespace($this->namespace())
            ->group($this->baseDirectory() . '/routes/participant/api.php');
    }

    public function mapAdminApiRoutes()
    {
        Route::prefix('/api/a/{activity_slug}/{module_instance_slug}/' . $this->alias())
            ->middleware(['api', 'auth', 'verified', 'module', 'activity', 'administrator'])
            ->namespace($this->namespace())
            ->group($this->baseDirectory() . '/routes/admin/api.php');
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
    
    abstract public function namespace();

    /**
     * @return string
     */
    abstract public function baseDirectory();

    /**
     * @return string
     */
    abstract public function alias(): string;

    abstract public function settings(): Form;
    
    public function registerSettings()
    {
        $this->app->make(ModuleSettingsStore::class)->register($this->alias(), $this->settings());
    }

    /**
     * Register a js file to be loaded on every request.
     * 
     * This is useful for registering custom components. If you want to register a custom component to use in a form,
     * pass in a js file path which registers the component.
     * 
     * @param string $path build path e.g. 'modules/module-alias/js/components.js'
     */
    public function registerGlobalScript(string $path) {
        View::composer('bristolsu::base', function(\Illuminate\View\View $view) use ($path) {
            $scripts = ($view->offsetExists('globalScripts')?$view->offsetGet('globalScripts'):[]);
            $scripts[] = asset($path);
            $view->with('globalScripts', $scripts);
        });
    }

}
