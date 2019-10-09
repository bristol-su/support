<?php


namespace BristolSU\Support\Tests\Control\Models;


use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Tests\TestCase;

class GroupTest extends TestCase
{

    /** @test */
    public function get_auth_identifier_returns_id(){
        $group = new Group(['id' => 5]);
        $this->assertEquals(5, $group->getAuthIdentifier());
    }

    /** @test */
    public function get_auth_identifier_name_returns_id(){
        $this->assertEquals('id', (new Group)->getAuthIdentifierName());
    }

}
