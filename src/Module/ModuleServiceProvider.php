<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Action\Facade\ActionManager;
use BristolSU\Support\Events\Contracts\EventManager;
use BristolSU\Support\Module\Contracts\ModuleManager;
use BristolSU\Support\ModuleInstance\Contracts\Scheduler\CommandStore;
use BristolSU\Support\ModuleInstance\Contracts\Settings\ModuleSettingsStore;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\Permissions\Facade\Permission;
use Exception;
use FormSchema\Schema\Form;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Module Service Provider.
 *
 * Extend this service provider to register a module
 */
abstract class ModuleServiceProvider extends ServiceProvider
{

    /**
     * Should the module registration be deferred?
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Permissions to register.
     *
     * All permissions your module uses should be registered here (or directly) so the framework knows about them.
     * Permissions should be entered in the following form
     * [
     *      'ability' => [
     *          'name' => 'Name of the permission',
     *          'description' => 'Description',
     *          'admin' => false
     *      ],
     *      'admin.ability' => [
     *          'name' => 'Name of the permission',
     *          'description' => 'Description',
     *          'admin' => true
     *      ], ...
     * ]
     * Do not add the module alias to the start of the permission. This will be done automatically.
     *
     * @var array
     */
    protected $permissions = [
    ];

    /**
     * Register any events the module fires.
     *
     * Register events in the following form. All events fired by your module should be registered.
     * [
     *      EventClass::class => [
     *          'name' => 'Name of the event',
     *          'description' => 'Description of the event'
     *      ], ...
     * ]
     *
     * @var array
     */
    protected $events = [];

    /**
     * Commands the module registers.
     *
     * An array of command class names
     * [
     *      CommandOne::class,
     *      CommandTwo::class
     * ]
     * See the laravel documentation for more information about commands.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Any listeners which should be triggered on specific settings being changed.
     *
     * Register an array of listeners which extend the SettingListener abstract class.
     * [
     *      TitleSettingListener::class,
     *      BackgroundColourSettingListener::class
     * ]
     *
     * @var array
     */
    protected $settingListeners = [];

    /**
     * Commands to run at scheduled times.
     *
     * Register any scheduled commands here. The commands must have been registered in the $commands array already,
     * but putting them here allows us to run commands at specific times in the background.
     * Commands should be registered with the index as the class name, and the value as a cron string representing
     * when to run the command.
     *
     * [
     *      CommandOne::class => '* * * * *', // Run every minute
     *      CommandTwo::class => '* /5 * * * *' // Run every five minutes
     * ]
     *
     * @var array
     */
    protected $scheduledCommands = [];

    /**
     * Boot
     *
     * - Register translations
     * - Register configuration
     * - Register the module
     * - Register module permissions
     * - Register module events
     * - Register views
     * - Register factories
     * - Register migrations to use
     * - Register commands
     * - Register assets
     * - Register routes
     * - Register settings
     * - Register setting listeners
     * - Register scheduled commands
     *
     * @throws BindingResolutionException
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
        $this->registerSettingListeners();
        $this->registerScheduledCommands();
    }

    /**
     * Register setting listeners
     *
     * Register listeners to be fired when settings are changed.
     */
    public function registerSettingListeners()
    {
        foreach ($this->settingListeners as $listener) {
            Event::listen('eloquent.*: '.ModuleInstanceSetting::class, $listener);
        }
    }

    /**
     * Register scheduled commands
     *
     * Register commands to run at scheduled times.
     *
     * @throws BindingResolutionException
     */
    public function registerScheduledCommands()
    {
        $commandStore = $this->app->make(CommandStore::class);
        foreach ($this->scheduledCommands as $command => $cron) {
            $commandStore->schedule($this->alias(), $command, $cron);
        }
    }

    /**
     * Register routes for the module
     */
    public function registerRoutes()
    {

        $this->mapParticipantRoutes();
        $this->mapAdminRoutes();
        $this->mapParticipantApiRoutes();
        $this->mapAdminApiRoutes();
    }

    /**
     * Register the module
     *
     * @throws BindingResolutionException
     */
    public function registerModule()
    {
        $this->app->make(ModuleManager::class)->register($this->alias());
    }

    /**
     * Register permissions the module uses
     *
     * @throws Exception
     */
    public function registerPermissions()
    {
        foreach ($this->permissions as $ability => $permission) {
            if (!array_key_exists('name', $permission) || !array_key_exists('description', $permission)) {
                throw new \Exception('Name and description of permission required.');
            }
            if (!array_key_exists('admin', $permission)) {
                $permission['admin'] = false;
            }
            Permission::register($this->alias().'.'.$ability, $permission['name'], $permission['description'], $this->alias(), $permission['admin']);
        }
    }

