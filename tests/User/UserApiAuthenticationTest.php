<?php

namespace BristolSU\Support\Tests\User;

use BristolSU\Support\User\UserApiAuthentication;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;

class UserApiAuthenticationTest extends TestCase
{

    /** @test */
    public function getUser_retrieves_a_user_from_the_api_guard(){
        $user = factory(User::class)->create();
        $this->be($user, 'api');

        $auth = resolve(UserApiAuthentication::class);
        $this->assertModelEquals($user, $auth->getUser());
    }
    
    /** @test */
    public function getUser_returns_null_if_no_user_found(){
        $factory = $this->prophesize(Factory::class);
        $guard = $this->prophesize(Guard::class);
        $guard->check()->shouldBeCalled()->willReturn(false);
        $factory->guard('api')->shouldBeCalled()->willReturn($guard->reveal());
        
        $auth = new UserApiAuthentication($factory->reveal());
        $this->assertNull($auth->getUser());
    }

    /** @test */
    public function setUser_throws_an_exception(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot set an API user');

        $user = factory(User::class)->create();

        $auth = new UserApiAuthentication($this->prophesize(Factory::class)->reveal());
        $auth->setUser($user);
    }

    /** @test */
    public function logout_throws_an_exception(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot log out an API user');

        $auth = new UserApiAuthentication($this->prophesize(Factory::class)->reveal());
        $auth->logout();
    }
    
}