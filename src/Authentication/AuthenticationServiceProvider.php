<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\AuthenticationProvider\GroupProvider;
use BristolSU\Support\Authentication\AuthenticationProvider\RoleProvider;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{

    public function register()
    {
        Auth::provider('role-provider', function(Container $app, array $config) {
            return new RoleProvider($app->make(RoleRepository::class));
        });

        Auth::provider('group-provider', function(Container $app, array $config) {
            return new GroupProvider($app->make(GroupRepository::class));
        });


        $this->app->bind(Authentication::class, LaravelAuthentication::class);
    }

}