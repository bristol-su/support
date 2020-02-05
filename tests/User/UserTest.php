<?php

namespace BristolSU\Support\Tests\User;

use BristolSU\ControlDB\Models\DataUser;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;

class UserTest extends TestCase
{
    
    /** @test */
    public function controlId_returns_the_control_id(){
        $user = factory(User::class)->create(['id' => 1]);
        
        $this->assertEquals(1, $user->controlId());
    }

    /** @test */
    public function controlUser_returns_the_control_user(){
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['id' => 1]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);

        $this->assertInstanceOf(\BristolSU\ControlDB\Models\User::class, $user->controlUser());
        $this->assertModelEquals($controlUser, $user->controlUser());
    }

    /** @test */
    public function routeNotificationForMail_returns_the_user_email(){
        $dataUser = factory(DataUser::class)->create(['email' => 'example@test.com']);
        $controlUser = factory(\BristolSU\ControlDB\Models\User::class)->create(['data_provider_id' => $dataUser->id()]);
        $user = factory(User::class)->create(['control_id' => $controlUser->id()]);
        
        $this->assertEquals('example@test.com', $user->routeNotificationForMail());
    }
}