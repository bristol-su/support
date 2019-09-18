<?php


namespace BristolSU\Support\Tests\Control\Models;


use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Testing\TestCase;

class RoleTest extends TestCase
{

    /** @test */
    public function get_auth_identifier_returns_id(){
        $role = new Role(['id' => 5]);
        $this->assertEquals(5, $role->getAuthIdentifier());
    }

    /** @test */
    public function get_auth_identifier_name_returns_id(){
        $this->assertEquals('id', (new Role)->getAuthIdentifierName());
    }

}
