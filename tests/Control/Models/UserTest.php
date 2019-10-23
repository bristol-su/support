<?php


namespace BristolSU\Support\Tests\Control\Models;


use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Tests\TestCase;

class UserTest extends TestCase
{

    /** @test */
    public function id_returns_the_id_of_the_user(){
        $user = new User(['id' => 5]);
        $this->assertEquals(5, $user->id());
    }

    /** @test */
    public function data_platorm_id_returns_the_data_platform_id_of_the_user(){
        $user = new User(['uc_uid' => 'unioncloud_uid']);
        $this->assertEquals('unioncloud_uid', $user->dataPlatformId());
    }
    
    /** @test */
    public function getAuthIdentifier_returns_the_id_of_the_user(){
        $user = new User(['id' => 5]);
        $this->assertEquals(5, $user->getAuthIdentifier());
    }
    
    /** @test */
    public function getAuthIdentifierName_returns_id(){
        $user = new User();
        $this->assertEquals('id', $user->getAuthIdentifierName());
    }



}
