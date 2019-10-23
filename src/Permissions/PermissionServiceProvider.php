<?php


namespace BristolSU\Support\Permissions;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Permissions\Contracts\Models\Permission as PermissionContract;
use BristolSU\Support\Permissions\Models\ModuleInstancePermissions;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionRepository as PermissionRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionStore as PermissionStoreContract;
use BristolSU\Support\Permissions\Contracts\PermissionTester as PermissionTesterContract;
use BristolSU\Support\Permissions\Facade\Permission as PermissionFacade;
use BristolSU\Support\Permissions\Facade\PermissionTester as PermissionTesterFacade;
use BristolSU\Support\Permissions\Testers\CheckPermissionExists;
use BristolSU\Support\Permissions\Testers\ModuleInstanceAdminPermissions;
use BristolSU\Support\Permissions\Testers\ModuleInstanceUserPermissions;
use BristolSU\Support\Permissions\Testers\SystemGroupPermission;
use BristolSU\Support\Permissions\Testers\SystemLogicPermission;
use BristolSU\Support\Permissions\Testers\SystemRolePermission;
use BristolSU\Support\Permissions\Testers\SystemUserPermission;
use BristolSU\Support\User\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Class PermissionServiceProvider
 * @package BristolSU\Support\Permissions
 */
class PermissionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(PermissionContract::class, Permission::class);
        $this->app->bind(PermissionRepositoryContract::class, PermissionRepository::class);
        $this->app->singleton(PermissionStoreContract::class, PermissionStore::class);
        $this->app->singleton(PermissionTesterContract::class, PermissionTester::class);
    }

    public function boot()
    {
        PermissionTesterFacade::register($this->app->make(SystemUserPermission::class));
        PermissionTesterFacade::register($this->app->make(SystemGroupPermission::class));
        PermissionTesterFacade::register($this->app->make(SystemRolePermission::class));
        PermissionTesterFacade::register($this->app->make(SystemLogicPermission::class));
        PermissionTesterFacade::register($this->app->make(ModuleInstanceUserPermissions::class));
        PermissionTesterFacade::register($this->app->make(ModuleInstanceAdminPermissions::class));
        PermissionTesterFacade::register($this->app->make(CheckPermissionExists::class));

        PermissionFacade::registerSitePermission(
            'settings',
            'Access Settings',
            'Can access the settings page'
        );

        Gate::before(function(User $user, $ability) {
            $tester = app()->make(PermissionTesterContract::class);
            return $tester->evaluate($ability);
        });

        Route::bind('module_instance_permission', function ($id) {
            return ModuleInstancePermissions::findOrFail($id);
        });
        
    }
}
