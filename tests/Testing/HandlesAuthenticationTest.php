<?php

namespace BristolSU\Support\Tests\Testing;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Testing\HandlesAuthentication;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class HandlesAuthenticationTest extends TestCase
{
    use HandlesAuthentication;
    
    /** @test */
    public function newUser_creates_a_new_user(){
        $user = $this->newUser();
        
        $this->assertInstanceOf(User::class, $user);
    }
    
    /** @test */
    public function newUser_allows_attributes_to_be_overridden(){
        $user = $this->newUser(['data_provider_id' => 5]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(5, $user->dataProviderId());
    }

    /** @test */
    public function newGroup_creates_a_new_group(){
        $group = $this->newGroup();

        $this->assertInstanceOf(Group::class, $group);
    }

    /** @test */
    public function newGroup_allows_attributes_to_be_overridden(){
        $group = $this->newGroup(['data_provider_id' => 5]);

        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals(5, $group->dataProviderId());
    }

    /** @test */
    public function newRole_creates_a_new_role(){
        $role = $this->newRole();

        $this->assertInstanceOf(Role::class, $role);
    }

    /** @test */
    public function newRole_allows_attributes_to_be_overridden(){
        $role = $this->newRole(['data_provider_id' => 5, 'position_id' => 1]);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals(5, $role->dataProviderId());
        $this->assertEquals(1, $role->positionId());
    }

    /** @test */
    public function setUser_sets_a_user_for_authentication(){
        $user = $this->newUser();
        $authentication = $this->prophesize(Authentication::class);
        $authentication->setUser(Argument::that(function($actual) use ($user) {
            return $actual instanceof \BristolSU\ControlDB\Contracts\Models\User && $actual->id() === $user->id();
        }))->shouldBeCalled();
        $this->app->instance(Authentication::class, $authentication->reveal());

        $this->beUser($user);
    }

    /** @test */
    public function setGroup_sets_a_group_for_authentication(){
        $group = $this->newGroup();
        $authentication = $this->prophesize(Authentication::class);
        $authentication->setGroup(Argument::that(function($actual) use ($group) {
            return $actual instanceof \BristolSU\ControlDB\Contracts\Models\Group && $actual->id() === $group->id();
        }))->shouldBeCalled();
        $this->app->instance(Authentication::class, $authentication->reveal());

        $this->beGroup($group);
    }

    /** @test */
    public function setRole_sets_a_role_for_authentication(){
        $role = $this->newRole();
        $authentication = $this->prophesize(Authentication::class);
        $authentication->setRole(Argument::that(function($actual) use ($role) {
            return $actual instanceof \BristolSU\ControlDB\Contracts\Models\Role && $actual->id() === $role->id();
        }))->shouldBeCalled();
        $this->app->instance(Authentication::class, $authentication->reveal());

        $this->beRole($role);
    }
}