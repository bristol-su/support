<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserRepository as UserRepositoryContract;
use Illuminate\Support\ServiceProvider;

/**
 * Class UserServiceProvider
 * @package BristolSU\Support\User
 */
class UserServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
    }

}