<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\AuthenticationProvider\GroupProvider;
use BristolSU\Support\Authentication\AuthenticationProvider\RoleProvider;
use BristolSU\Support\Authentication\AuthenticationProvider\UserProvider;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\UserAuthentication;
use BristolSU\Support\Authentication\Middleware\CheckActivityFor;
use BristolSU\Support\Authentication\Middleware\CheckAdminActivityFor;
use BristolSU\Support\Authentication\Middleware\CheckLoggedIntoActivityForType;
use BristolSU\Support\Authentication\Middleware\CheckValidUserParametersGiven;
use BristolSU\Support\Authentication\Middleware\Logout;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthenticationServiceProvider
 * @package BristolSU\Support\Authentication
 */
class AuthenticationServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->call([$this, 'registerAuthentication']);
    }

    public function boot(Request $request)
    {
        Auth::provider('role-provider', function(Container $app, array $config) {
            return new RoleProvider($app->make(RoleRepository::class));
        });

        Auth::provider('group-provider', function(Container $app, array $config) {
            return new GroupProvider($app->make(GroupRepository::class));
        });

        Auth::provider('user-provider', function(Container $app, array $config) {
            return new UserProvider($app->make(UserRepository::class));
        });
    }

    public function registerAuthentication(Request $request)
    {
        $this->app->bind(Authentication::class, function($app) use ($request) {
            return ($request->is('api/*')?
                $app->make(ApiAuthentication::class):
                $app->make(WebAuthentication::class));
        });
        $this->app->bind(UserAuthentication::class, function($app) use ($request) {
            return ($request->is('api/*')?
                $app->make(UserApiAuthentication::class):
                $app->make(UserWebAuthentication::class));
        });
    }
    

}