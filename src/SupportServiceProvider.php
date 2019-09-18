<?php

namespace BristolSU\Support;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\ActivityServiceProvider;
use BristolSU\Support\Authentication\AuthenticationServiceProvider;
use BristolSU\Support\Completion\CompletionServiceProvider;
use BristolSU\Support\Control\ControlClientServiceProvider;
use BristolSU\Support\Control\ControlServiceProvider;
use BristolSU\Support\DataPlatform\UnionCloudServiceProvider;
use BristolSU\Support\EventStore\EventStoreServiceProvider;
use BristolSU\Support\Filters\Contracts\FilterManager;
use BristolSU\Support\Filters\Filters\GroupTagged;
use BristolSU\Support\Filters\Filters\UserEmailIs;
use BristolSU\Support\Filters\FilterServiceProvider;
use BristolSU\Support\GoogleDrive\GoogleDriveServiceProvider;
use BristolSU\Support\Logic\LogicServiceProvider;
use BristolSU\Support\Module\Contracts\ModuleRepository;
use BristolSU\Support\Module\ModuleFrameworkServiceProvider;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSettings;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceServiceProvider;
use BristolSU\Support\Permissions\Facade\Permission;
use BristolSU\Support\Permissions\Facade\PermissionTester;
use BristolSU\Support\Permissions\Models\ModuleInstancePermissions;
use BristolSU\Support\Permissions\PermissionServiceProvider;
use BristolSU\Support\Permissions\Testers\CheckPermissionExists;
use BristolSU\Support\Permissions\Testers\ModuleInstanceAdminPermissions;
use BristolSU\Support\Permissions\Testers\ModuleInstanceUserPermissions;
use BristolSU\Support\Permissions\Testers\SystemLogicPermission;
use BristolSU\Support\Permissions\Testers\SystemUserPermission;
use BristolSU\Support\User\UserServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{

    protected $providers = [
        ActivityServiceProvider::class,
        CompletionServiceProvider::class,
        ControlClientServiceProvider::class,
        ControlServiceProvider::class,
        UnionCloudServiceProvider::class,
        EventStoreServiceProvider::class,
        FilterServiceProvider::class,
        GoogleDriveServiceProvider::class,
        LogicServiceProvider::class,
        PermissionServiceProvider::class,
        AuthenticationServiceProvider::class,
        ModuleFrameworkServiceProvider::class,
        ModuleInstanceServiceProvider::class,
        UserServiceProvider::class,
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

    public function boot()
    {
        Route::bind('module_instance_setting', function ($id) {
            return ModuleInstanceSettings::findOrFail($id);
        });

        Route::bind('module_instance_permission', function ($id) {
            return ModuleInstancePermissions::findOrFail($id);
        });

        Route::bind('module', function ($alias) {
            return $this->app[ModuleRepository::class]->findByAlias($alias);
        });

        Route::bind('activity_slug', function ($slug) {
            return Activity::where(['slug' => $slug])->firstOrFail();
        });

        Route::bind('module_instance_slug', function ($slug, $route) {
            $activity = $route->parameter('activity_slug');
            return ModuleInstance::where('slug', $slug)
                ->whereHas('activity', function ($query) use ($activity) {
                    $query->where('slug', $activity->slug);
                })
                ->firstOrFail();
        });

    }
}
