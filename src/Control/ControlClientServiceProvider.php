<?php

namespace BristolSU\Support\Control;

use BristolSU\Support\Control\Client\GuzzleClient;
use BristolSU\Support\Control\Client\Token;
use BristolSU\Support\Control\Contracts\Client\Client as ClientContract;
use BristolSU\Support\Control\Contracts\Client\Token as TokenContract;
use BristolSU\Support\Control\Contracts\Models\Group as GroupModelContract;
use BristolSU\Support\Control\Contracts\Models\GroupTag as GroupTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Role as RoleModelContract;
use BristolSU\Support\Control\Contracts\Models\User as UserContract;

use BristolSU\Support\Control\Models\Group as GroupModel;
use BristolSU\Support\Control\Models\GroupTag as GroupTagModel;
use BristolSU\Support\Control\Models\Role as RoleModel;
use BristolSU\Support\Control\Models\User as UserModel;


use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepositoryContract;
use BristolSU\Support\Control\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Repositories\GroupTag as GroupTagRepository;
use BristolSU\Support\Control\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Repositories\User as UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Class ControlClientServiceProvider
 * @package BristolSU\Support\Control
 */
class ControlClientServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Client
        $this->app->singleton(ClientContract::class, GuzzleClient::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            ClientContract::class,
        ];
    }

}
