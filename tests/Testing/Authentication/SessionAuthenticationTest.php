<?php

namespace BristolSU\Support\Tests\Testing\Authentication;

use BristolSU\ControlDB\Models\Group;
use BristolSU\Support\Testing\Authentication\SessionAuthentication;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Session;

class SessionAuthenticationTest extends TestCase
{
    /** @test */
    public function get_group_gets_a_group_from_a_group()
    {
        $group = $this->newGroup([
            'id' => 2
        ]);
        $this->beGroup($group);
        $authentication = resolve(SessionAuthentication::class);

        $this->assertEquals(2, $authentication->getGroup()->id);
    }

    /** @test */
    public function get_group_returns_a_group_given_by_a_role_if_role_given()
    {
        $group = $this->newGroup();
        $role = $this->newRole(['group_id' => $group->id()]);
        $this->beRole($role);

        $authentication = resolve(SessionAuthentication::class);
        $authGroup = $authentication->getGroup();
        $this->assertInstanceOf(Group::class, $authGroup);
        $this->assertModelEquals($group, $authGroup);
    }

    /** @test */
    public function get_group_returns_null_if_not_logged_into_a_group_or_role()
    {
        $authentication = resolve(SessionAuthentication::class);
        $this->assertNull($authentication->getGroup());
    }

    /** @test */
    public function get_role_gets_role_if_logged_in()
    {
        $role = $this->newRole();
        $this->beRole($role);

        $authentication = resolve(SessionAuthentication::class);
        $this->assertEquals($role->id(), $authentication->getRole()->id);
    }

    /** @test */
    public function get_role_returns_null_if_not_logged_into_role()
    {
        $authentication = resolve(SessionAuthentication::class);
        $this->assertNull($authentication->getRole());
    }

    /** @test */
    public function get_user_returns_a_user_if_logged_into_a_user()
    {
        $user = $this->newUser();
        $this->beUser($user);
        $authentication = resolve(SessionAuthentication::class);
        $this->assertEquals($user->id(), $authentication->getUser()->id());
    }

    /** @test */
    public function get_user_returns_null_if_not_logged_into_a_user()
    {
        $authentication = resolve(SessionAuthentication::class);
        $this->assertNull($authentication->getUser());
    }

    /** @test */
    public function set_user_sets_the_user()
    {
        $user = $this->newUser();
        $authentication = resolve(SessionAuthentication::class);
        $authentication->setUser($user);
        $this->assertTrue(Session::has('user_id'));
        $this->assertEquals($user->id(), Session::get('user_id'));
    }

    /** @test */
    public function set_group_sets_the_group()
    {
        $group = $this->newGroup();
        $authentication = resolve(SessionAuthentication::class);
        $authentication->setGroup($group);
        $this->assertTrue(Session::has('group_id'));
        $this->assertEquals($group->id(), Session::get('group_id'));
    }

    /** @test */
    public function set_role_sets_the_role()
    {
        $role = $this->newRole();
        $authentication = resolve(SessionAuthentication::class);
        $authentication->setRole($role);
        $this->assertTrue(Session::has('role_id'));
        $this->assertEquals($role->id(), Session::get('role_id'));
    }

    /** @test */
    public function has_user_returns_true_if_a_user_is_logged_in()
    {
        $authentication = resolve(SessionAuthentication::class);
        $authentication->setUser($this->newUser());
        $this->assertTrue($authentication->hasUser());
    }

    /** @test */
    public function has_user_returns_false_if_a_user_is_not_logged_in()
    {
        $authentication = resolve(SessionAuthentication::class);
        $this->assertFalse($authentication->hasUser());
    }

    /** @test */
    public function has_group_returns_true_if_a_group_is_logged_in()
    {
        $authentication = resolve(SessionAuthentication::class);
        $authentication->setGroup($this->newGroup());
        $this->assertTrue($authentication->hasGroup());
    }

    /** @test */
    public function has_group_returns_false_if_a_group_is_not_logged_in()
    {
        $authentication = resolve(SessionAuthentication::class);
        $this->assertFalse($authentication->hasGroup());
    }

    /** @test */
    public function has_role_returns_true_if_a_role_is_logged_in()
    {
        $authentication = resolve(SessionAuthentication::class);
        $authentication->setRole($this->newRole());
        $this->assertTrue($authentication->hasRole());
    }

    /** @test */
    public function has_role_returns_false_if_a_role_is_not_logged_in()
    {
        $authentication = resolve(SessionAuthentication::class);
        $this->assertFalse($authentication->hasRole());
    }
}
