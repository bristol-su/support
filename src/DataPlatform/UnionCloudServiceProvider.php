<?php


namespace BristolSU\Support\DataPlatform;


use BristolSU\Support\DataPlatform\Contracts\Repositories\User as UserRepositoryContract;
use BristolSU\Support\DataPlatform\Repositories\User as UserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class UnionCloudServiceProvider
 * @package BristolSU\Support\DataPlatform
 */
class UnionCloudServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
    }

    public function boot()
    {

    }

}
