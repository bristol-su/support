<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserAuthentication;
use BristolSU\Support\User\Contracts\UserRepository as UserRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

/**
 * Class UserServiceProvider
 * @package BristolSU\Support\User
 */
class UserServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->call([$this, 'registerUserAuthentication']);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
    }

    public function registerUserAuthentication(Request $request)
    {
        $this->app->bind(UserAuthentication::class, function ($app) use ($request) {
            return ($request->is('api/*') ?
                $app->make(UserApiAuthentication::class) :
                $app->make(UserWebAuthentication::class));
        });
    }

    public function boot()
    {
        $this->app['auth']->resolveUsersUsing(function() {
            return app()->make(UserAuthentication::class)->getUser();
        });
    }


}