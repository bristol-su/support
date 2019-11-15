<?php

namespace BristolSU\Support\Control;

use BristolSU\Support\Control\Client\Token;
use BristolSU\Support\Control\Contracts\Client\Token as TokenContract;
use BristolSU\Support\Control\Contracts\Models\Group as GroupModelContract;
use BristolSU\Support\Control\Contracts\Models\Role as RoleModelContract;
use BristolSU\Support\Control\Contracts\Models\User as UserContract;
use BristolSU\Support\Control\Contracts\Models\Position as PositionContract;

use BristolSU\Support\Control\Models\Group as GroupModel;
use BristolSU\Support\Control\Models\Role as RoleModel;
use BristolSU\Support\Control\Models\User as UserModel;
use BristolSU\Support\Control\Models\Position as PositionModel;


use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Position as PositionRepositoryContract;
use BristolSU\Support\Control\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Repositories\User as UserRepository;
use BristolSU\Support\Control\Repositories\Position as PositionRepository;

// Tag models (contracts)
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTagCategory as GroupTagCategoryModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTagCategory as UserTagCategoryModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTagCategory as RoleTagCategoryModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTagCategory as PositionTagCategoryModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTag as GroupTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTag as UserTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTag as RoleTagModelContract;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTag as PositionTagModelContract;

// Tag models
use BristolSU\Support\Control\Models\Tags\GroupTag as GroupTagModel;
use BristolSU\Support\Control\Models\Tags\GroupTagCategory as GroupTagCategoryModel;
use BristolSU\Support\Control\Models\Tags\UserTag as UserTagModel;
use BristolSU\Support\Control\Models\Tags\UserTagCategory as UserTagCategoryModel;
use BristolSU\Support\Control\Models\Tags\RoleTag as RoleTagModel;
use BristolSU\Support\Control\Models\Tags\RoleTagCategory as RoleTagCategoryModel;
use BristolSU\Support\Control\Models\Tags\PositionTag as PositionTagModel;
use BristolSU\Support\Control\Models\Tags\PositionTagCategory as PositionTagCategoryModel;

// Tag repositories (contracts)
use BristolSU\Support\Control\Contracts\Repositories\Tags\GroupTagCategory as GroupTagCategoryRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\UserTagCategory as UserTagCategoryRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\RoleTagCategory as RoleTagCategoryRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\PositionTagCategory as PositionTagCategoryRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\GroupTag as GroupTagRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\UserTag as UserTagRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\RoleTag as RoleTagRepositoryContract;
use BristolSU\Support\Control\Contracts\Repositories\Tags\PositionTag as PositionTagRepositoryContract;

// Tag repositories
use BristolSU\Support\Control\Repositories\Tags\GroupTag as GroupTagRepository;
use BristolSU\Support\Control\Repositories\Tags\GroupTagCategory as GroupTagCategoryRepository;
use BristolSU\Support\Control\Repositories\Tags\UserTag as UserTagRepository;
use BristolSU\Support\Control\Repositories\Tags\UserTagCategory as UserTagCategoryRepository;
use BristolSU\Support\Control\Repositories\Tags\RoleTag as RoleTagRepository;
use BristolSU\Support\Control\Repositories\Tags\RoleTagCategory as RoleTagCategoryRepository;
use BristolSU\Support\Control\Repositories\Tags\PositionTag as PositionTagRepository;
use BristolSU\Support\Control\Repositories\Tags\PositionTagCategory as PositionTagCategoryRepository;


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

        // Base Models
        $this->app->bind(GroupModelContract::class, GroupModel::class);
        $this->app->bind(RoleModelContract::class, RoleModel::class);
        $this->app->bind(UserContract::class, UserModel::class);
        $this->app->bind(PositionContract::class, PositionModel::class);

        // Base Repositories
        $this->app->bind(GroupRepositoryContract::class, GroupRepository::class);
        $this->app->bind(RoleRepositoryContract::class, RoleRepository::class);
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(PositionRepositoryContract::class, PositionRepository::class);
        
        // Tag Models
        $this->app->bind(GroupTagModelContract::class, GroupTagModel::class);
        $this->app->bind(GroupTagCategoryModelContract::class, GroupTagCategoryModel::class);
        $this->app->bind(UserTagModelContract::class, UserTagModel::class);
        $this->app->bind(UserTagCategoryModelContract::class, UserTagCategoryModel::class);
        $this->app->bind(RoleTagModelContract::class, RoleTagModel::class);
        $this->app->bind(RoleTagCategoryModelContract::class, RoleTagCategoryModel::class);
        $this->app->bind(PositionTagModelContract::class, PositionTagModel::class);
        $this->app->bind(PositionTagCategoryModelContract::class, PositionTagCategoryModel::class);
        
        // Tag Repositories
        $this->app->bind(GroupTagRepositoryContract::class, GroupTagRepository::class);
        $this->app->bind(GroupTagCategoryRepositoryContract::class, GroupTagCategoryRepository::class);
        $this->app->bind(UserTagRepositoryContract::class, UserTagRepository::class);
        $this->app->bind(UserTagCategoryRepositoryContract::class, UserTagCategoryRepository::class);
        $this->app->bind(RoleTagRepositoryContract::class, RoleTagRepository::class);
        $this->app->bind(RoleTagCategoryRepositoryContract::class, RoleTagCategoryRepository::class);
        $this->app->bind(PositionTagRepositoryContract::class, PositionTagRepository::class);
        $this->app->bind(PositionTagCategoryRepositoryContract::class, PositionTagCategoryRepository::class);
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
