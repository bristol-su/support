<?php

namespace BristolSU\Support\Tests\Logic\Audience;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Position as PositionRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Models\Position;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Collection;

class AudienceMemberTest extends TestCase
{

    /** @test */
    public function groups_loads_the_users_groups_if_not_already_loaded(){
        $user = $this->newUser(['id' => 1]);
        $group1 = $this->newGroup(['id' => 2]);
        $group2 = $this->newGroup(['id' => 3]);
        $groups = collect([$group1, $group2]);

        $user->addGroup($group1);
        $user->addGroup($group2);
        
        $audienceMember = new AudienceMember($user);
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(2, $audienceMember->groups());
        $this->assertModelEquals($group1, $audienceMember->groups()[0]);
        $this->assertModelEquals($group2, $audienceMember->groups()[1]);
    }
    
    /** @test */
    public function roles_loads_the_users_roles_if_not_already_loaded(){
        $user = $this->newUser(['id' => 1]);
        $role1 = $this->newRole(['id' => 2]);
        $role2 = $this->newRole(['id' => 3]);
        $roles = collect([$role1, $role2]);

        $user->addRole($role1);
        $user->addRole($role2);
        
        $audienceMember = new AudienceMember($user);
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(2, $audienceMember->roles());
        $this->assertModelEquals($role1, $audienceMember->roles()[0]);
        $this->assertModelEquals($role2, $audienceMember->roles()[1]);
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
        
        $audienceMember = new AudienceMember($user);
        
        $this->logicTester()->forLogic($logic)->pass($user);
        $this->logicTester()->bind();
        
        $audienceMember->filterForLogic($logic);
        
        $this->assertTrue($audienceMember->canBeUser());
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(0, $audienceMember->roles());
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(0, $audienceMember->groups());

    }

    /** @test */
    public function filterForLogic_evaluates_each_group(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $group1 = $this->newGroup(['id' => 2]);
        $group2 = $this->newGroup(['id' => 3]);
        $groups = collect([$group1, $group2]);

        $user->addGroup($group1);
        $user->addGroup($group2);
        
        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user, $group1);
        $this->logicTester()->forLogic($logic)->fail($user, $group2);
        $this->logicTester()->bind();
        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(0, $audienceMember->roles());
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(1, $audienceMember->groups());
        $this->assertModelEquals($group1, $audienceMember->groups()[0]);

    }

    /** @test */
    public function filterForLogic_evaluates_each_role(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $role1 = $this->newRole(['id' => 4]);
        $role2 = $this->newRole(['id' => 5]);
        $roles = collect([$role1, $role2]);

        $user->addRole($role1);
        $user->addRole($role2);
        
        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user, $role1->group(), $role1);
        $this->logicTester()->forLogic($logic)->fail($user, $role2->group(), $role2);
        $this->logicTester()->bind();

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(0, $audienceMember->groups());
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(1, $audienceMember->roles());
        $this->assertModelEquals($role1, $audienceMember->roles()[0]);
    }


    /** @test */
    public function hasAudience_returns_true_if_a_user_can_act_as_themselves(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);

        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user);
        $this->logicTester()->bind();
        
        $audienceMember->filterForLogic($logic);

        $this->assertTrue($audienceMember->canBeUser());
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(0, $audienceMember->groups());
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(0, $audienceMember->roles());
        $this->assertTrue($audienceMember->hasAudience());
    }

    /** @test */
    public function hasAudience_returns_true_if_a_user_has_groups(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $group1 = $this->newGroup(['id' => 2]);
        $group2 = $this->newGroup(['id' => 3]);

        $user->addGroup($group1);
        $user->addGroup($group2);
            
        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user, $group1);
        $this->logicTester()->forLogic($logic)->fail($user, $group2);
        
        $this->logicTester()->bind();

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(0, $audienceMember->roles());
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(1, $audienceMember->groups());
        $this->assertModelEquals($group1, $audienceMember->groups()[0]);
    }

    /** @test */
    public function hasAudience_returns_true_if_a_user_has_roles(){
        $logic = factory(Logic::class)->create();
        $user = $this->newUser(['id' => 1]);
        $role1 = $this->newRole(['id' => 4]);
        $role2 = $this->newRole(['id' => 5]);
        $roles = collect([$role1, $role2]);

        $user->addRole($role1);
        $user->addRole($role2);
        
        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->pass($user, $role1->group(), $role1);
        $this->logicTester()->forLogic($logic)->fail($user, $role2->group(), $role2);
        $this->logicTester()->bind();

        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(0, $audienceMember->groups());
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(1, $audienceMember->roles());
        $this->assertModelEquals($role1, $audienceMember->roles()[0]);
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

        $user->addRole($role1);
        $user->addRole($role2);
        $user->addGroup($group1);
        $user->addGroup($group2);
        
        $audienceMember = new AudienceMember($user);

        $this->logicTester()->forLogic($logic)->alwaysFail();
        $this->logicTester()->bind();
        
        $audienceMember->filterForLogic($logic);

        $this->assertFalse($audienceMember->canBeUser());
        $this->assertInstanceOf(Collection::class, $audienceMember->groups());
        $this->assertCount(0, $audienceMember->groups());
        $this->assertInstanceOf(Collection::class, $audienceMember->roles());
        $this->assertCount(0, $audienceMember->roles());
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

        $user->addRole($role1);
        $user->addRole($role2);
        $user->addGroup($group1);
        $user->addGroup($group2);

        $audienceMember = new AudienceMember($user);

        $this->assertArrayHasKey('user', $audienceMember->toArray());
        $this->assertArrayHasKey('can_be_user', $audienceMember->toArray());
        $this->assertArrayHasKey('groups', $audienceMember->toArray());
        $this->assertArrayHasKey('roles', $audienceMember->toArray());
        $this->assertModelEquals($user, $audienceMember->toArray()['user']);
        $this->assertTrue($audienceMember->toArray()['can_be_user']);
        $this->assertInstanceOf(Collection::class, $audienceMember->toArray()['groups']);
        $this->assertCount(2, $audienceMember->toArray()['groups']);
        $this->assertModelEquals($group1, $audienceMember->toArray()['groups'][0]);
        $this->assertModelEquals($group2, $audienceMember->toArray()['groups'][1]);
        $this->assertInstanceOf(Collection::class, $audienceMember->toArray()['roles']);
        $this->assertCount(2, $audienceMember->toArray()['roles']);
        $this->assertModelEquals($role1, $audienceMember->toArray()['roles'][0]);
        $this->assertModelEquals($role2, $audienceMember->toArray()['roles'][1]);
        
        
    }
    
    /** @test */
    public function canBeUser_defaults_to_true(){
        $user = $this->newUser(['id' => 1]);
        $audienceMember = new AudienceMember($user);
        $this->assertTrue($audienceMember->canBeUser());
    }
}