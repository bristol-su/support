<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\AuthenticationProvider\GroupProvider;
use BristolSU\Support\Authentication\AuthenticationProvider\RoleProvider;
use BristolSU\Support\Authentication\AuthenticationProvider\UserProvider;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\UserAuthentication as UserAuthenticationContract;
use BristolSU\Support\Authentication\Middleware\CheckValidUserParametersGiven;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\Container;
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
        $this->app->bind(Authentication::class, LaravelAuthentication::class);
        $this->app->bind(UserAuthenticationContract::class, UserAuthentication::class);
    }

    public function boot()
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
        
        $this->app['router']->pushMiddlewareToGroup('activity', CheckValidUserParametersGiven::class);
        
        
    }

}