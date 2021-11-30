<?php

namespace BristolSU\Support\Tests\Logic;

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
use BristolSU\Support\Filters\FilterInstance;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Jobs\CacheLogicForGroup;
use BristolSU\Support\Logic\Jobs\CacheLogicForRole;
use BristolSU\Support\Logic\Jobs\CacheLogicForSingleCombination;
use BristolSU\Support\Logic\Jobs\CacheLogicForUser;
use BristolSU\Support\Logic\Listeners\RefreshLogicResult;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;

class LogicServiceProviderTest extends TestCase
{

    /** @test */
    public function it_fires_a_refresh_logic_result_when_an_audience_changed_is_fired(){
        Event::fake(AudienceChanged::class);
        Event::assertListening(AudienceChanged::class, RefreshLogicResult::class);
    }

    /** @test */
    public function it_deletes_logic_results_by_user_id_when_a_user_is_deleted(){
        $user = $this->newUser();
        LogicResult::factory(['user_id' => $user->id()])->count(10)->create();
        LogicResult::factory()->count(15)->create();

        $this->assertCount(25, LogicResult::all());
        event(new UserDeleted($user));
        $this->assertCount(15, LogicResult::all());
    }

    /** @test */
    public function it_deletes_logic_results_by_group_id_when_a_group_is_deleted(){
        $group = $this->newGroup();
        LogicResult::factory(['group_id' => $group->id()])->count(10)->create();
        LogicResult::factory()->count(15)->create();

        $this->assertCount(25, LogicResult::all());
        event(new GroupDeleted($group));
        $this->assertCount(15, LogicResult::all());
    }

    /** @test */
    public function it_deletes_logic_results_by_role_id_when_a_role_is_deleted(){
        $role = $this->newRole();
        LogicResult::factory(['role_id' => $role->id()])->count(10)->create();
        LogicResult::factory()->count(15)->create();

        $this->assertCount(25, LogicResult::all());
        event(new RoleDeleted($role));
        $this->assertCount(15, LogicResult::all());
    }

    /** @test */
    public function it_caches_logic_when_a_user_is_created(){
        Bus::fake(CacheLogicForUser::class);

        $user = $this->newUser();
        event(new UserCreated($user));

        Bus::assertDispatchedTimes(CacheLogicForUser::class, 1);
    }

    /** @test */
    public function it_caches_logic_when_a_group_is_created(){
        Bus::fake(CacheLogicForGroup::class);

        $group = $this->newGroup();
        event(new GroupCreated($group));

        Bus::assertDispatchedTimes(CacheLogicForGroup::class, 1);
    }

    /** @test */
    public function it_caches_logic_when_a_role_is_created(){
        Bus::fake(CacheLogicForRole::class);

        $role = $this->newRole();
        event(new RoleCreated($role));

        Bus::assertDispatchedTimes(CacheLogicForRole::class, 1);
    }

    /** @test */
    public function it_caches_logic_when_a_user_is_added_to_a_role(){
        Bus::fake(CacheLogicForSingleCombination::class);

        event(new UserAddedToRole($this->newUser(), $this->newRole()));

        Bus::assertDispatchedTimes(CacheLogicForSingleCombination::class, 1);
    }

    /** @test */
    public function it_removes_logic_when_a_user_is_removed_from_a_group(){
        $group = $this->newGroup();
        $user = $this->newUser();

        LogicResult::factory(['group_id' => $group->id()])->count(10)->create();
        LogicResult::factory(['user_id' => $user->id(), 'group_id' => $group->id()])->count(15)->create();


        $this->assertCount(25, LogicResult::all());
        event(new UserRemovedFromGroup($user, $group));
        $this->assertCount(10, LogicResult::all());
    }

    /** @test */
    public function it_caches_logic_when_a_user_is_added_to_a_group(){
        Bus::fake(CacheLogicForSingleCombination::class);

        event(new UserAddedToGroup($this->newUser(), $this->newGroup()));

        Bus::assertDispatchedTimes(CacheLogicForSingleCombination::class, 1);
    }

}
