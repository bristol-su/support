<?php


namespace BristolSU\Support\Permissions;


use BristolSU\Support\Permissions\Contracts\Models\Permission as PermissionContract;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Contracts\PermissionRepository as PermissionRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionStore as PermissionStoreContract;
use BristolSU\Support\Permissions\Contracts\PermissionTester as PermissionTesterContract;
use BristolSU\Support\Permissions\Facade\Permission as PermissionFacade;
use BristolSU\Support\Permissions\Facade\PermissionTester as PermissionTesterFacade;
use BristolSU\Support\User\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(PermissionContract::class, Permission::class);
        $this->app->bind(PermissionRepositoryContract::class, PermissionRepository::class);
        $this->app->singleton(PermissionStoreContract::class, PermissionStore::class);
        $this->app->bind(PermissionTesterContract::class, PermissionTester::class);
    }

    public function boot()
    {
        foreach(config('support.permissions.testers') as $tester) {
            PermissionTesterFacade::register($this->app->make($tester));
        }

        PermissionFacade::registerSitePermission(
            'settings',
            'Access Settings',
            'Can access the settings page'
        );

        Gate::before(function(User $user, $ability) {
            return PermissionTesterFacade::evaluate($ability);
        });

    }
}
