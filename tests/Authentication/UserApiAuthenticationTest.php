<?php

namespace BristolSU\Support\Tests\Authentication;

use BristolSU\Support\Authentication\UserApiAuthentication;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;

class UserApiAuthenticationTest extends TestCase
{

    /** @test */
    public function getUser_retrieves_a_user_from_the_api_guard(){
        $user = factory(User::class)->create();
        $this->be($user, 'api');

        $auth = resolve(UserApiAuthentication::class);
        $this->assertModelEquals($user, $auth->getUser());
    }

}