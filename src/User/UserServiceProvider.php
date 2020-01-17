<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserAuthentication;
use BristolSU\Support\User\Contracts\UserRepository as UserRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

/**
 * Database user service provider
 */
class UserServiceProvider extends ServiceProvider
{

    /**
     * Register
     * 
     * - Register the authentication provider to use for resolving and setting the current user
     * - Bind a repository to the contract
     */
    public function register()
    {
        $this->app->call([$this, 'registerUserAuthentication']);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
    }

    /**
     * Register the authentication provider.
     * 
     * Will register the API authentication provider if '/api/' is in the route, or the web authentication otherwise
     * @param Request $request
     */
    public function registerUserAuthentication(Request $request)
    {
        $this->app->bind(UserAuthentication::class, function($app) use ($request) {
            return ($request->is('api/*') ?
                $app->make(UserApiAuthentication::class) : $app->make(UserWebAuthentication::class));
        });
    }

    /**
     * Boot
     * 
     * - Tell Laravel to resolve users from the authentication contract.
     */
    public function boot()
    {
        $this->app['auth']->resolveUsersUsing(function() {
            return app()->make(UserAuthentication::class)->getUser();
        });
    }


}