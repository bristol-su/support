<?php


namespace BristolSU\Support\DataPlatform;


use BristolSU\Support\DataPlatform\Contracts\Repositories\User as UserRepositoryContract;
use BristolSU\Support\DataPlatform\Repositories\User as UserRepository;
use Illuminate\Support\ServiceProvider;

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
