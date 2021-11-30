<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\ControlDB\Contracts\Repositories\Pivots\UserGroup;
use BristolSU\ControlDB\Contracts\Repositories\Pivots\UserRole;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\ControlDB\Contracts\Models\User as UserContract;
use BristolSU\ControlDB\Contracts\Models\Group as GroupContract;
use BristolSU\ControlDB\Contracts\Models\Role as RoleContract;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Collection;
use Prophecy\Argument;

class AudienceMemberFactoryTest extends TestCase
{
    /** @test */
    public function from_user_creates_an_audience_member_from_a_given_user()
    {
        $user = $this->newUser();
        $groups = Group::factory()->count(3)->create()->each(fn(Group $group) => $group->addUser($user));
        $roles = Role::factory()->count(3)->create()->each(fn(Role $role) => $role->addUser($user));

        $factory = new AudienceMemberFactory();
        $audienceMember = $factory->fromUser($user);

        $this->assertInstanceOf(AudienceMember::class, $audienceMember);
        $this->assertEquals($user, $audienceMember->user());
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(3, $audienceMember->groups());
        foreach($audienceMember->groups() as $group) {
            $this->assertModelEquals($groups->shift(), $group);
        }
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(3, $audienceMember->roles());
        foreach($audienceMember->roles() as $role) {
            $this->assertModelEquals($roles->shift(), $role);
        }
    }

    /** @test */
    public function withAccessToResource_returns_just_the_user_if_a_user_model_given(){
        $resource = $this->newUser();

        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToResource($resource);

        $this->assertCount(1, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($resource, $audienceMembers[0]->user());
    }

    /** @test */
    public function withAccessToResource_returns_all_members_and_users_with_a_role_attached_to_the_group_if_a_group_model_is_given(){
        $resource = $this->newGroup();
        $role1 = $this->newRole(['group_id' => $resource->id()]);
        $role2 = $this->newRole(['group_id' => $resource->id()]);

        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $user3 = $this->newUser();
        $user4 = $this->newUser();

        $resource->addUser($user1);
        $resource->addUser($user2);
        $role1->addUser($user3);
        $role1->addUser($user4);
        $role2->addUser($user4);

        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToResource($resource);

        $this->assertCount(4, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($user1, $audienceMembers[0]->user());
        $this->assertModelEquals($user2, $audienceMembers[1]->user());
        $this->assertModelEquals($user3, $audienceMembers[2]->user());
        $this->assertModelEquals($user4, $audienceMembers[3]->user());
    }

    /** @test */
    public function withAccessToResource_returns_all_with_the_role_if_the_role_model_is_given(){
        $resource = $this->newRole();

        $user1 = $this->newUser();
        $user2 = $this->newUser();

        $resource->addUser($user1);
        $resource->addUser($user2);

        $factory = new AudienceMemberFactory();
        $audienceMembers = $factory->withAccessToResource($resource);

        $this->assertCount(2, $audienceMembers);
        $this->assertContainsOnlyInstancesOf(AudienceMember::class, $audienceMembers);
        $this->assertModelEquals($user1, $audienceMembers[0]->user());
        $this->assertModelEquals($user2, $audienceMembers[1]->user());
    }

    /** @test */
    public function audience_returns_the_audience_of_a_logic_group_from_the_database(){
        $audience = new AudienceMemberFactory();

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

        LogicResult::factory()->forLogic($logic)->forUser($user1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user1)->forGroup($role1->group())->forRole($role1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user1)->forGroup($role2->group())->forRole($role2)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user2)->forGroup($group1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user2)->forGroup($group2)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user2)->forGroup($role1->group())->forRole($role1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user3)->forGroup($group3)->rejecting()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user3)->forGroup($role1->group())->forRole($role1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user3)->forGroup($role2->group())->forRole($role2)->rejecting()->create();

        $audienceResult = $audience->audience($logic);
        $this->assertCount(3, $audienceResult);
        $firstResult = $audienceResult->shift();
        $this->assertEquals($user1->id(), $firstResult->user()->id());
        $this->assertCount(2, $firstResult->roles());
        $this->assertCount(0, $firstResult->groups());
        $this->assertTrue($firstResult->canBeUser());

        $secondResult = $audienceResult->shift();
        $this->assertEquals($user2->id(), $secondResult->user()->id());
        $this->assertCount(1, $secondResult->roles());
        $this->assertCount(2, $secondResult->groups());
        $this->assertFalse($secondResult->canBeUser());

        $thirdResult = $audienceResult->shift();
        $this->assertEquals($user3->id(), $thirdResult->user()->id());
        $this->assertCount(1, $thirdResult->roles());
        $this->assertCount(0, $thirdResult->groups());
        $this->assertFalse($thirdResult->canBeUser());
    }

    /** @test */
    public function getUsersInLogicGroup_returns_all_users_in_a_logic_group_from_a_database(){
        $audience = new AudienceMemberFactory();

        $logic = Logic::factory()->create();

        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $user3 = $this->newUser();

        LogicResult::factory()->forLogic($logic)->forUser($user1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user2)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forUser($user3)->rejecting()->create();

        $audienceResult = $audience->getUsersInLogicGroup($logic);
        $this->assertCount(2, $audienceResult);
        $this->assertContainsOnlyInstancesOf(UserContract::class, $audienceResult);
        $this->assertEquals($user1->id(), $audienceResult->shift()->id());
        $this->assertEquals($user2->id(), $audienceResult->shift()->id());
    }

    /** @test */
    public function getGroupsInLogicGroup_returns_all_groups_in_a_logic_group_from_a_database(){
        $audience = new AudienceMemberFactory();

        $logic = Logic::factory()->create();

        $group1 = $this->newGroup();
        $group2 = $this->newGroup();
        $group3 = $this->newGroup();

        LogicResult::factory()->forLogic($logic)->forGroup($group1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forGroup($group2)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forGroup($group3)->rejecting()->create();

        $audienceResult = $audience->getGroupsInLogicGroup($logic);
        $this->assertCount(2, $audienceResult);
        $this->assertContainsOnlyInstancesOf(GroupContract::class, $audienceResult);
        $this->assertEquals($group1->id(), $audienceResult->shift()->id());
        $this->assertEquals($group2->id(), $audienceResult->shift()->id());
    }

    /** @test */
    public function getRolesInLogicGroup_returns_all_roles_in_a_logic_group_from_a_database(){
        $audience = new AudienceMemberFactory();

        $logic = Logic::factory()->create();

        $role1 = $this->newRole();
        $role2 = $this->newRole();
        $role3 = $this->newRole();

        LogicResult::factory()->forLogic($logic)->forRole($role1)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forRole($role2)->passing()->create();
        LogicResult::factory()->forLogic($logic)->forRole($role3)->rejecting()->create();

        $audienceResult = $audience->getRolesInLogicGroup($logic);
        $this->assertCount(2, $audienceResult);
        $this->assertContainsOnlyInstancesOf(RoleContract::class, $audienceResult);
        $this->assertEquals($role1->id(), $audienceResult->shift()->id());
        $this->assertEquals($role2->id(), $audienceResult->shift()->id());
    }
}
