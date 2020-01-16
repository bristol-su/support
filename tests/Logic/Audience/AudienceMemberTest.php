<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Position as PositionRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Models\Position;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;

class AudienceMemberTest extends TestCase
{

    /** @test */
    public function groups_loads_the_users_groups_if_not_already_loaded(){
        $user = $this->newUser(['id' => 1]);
        $group1 = $this->newGroup(['id' => 2]);
        $group2 = $this->newGroup(['id' => 3]);
        $groups = collect([$group1, $group2]);
        
        $groupRepository = $this->prophesize(GroupRepository::class);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        
        $audienceMember = new AudienceMember($user);
        $this->assertEquals($groups, $audienceMember->groups());
        $this->assertEquals($groups, $audienceMember->groups());
    }
    
    /** @test */
    public function roles_loads_the_users_roles_if_not_already_loaded(){
        $user = $this->newUser(['id' => 1]);
        $role1 = $this->newRole(['id' => 2]);
        $role2 = $this->newRole(['id' => 3]);
        $roles = collect([$role1, $role2]);

        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);
        $this->assertEquals($roles, $audienceMember->roles());
        $this->assertEquals($roles, $audienceMember->roles());
    }
    
    /** @test */
    public function user_returns_the_user(){
        $user = $this->newUser(['id' => 1]);

        $audienceMember = new AudienceMember($user);
        $this->assertEquals($user, $audienceMember->user());
    }

    /** @test */
    public function filterForLogic_evaluates_the_user(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        
        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn(collect());
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn(collect());
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());
        
        $audienceMember = new AudienceMember($user);
        
        $this->logicTester()->forLogic($logic)->pass($user);
        $this->logicTester()->bind();
        
        $audienceMember->filterForLogic($logic);
        
        $this->assertTrue($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
    }

    /** @test */
    public function filterForLogic_evaluates_each_group(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $group1 = $this->newGroup(['id' => 2]);
        $group2 = $this->newGroup(['id' => 3]);
        $groups = collect([$group1, $group2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn(collect());
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user, $group1);
        $this->logicTester()->forLogic($logic)->fail($user, $group2);
        $this->logicTester()->bind();
        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect([$group1]), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
    }

    /** @test */
    public function filterForLogic_evaluates_each_role(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $role1 = $this->newRole(['id' => 4]);
        $role2 = $this->newRole(['id' => 5]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn(collect());
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user, $role1->group(), $role1);
        $this->logicTester()->forLogic($logic)->fail($user, $role2->group(), $role2);
        $this->logicTester()->bind();

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect([$role1]), $audienceMember->roles());
    }


    /** @test */
    public function hasAudience_returns_true_if_a_user_can_act_as_themselves(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn(collect());
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn(collect());
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user);
        $this->logicTester()->bind();
        
        $audienceMember->filterForLogic($logic);

        $this->assertTrue($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function hasAudience_returns_true_if_a_user_has_groups(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $group1 = $this->newGroup(['id' => 2]);
        $group2 = $this->newGroup(['id' => 3]);
        $groups = collect([$group1, $group2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn(collect());
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user, $group1);
        $this->logicTester()->forLogic($logic)->fail($user, $group2);
        
        $this->logicTester()->bind();

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect([$group1]), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function hasAudience_returns_true_if_a_user_has_roles(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $role1 = $this->newRole(['id' => 4]);
        $role2 = $this->newRole(['id' => 5]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn(collect());
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user, $role1->group(), $role1);
        $this->logicTester()->forLogic($logic)->fail($user, $role2->group(), $role2);
        $this->logicTester()->bind();

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect([$role1]), $audienceMember->roles());
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function hasAudience_returns_false_if_a_user_cannot_be_themselves_or_any_group_or_role(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $group1 = $this->newGroup(['id' => 2]);
        $group2 = $this->newGroup(['id' => 3]);
        $role1 = $this->newRole(['id' => 4]);
        $role2 = $this->newRole(['id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->alwaysFail();
        $this->logicTester()->bind();
        
        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
        $this->assertFalse($audienceMember->hasAudience());
    }
    
    /** @test */
    public function toArray_toJson_and_toString_returns_attributes(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $group1 = $this->newGroup(['id' => 2]);
        $group2 = $this->newGroup(['id' => 3]);
        $role1 = $this->newRole(['id' => 4]);
        $role2 = $this->newRole(['id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $positionRepository = $this->prophesize(PositionRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());
        $this->app->instance(PositionRepository::class, $positionRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->assertEquals([
            'user' => $user,
            'can_be_user' => true,
            'groups' => $groups,
            'roles' => $roles
        ], $audienceMember->toArray());

        $this->assertEquals(json_encode([
            'user' => $user,
            'can_be_user' => true,
            'groups' => $groups,
            'roles' => $roles
        ]), $audienceMember->toJson());

        $this->assertEquals(json_encode([
            'user' => $user,
            'can_be_user' => true,
            'groups' => $groups,
            'roles' => $roles
        ]), (string) $audienceMember);
        
    }
    
    /** @test */
    public function canBeUser_defaults_to_true(){
        $user = $this->newUser(['id' => 1]);
        $audienceMember = new AudienceMember($user);
        $this->assertTrue($audienceMember->canBeUser());
    }
}