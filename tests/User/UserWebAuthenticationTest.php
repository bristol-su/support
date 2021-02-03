<?php

namespace BristolSU\Support\Tests\User;

use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use BristolSU\Support\User\UserWebAuthentication;

class UserWebAuthenticationTest extends TestCase
{
    /** @test */
    public function get_user_retrieves_a_user_from_the_web_guard()
    {
        $user = factory(User::class)->create();
        $this->be($user, 'web');
        
        $auth = resolve(UserWebAuthentication::class);
        $this->assertModelEquals($user, $auth->getUser());
    }

    /** @test */
    public function set_user_sets_a_user_in_the_web_guard()
    {
        $user = factory(User::class)->create();

        $auth = resolve(UserWebAuthentication::class);
        $auth->setUser($user);
        
        $this->assertAuthenticatedAs($user, 'web');
    }
    
    /** @test */
    public function get_user_returns_null_if_no_user_in_guard()
    {
        $auth = resolve(UserWebAuthentication::class);
        $this->assertNull($auth->getUser());
    }

    /** @test */
    public function logout_logs_a_user_out()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->assertAuthenticatedAs($user, 'web');

        $auth = resolve(UserWebAuthentication::class);
        $auth->logout();

        $this->assertGuest();
    }
}