    /**
     * Register events the module fires
     *
     * @throws BindingResolutionException
     */
    public function registerEvents()
    {
        $manager = $this->app->make(EventManager::class);
        foreach ($this->events as $class => $meta) {
            if (!array_key_exists('name', $meta) || !array_key_exists('description', $meta)) {
                throw new \Exception('Name and description of event required.');
            }
            $manager->registerEvent($this->alias(), $meta['name'], $class, $meta['description']);
        }
    }

    /**
     * Register translations
     */
    public function registerTranslations()
    {
        $this->loadTranslationsFrom($this->baseDirectory().'/resources/lang', $this->alias());
    }

    /**
     * Register config
     */
    protected function registerConfig()
    {
        $this->publishes([$this->baseDirectory().'/config/config.php' => config_path($this->alias().'.php'),
        ], ['module', 'module-config', 'config']);
        $this->mergeConfigFrom(
            $this->baseDirectory().'/config/config.php', $this->alias()
        );
    }

    /**
     * Register views
     */
    public function registerViews()
    {
        $this->publishes([
            $this->baseDirectory().'/resources/views' => resource_path('views/vendor/'.$this->alias()),
        ], ['module', 'module-views', 'views']);

        $this->loadViewsFrom($this->baseDirectory().'/resources/views', $this->alias());
    }

    /**
     * Register factories in a non-production environment
     *
     * @throws BindingResolutionException
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
            $this->app->make(Factory::class)->load($this->baseDirectory().'/database/factories');
        }
    }

    /**
     * Load migrations to be used
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom($this->baseDirectory().'/database/migrations');
    }

    /**
     * Load participant routes
     */
    public function mapParticipantRoutes()
    {
        Route::prefix('/p/{activity_slug}/{module_instance_slug}/'.$this->alias())
            ->middleware(['web', 'auth', 'verified', 'module', 'activity', 'participant', 'moduleparticipant'])
            ->namespace($this->namespace())
            ->group($this->baseDirectory().'/routes/participant/web.php');
    }

    /**
     * Load admin routes
     */
    public function mapAdminRoutes()
    {
        Route::prefix('/a/{activity_slug}/{module_instance_slug}/'.$this->alias())
            ->middleware(['web', 'auth', 'verified', 'module', 'activity', 'administrator'])
            ->namespace($this->namespace())
            ->group($this->baseDirectory().'/routes/admin/web.php');
    }

    /**
     * Load participant API routes
     */
    public function mapParticipantApiRoutes()
    {
        Route::prefix('/api/p/{activity_slug}/{module_instance_slug}/'.$this->alias())
            ->middleware(['api', 'auth', 'verified', 'module', 'activity', 'participant', 'moduleparticipant'])
            ->namespace($this->namespace())
            ->group($this->baseDirectory().'/routes/participant/api.php');
    }

    /**
     * Load admin API routes
     */
    public function mapAdminApiRoutes()
    {
        Route::prefix('/api/a/{activity_slug}/{module_instance_slug}/'.$this->alias())
            ->middleware(['api', 'auth', 'verified', 'module', 'activity', 'administrator'])
            ->namespace($this->namespace())
            ->group($this->baseDirectory().'/routes/admin/api.php');
    }

    /**
     * Register commmands to make available
     */
    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * Register assets
     */
    public function registerAssets()
    {
        $this->publishes([
          $this->baseDirectory().'/public/modules/'.$this->alias() => public_path('modules/'.$this->alias())
        ], ['module', 'module-assets', 'assets']);
    }

    /**
     * Base namespace of the controllers.
     *
     * Routes will use this namespace to prevent you from having to give the fully qualified name of the controller in the
     * routes file. Will look something like \App\Http\Controllers
     *
     * @return string
     */
    abstract public function namespace();

    /**
     * Return the path to the base directory (where the composer.json file is).
     *
     * Used for registering files, will often look something like __DIR__ . '/..'
     *
     * @return string
     */
    abstract public function baseDirectory();

    /**
     * Return the alias of the module
     *
     * @return string Module alias
     */
    abstract public function alias(): string;

    /**
     * Return settings required by the module
     *
     * @return Form
     */
    abstract public function settings(): Form;

    /**
     * Register settings for the module
     *
     * @throws BindingResolutionException
     */
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
            $scripts = ($view->offsetExists('globalScripts') ? $view->offsetGet('globalScripts') : []);
            $scripts[] = asset($path);
            $view->with('globalScripts', $scripts);
        });
    }

    /**
     * Register an action with the portal
     *
     * @param string $class The fully qualified class name of the action class
     * @param string $name A name for your action
     * @param string $description A longer description for your action
     */
    public function registerAction(string $class, string $name, string $description): void
    {
        ActionManager::registerAction($class, $name, $description);
    }
}
