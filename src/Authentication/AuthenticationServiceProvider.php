<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\AuthenticationProvider\GroupProvider;
use BristolSU\Support\Authentication\AuthenticationProvider\RoleProvider;
use BristolSU\Support\Authentication\AuthenticationProvider\UserProvider;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\ResourceIdGenerator;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Authentication Service Provider
 */
class AuthenticationServiceProvider extends ServiceProvider
{

    /**
     * Register
     * 
     * - Bind the resource ID generator
     * - Bind the authentication contract
     */
    public function register()
    {
        $this->app->call([$this, 'registerAuthentication']);
        $this->app->bind(ResourceIdGenerator::class, AuthenticationResourceIdGenerator::class);
    }

    /**
     * Boot
     * 
     * - Boot authentication providers for user, group and role
     */
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

    }

    public function registerAuthentication(Request $request)
    {
        $this->app->bind(Authentication::class, function($app) use ($request) {
            return ($request->is('api/*')?
                $app->make(ApiAuthentication::class):
                $app->make(WebAuthentication::class));
        });

    }
    

}