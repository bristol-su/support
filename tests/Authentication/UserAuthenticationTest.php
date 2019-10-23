<?php

namespace BristolSU\Support\Tests\Authentication;

use BristolSU\Support\Authentication\UserAuthentication;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use Illuminate\Contracts\Auth\Factory;

class UserAuthenticationTest extends TestCase
{

    /** @test */
    public function getUser_retrieves_a_user_from_the_web_guard(){
        $user = factory(User::class)->create();
        $this->be($user, 'web');
        
        $auth = resolve(UserAuthentication::class);
        $this->assertModelEquals($user, $auth->getUser());
    }

    /** @test */
    public function getUser_retrieves_a_user_from_the_api_guard(){
        $user = factory(User::class)->create();
        $this->be($user, 'api');

        $auth = resolve(UserAuthentication::class);
        $this->assertModelEquals($user, $auth->getUser());
    }

    /** @test */
    public function getUser_prefers_a_user_from_the_web_guard(){
        $userWeb = factory(User::class)->create();
        $this->be($userWeb, 'web');
        $userApi = factory(User::class)->create();
        $this->be($userApi, 'api');

        $auth = resolve(UserAuthentication::class);
        $this->assertModelEquals($userWeb, $auth->getUser());
    }
    
    /** @test */
    public function setUser_sets_a_user_in_the_web_guard(){
        $user = factory(User::class)->create();

        $auth = resolve(UserAuthentication::class);
        $auth->setUser($user);
        
        $this->assertAuthenticatedAs($user, 'web');
    }
    
}