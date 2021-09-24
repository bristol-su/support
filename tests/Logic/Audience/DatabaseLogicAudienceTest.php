<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\ControlDB\Repositories\User as UserRepository;
use BristolSU\ControlDB\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Repositories\Role as RoleRepository;
use BristolSU\Support\Logic\Audience\DatabaseLogicAudience;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;

class DatabaseLogicAudienceTest extends TestCase
{

    /** @test */
    public function it_creates_audience_from_the_database(){
        $audience = new DatabaseLogicAudience(
            app(UserRepository::class),
            app(GroupRepository::class),
            app(RoleRepository::class),
        );

        $logic = Logic::factory()->create();
        $role1 = $this->newRole();
        $role2 = $this->newRole();
        $role3 = $this->newRole();
        $group1 = $this->newGroup();
        $group2 = $this->newGroup();
        $group3 = $this->newGroup();

        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $user3 = $this->newUser();

        LogicResult::factory()->forLogic($logic)->forUser($user1)->forGroup($role1->group())->forRole($role1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user1)->forGroup($role2->group())->forRole($role2)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user2)->forGroup($group1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user2)->forGroup($group2)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user2)->forGroup($role1->group())->forRole($role1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user3)->forGroup($group3)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user3)->forGroup($role1->group())->forRole($role1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user3)->forGroup($role2->group())->forRole($role2)->passing()->create();

        $audienceResult = $audience->audience($logic);
        dd($audienceResult);
    }

}
