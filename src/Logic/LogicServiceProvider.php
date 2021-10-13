<?php

namespace BristolSU\Support\Logic;

use BristolSU\ControlDB\Events\Group\GroupDeleted;
use BristolSU\ControlDB\Events\Role\RoleDeleted;
use BristolSU\ControlDB\Events\User\UserDeleted;
use BristolSU\Support\Logic\Commands\CacheStatusCommand;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Logic\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceFactoryContract;
use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\Commands\CacheLogic;
use BristolSU\Support\Logic\DatabaseDecorator\LogicDatabaseDecorator;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Listeners\RefreshLogicResult;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Logic Service Provider.
 */
class LogicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * - Bind implementations to interfaces
     *
     */
    public function register()
    {
        $this->app->bind(LogicRepositoryContract::class, LogicRepository::class);
        $this->app->bind(LogicTesterContract::class, LogicTester::class);
        $this->app->bind(AudienceFactoryContract::class, AudienceMemberFactory::class);
        $this->app->extend(LogicTesterContract::class, fn(LogicTesterContract $logicTester) => new LogicDatabaseDecorator($logicTester));

    }

    public function boot()
    {
        $this->commands([CacheLogic::class, CacheStatusCommand::class]);

        Event::listen(AudienceChanged::class, RefreshLogicResult::class);
        Event::listen(UserDeleted::class, fn(UserDeleted $event) => LogicResult::where('user_id', $event->user->id())->delete());
        Event::listen(GroupDeleted::class, fn(GroupDeleted $event) => LogicResult::where('group_id', $event->group->id())->delete());
        Event::listen(RoleDeleted::class, fn(RoleDeleted $event) => LogicResult::where('role_id', $event->role->id())->delete());

    }
}
