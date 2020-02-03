<?php

namespace BristolSU\Support\Tests\User;

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

}