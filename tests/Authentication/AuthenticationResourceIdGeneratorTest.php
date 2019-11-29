<?php

namespace BristolSU\Support\Tests\Authentication;

use BristolSU\Support\Authentication\AuthenticationResourceIdGenerator;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Tests\TestCase;
use Exception;

class AuthenticationResourceIdGeneratorTest extends TestCase
{

    /** @test */
    public function fromString_returns_the_user_id_if_user_given(){
        $authentication = $this->prophesize(Authentication::class);
        $user = new User(['id' => 1]);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);

        $idGenerator = new AuthenticationResourceIdGenerator($authentication->reveal());
        $this->assertEquals(1, $idGenerator->fromString('user'));
    }

    /** @test */
    public function fromString_returns_the_group_id_if_group_given(){
        $authentication = $this->prophesize(Authentication::class);
        $group = new Group(['id' => 1]);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);

        $idGenerator = new AuthenticationResourceIdGenerator($authentication->reveal());
        $this->assertEquals(1, $idGenerator->fromString('group'));
    }

    /** @test */
    public function fromString_returns_the_role_id_if_role_given(){
        $authentication = $this->prophesize(Authentication::class);
        $role = new Role(['id' => 1]);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $idGenerator = new AuthenticationResourceIdGenerator($authentication->reveal());
        $this->assertEquals(1, $idGenerator->fromString('role'));
    }

    /** @test */
    public function fromString_throws_an_exception_if_no_user_found_and_user_given(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not logged into correct model');
        
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        
        $idGenerator = new AuthenticationResourceIdGenerator($authentication->reveal());
        $idGenerator->fromString('user');
    }

    /** @test */
    public function fromString_throws_an_exception_if_no_group_found_and_group_given(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not logged into correct model');

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);

        $idGenerator = new AuthenticationResourceIdGenerator($authentication->reveal());
        $idGenerator->fromString('group');
    }

    /** @test */
    public function fromString_throws_an_exception_if_no_role_found_and_role_given(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not logged into correct model');

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $idGenerator = new AuthenticationResourceIdGenerator($authentication->reveal());
        $idGenerator->fromString('role');
    }
    
}