<?php

namespace BristolSU\Support\Logic;

use BristolSU\ControlDB\Events\Group\GroupCreated;
use BristolSU\ControlDB\Events\Group\GroupDeleted;
use BristolSU\ControlDB\Events\Pivots\UserGroup\UserAddedToGroup;
use BristolSU\ControlDB\Events\Pivots\UserGroup\UserRemovedFromGroup;
use BristolSU\ControlDB\Events\Pivots\UserRole\UserAddedToRole;
use BristolSU\ControlDB\Events\Pivots\UserRole\UserRemovedFromRole;
use BristolSU\ControlDB\Events\Role\RoleCreated;
use BristolSU\ControlDB\Events\Role\RoleDeleted;
use BristolSU\ControlDB\Events\User\UserCreated;
use BristolSU\ControlDB\Events\User\UserDeleted;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Logic\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Commands\CacheLogicCommand;
use BristolSU\Support\Logic\Commands\CacheStatusCommand;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceFactoryContract;
use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;
use BristolSU\Support\Logic\Contracts\LogicTester as LogicTesterContract;
use BristolSU\Support\Logic\DatabaseDecorator\LogicDatabaseDecorator;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Jobs\CacheLogicForGroup;
use BristolSU\Support\Logic\Jobs\CacheLogicForRole;
use BristolSU\Support\Logic\Jobs\CacheLogicForSingleCombination;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
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
        $this->app->extend(LogicTesterContract::class, fn (LogicTesterContract $logicTester) => new LogicDatabaseDecorator($logicTester));
    }

    public function boot()
    {
        $this->commands([CacheLogicCommand::class, CacheStatusCommand::class]);

        Event::listen(AudienceChanged::class, RefreshLogicResult::class);
        Event::listen(UserDeleted::class, fn (UserDeleted $event) => LogicResult::where('user_id', $event->user->id())->delete());
        Event::listen(GroupDeleted::class, fn (GroupDeleted $event) => LogicResult::where('group_id', $event->group->id())->delete());
        Event::listen(RoleDeleted::class, fn (RoleDeleted $event) => LogicResult::where('role_id', $event->role->id())->delete());
        Event::listen(UserCreated::class, fn (UserCreated $event) => dispatch(new CacheLogicForUser([$event->user])));
        Event::listen(GroupCreated::class, fn (GroupCreated $event) => dispatch(new CacheLogicForGroup([$event->group])));
        Event::listen(RoleCreated::class, fn (RoleCreated $event) => dispatch(new CacheLogicForRole([$event->role])));
        Event::listen(UserAddedToRole::class, fn (UserAddedToRole $event) => dispatch(
            new CacheLogicForSingleCombination(null, $event->user, $event->role->group(), $event->role)
        ));
        Event::listen(UserRemovedFromRole::class, fn (UserRemovedFromRole $event) => LogicResult::where([
            'user_id' => $event->user->id(),
            'group_id' => $event->role->group()->id(),
            'role_id' => $event->role->id()
        ])->delete());
        Event::listen(UserAddedToGroup::class, fn (UserAddedToGroup $event) => dispatch(
            new CacheLogicForSingleCombination(null, $event->user, $event->group, null)
        ));
        Event::listen(UserRemovedFromGroup::class, fn (UserRemovedFromGroup $event) => LogicResult::where([
            'user_id' => $event->user->id(),
            'group_id' => $event->group->id(),
            'role_id' => null
        ])->delete());
    }
}
