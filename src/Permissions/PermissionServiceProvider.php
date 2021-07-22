<?php


namespace BristolSU\Support\Permissions;

use BristolSU\Support\Permissions\Contracts\Models\Permission as PermissionContract;
use BristolSU\Support\Permissions\Contracts\PermissionRepository as PermissionRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionStore as PermissionStoreContract;
use BristolSU\Support\Permissions\Contracts\PermissionTester as PermissionTesterContract;
use BristolSU\Support\Permissions\Facade\PermissionTester as PermissionTesterFacade;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Testers\ModuleInstanceGroupOverridePermission;
use BristolSU\Support\Permissions\Testers\ModuleInstancePermissions;
use BristolSU\Support\Permissions\Testers\ModuleInstanceRoleOverridePermission;
use BristolSU\Support\Permissions\Testers\ModuleInstanceUserOverridePermission;
use BristolSU\Support\Permissions\Testers\SystemUserPermission;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

/**
 * Permission Service Provider.
 */
class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register.
     *
     * - Bind the permission model and the permission repository
     * - Set the permission store as a singleton
     * - Set the permission tester as a singleton
     */
    public function register()
    {
        $this->app->bind(PermissionContract::class, Permission::class);
        $this->app->bind(PermissionRepositoryContract::class, PermissionRepository::class);
        $this->app->singleton(PermissionStoreContract::class, PermissionStore::class);
        $this->app->singleton(PermissionTesterContract::class, PermissionTester::class);
    }

    /**
     * Boot.
     *
     * - Register provided permission testers.
     * - Set the Laravel gate callback to use the Permission Tester. This allows us to still use laravel functions to check permissions.
     * - Route model binding for a module instance permission
     * - route model binding for a permission
     * - Route model binding for a site (global) permission specifically
     *
     * @throws BindingResolutionException
     */
    public function boot()
    {
        // Check system permissions
        PermissionTesterFacade::register($this->app->make(SystemUserPermission::class));
        // Check module instance override permissions
        PermissionTesterFacade::register($this->app->make(ModuleInstanceUserOverridePermission::class));
        PermissionTesterFacade::register($this->app->make(ModuleInstanceGroupOverridePermission::class));
        PermissionTesterFacade::register($this->app->make(ModuleInstanceRoleOverridePermission::class));
        // Check default module instance permissions
        PermissionTesterFacade::register($this->app->make(ModuleInstancePermissions::class));

        Gate::before(function ($user, $ability) {
            return app()->make(PermissionTesterContract::class)->evaluate($ability);
        });

        Route::bind('module_instance_permission', function ($id) {
            return ModuleInstancePermission::findOrFail($id);
        });

        Route::bind('site_permission', function ($ability) {
            $permission = app(PermissionRepositoryContract::class)->get($ability);
            if ($permission->getType() !== 'global') {
                throw new \HttpException('Permission not a site permission', 404);
            }

            return $permission;
        });

        Route::bind('permission', function ($ability) {
            return app(PermissionRepositoryContract::class)->get($ability);
        });
    }
}
