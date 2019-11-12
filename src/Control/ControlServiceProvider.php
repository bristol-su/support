<?php

namespace BristolSU\Support\Control;

use BristolSU\Support\Control\Client\Token;
use BristolSU\Support\Control\Contracts\Client\Token as TokenContract;
use BristolSU\Support\Control\Contracts\Models\Group as GroupModelContract;
use BristolSU\Support\Control\Contracts\Models\GroupTag as GroupTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Role as RoleModelContract;
use BristolSU\Support\Control\Contracts\Models\User as UserContract;
use BristolSU\Support\Control\Contracts\Models\Position as PositionContract;

use BristolSU\Support\Control\Models\Group as GroupModel;
use BristolSU\Support\Control\Models\GroupTag as GroupTagModel;
use BristolSU\Support\Control\Models\Role as RoleModel;
use BristolSU\Support\Control\Models\User as UserModel;
use BristolSU\Support\Control\Models\Position as PositionModel;


use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Position as PositionRepositoryContract;
use BristolSU\Support\Control\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Repositories\GroupTag as GroupTagRepository;
use BristolSU\Support\Control\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Repositories\User as UserRepository;
use BristolSU\Support\Control\Repositories\Position as PositionRepository;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class ControlServiceProvider
 * @package BristolSU\Support\Control
 */
class ControlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Client
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(TokenContract::class, Token::class);

        // Models
        $this->app->bind(GroupTagModelContract::class, GroupTagModel::class);
        $this->app->bind(GroupModelContract::class, GroupModel::class);
        $this->app->bind(RoleModelContract::class, RoleModel::class);
        $this->app->bind(UserContract::class, UserModel::class);
        $this->app->bind(PositionContract::class, PositionModel::class);

        // Repositories
        $this->app->bind(GroupRepositoryContract::class, GroupRepository::class);
        $this->app->bind(GroupTagRepositoryContract::class, GroupTagRepository::class);
        $this->app->bind(RoleRepositoryContract::class, RoleRepository::class);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(PositionRepositoryContract::class, PositionRepository::class);
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

}
