<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Position as PositionRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Position;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;

class AudienceMemberTest extends TestCase
{

    /** @test */
    public function groups_loads_the_users_groups_if_not_already_loaded(){
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
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
        $user = new User(['id' => 1]);
        $role1 = new Role(['id' => 2]);
        $role2 = new Role(['id' => 3]);
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
        $user = new User(['id' => 1]);

        $audienceMember = new AudienceMember($user);
        $this->assertEquals($user, $audienceMember->user());
    }

    /** @test */
    public function filterForLogic_evaluates_the_user(){
        $logic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
        $group3 = new Group(['id' => 4]);
        $group4 = new Group(['id' => 5]);
        $role1 = new Role(['id' => 4, 'group_id' => 4]);
        $role2 = new Role(['id' => 5, 'group_id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);
        
        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $groupRepository->getById(4)->shouldBeCalled()->willReturn($group3);
        $groupRepository->getById(5)->shouldBeCalled()->willReturn($group4);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());
        
        $audienceMember = new AudienceMember($user);
        
        $this->createLogicTester([$logic], [], $user, null, null);
        
        $audienceMember->filterForLogic($logic);
        
        $this->assertTrue($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
    }

    /** @test */
    public function filterForLogic_evaluates_each_group(){
        $logic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
        $group3 = new Group(['id' => 4]);
        $group4 = new Group(['id' => 5]);
        $role1 = new Role(['id' => 4, 'group_id' => 4]);
        $role2 = new Role(['id' => 5, 'group_id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $groupRepository->getById(4)->shouldBeCalled()->willReturn($group3);
        $groupRepository->getById(5)->shouldBeCalled()->willReturn($group4);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->createLogicTester([$logic], [], $user, $group1, null);

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect([$group1]), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
    }

    /** @test */
    public function filterForLogic_evaluates_each_role(){
        $logic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
        $group3 = new Group(['id' => 4]);
        $group4 = new Group(['id' => 5]);
        $role1 = new Role(['id' => 4, 'group_id' => 4]);
        $role2 = new Role(['id' => 5, 'group_id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $groupRepository->getById(4)->shouldBeCalled()->willReturn($group3);
        $groupRepository->getById(5)->shouldBeCalled()->willReturn($group4);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->createLogicTester([$logic], [], $user, $group3, $role1);

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect([$role1]), $audienceMember->roles());
    }


    /** @test */
    public function hasAudience_returns_true_if_a_user_can_act_as_themselves(){
        $logic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
        $group3 = new Group(['id' => 4]);
        $group4 = new Group(['id' => 5]);
        $role1 = new Role(['id' => 4, 'group_id' => 4]);
        $role2 = new Role(['id' => 5, 'group_id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $groupRepository->getById(4)->shouldBeCalled()->willReturn($group3);
        $groupRepository->getById(5)->shouldBeCalled()->willReturn($group4);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->createLogicTester([$logic], [], $user, null, null);

        $audienceMember->filterForLogic($logic);

        $this->assertTrue($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function hasAudience_returns_true_if_a_user_has_groups(){
        $logic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
        $group3 = new Group(['id' => 4]);
        $group4 = new Group(['id' => 5]);
        $role1 = new Role(['id' => 4, 'group_id' => 4]);
        $role2 = new Role(['id' => 5, 'group_id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $groupRepository->getById(4)->shouldBeCalled()->willReturn($group3);
        $groupRepository->getById(5)->shouldBeCalled()->willReturn($group4);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->createLogicTester([$logic], [], $user, $group1, null);

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect([$group1]), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function hasAudience_returns_true_if_a_user_has_roles(){
        $logic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
        $group3 = new Group(['id' => 4]);
        $group4 = new Group(['id' => 5]);
        $role1 = new Role(['id' => 4, 'group_id' => 4]);
        $role2 = new Role(['id' => 5, 'group_id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $groupRepository->getById(4)->shouldBeCalled()->willReturn($group3);
        $groupRepository->getById(5)->shouldBeCalled()->willReturn($group4);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->createLogicTester([$logic], [], $user, $group3, $role1);

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect([$role1]), $audienceMember->roles());
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function hasAudience_returns_false_if_a_user_cannot_be_themselves_or_any_group_or_role(){
        $logic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
        $group3 = new Group(['id' => 4]);
        $group4 = new Group(['id' => 5]);
        $role1 = new Role(['id' => 4, 'group_id' => 4]);
        $role2 = new Role(['id' => 5, 'group_id' => 5]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $groupRepository->getById(4)->shouldBeCalled()->willReturn($group3);
        $groupRepository->getById(5)->shouldBeCalled()->willReturn($group4);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $this->createLogicTester();
        
        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertEquals(collect(), $audienceMember->groups());
        $this->assertEquals(collect(), $audienceMember->roles());
        $this->assertFalse($audienceMember->hasAudience());
    }
    
    /** @test */
    public function toArray_toJson_and___toString_returns_attributes(){
        $logic = factory(Logic::class)->create();
        $user = new User(['id' => 1]);
        $group1 = new Group(['id' => 2]);
        $group2 = new Group(['id' => 3]);
        $group3 = new Group(['id' => 4]);
        $group4 = new Group(['id' => 5]);
        $role1 = new Role(['id' => 4, 'group_id' => 4, 'position_id' => 1]);
        $role2 = new Role(['id' => 5, 'group_id' => 5, 'position_id' => 2]);
        $position1 = new Position(['id' => 1]);
        $position2 = new Position(['id' => 2]);
        $groups = collect([$group1, $group2]);
        $roles = collect([$role1, $role2]);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $positionRepository = $this->prophesize(PositionRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($roles);
        $groupRepository->allThroughUser($user)->shouldBeCalledTimes(1)->willReturn($groups);
        $groupRepository->getById(4)->shouldBeCalled()->willReturn($group3);
        $groupRepository->getById(5)->shouldBeCalled()->willReturn($group4);
        $positionRepository->getById(1)->shouldBeCalled()->willReturn($position1);
        $positionRepository->getById(2)->shouldBeCalled()->willReturn($position2);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());
        $this->app->instance(PositionRepository::class, $positionRepository->reveal());

        $audienceMember = new AudienceMember($user);

        $mergedRole1 = $role1;
        $mergedRole1->group = $group3;
        $mergedRole1->position = $position1;
        $mergedRole2 = $role2;
        $mergedRole2->group = $group4;
        $mergedRole2->position = $position2;
        
        $this->assertEquals([
            'user' => $user,
            'can_be_user' => true,
            'groups' => $groups,
            'roles' => collect([$mergedRole1, $mergedRole2])
        ], $audienceMember->toArray());

        $this->assertEquals(json_encode([
            'user' => $user,
            'can_be_user' => true,
            'groups' => $groups,
            'roles' => collect([$mergedRole1, $mergedRole2])
        ]), $audienceMember->toJson());

        $this->assertEquals(json_encode([
            'user' => $user,
            'can_be_user' => true,
            'groups' => $groups,
            'roles' => collect([$mergedRole1, $mergedRole2])
        ]), (string) $audienceMember);
        
    }
    
    /** @test */
    public function canBeUser_defaults_to_true(){
        $user = new User(['id' => 1]);
        $audienceMember = new AudienceMember($user);
        $this->assertTrue($audienceMember->canBeUser());
    }
}