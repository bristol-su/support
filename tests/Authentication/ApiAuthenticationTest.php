<?php


namespace BristolSU\Support\Tests\Authentication;


use BristolSU\Support\Authentication\ApiAuthentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{

    /**
     * @var ApiAuthentication
     */
    private $authentication;

    public function setUp(): void
    {
        parent::setUp();
        $this->authentication = resolve(ApiAuthentication::class);
    }

    /** @test */
    public function get_group_returns_null_if_not_logged_into_a_group_or_role()
    {
        $this->stubControl();
        $this->assertNull($this->authentication->getGroup());
    }

    /** @test */
    public function get_group_returns_a_group_if_given_in_url()
    {
        $this->mockControl('get', 'groups/1', ['id' => 1], true);
        $request = $this->prophesize(Request::class);
        $request->has('role_id')->shouldBeCalled()->willReturn(false);
        $request->has('group_id')->shouldBeCalled()->willReturn(true);
        $request->query('group_id')->shouldBeCalled()->willReturn(1);
        $this->authentication = resolve(ApiAuthentication::class, ['request' => $request->reveal()]);
        $this->assertInstanceOf(Group::class, $this->authentication->getGroup());
        $this->assertEquals(1, $this->authentication->getGroup()->id);
    }

    /** @test */
    public function get_group_returns_a_group_given_by_a_role_if_role_given(){

    }

    /** @test */
    public function get_role_returns_null_if_not_logged_into_role()
    {
        $this->stubControl();
        $this->assertNull($this->authentication->getRole());
    }

    /** @test */
    public function get_role_returns_a_role_if_given_in_query()
    {
        $this->mockControl('get', 'roles/1', ['id' => 1], true);
        $request = $this->prophesize(Request::class);
        $request->has('role_id')->shouldBeCalled()->willReturn(true);
        $request->query('role_id')->shouldBeCalled()->willReturn(1);
        $this->authentication = resolve(ApiAuthentication::class, ['request' => $request->reveal()]);
        $this->assertInstanceOf(Role::class, $this->authentication->getRole());
        $this->assertEquals(1, $this->authentication->getRole()->id);
    }

    /** @test */
    public function get_user_returns_null_if_not_logged_into_a_user()
    {
        $this->assertNull($this->authentication->getUser());
    }

    /** @test */
    public function get_user_returns_a_user_if_given_in_query()
    {
        $this->mockControl('get', 'students/1', ['id' => 1], true);
        $request = $this->prophesize(Request::class);
        $request->has('user_id')->shouldBeCalled()->willReturn(true);
        $request->query('user_id')->shouldBeCalled()->willReturn(1);
        $this->authentication = resolve(ApiAuthentication::class, ['request' => $request->reveal()]);
        $this->assertInstanceOf(User::class, $this->authentication->getUser());
        $this->assertEquals(1, $this->authentication->getUser()->id);
    }

    /** @test */
    public function set_user_sets_the_user()
    {
        $this->mockControl('get', 'students/1', ['id' => 1], true);
        $this->authentication = resolve(ApiAuthentication::class);
        $user = new User(['id' => 1]);
        $this->authentication->setUser($user);
        $this->assertInstanceOf(User::class, $this->authentication->getUser());
        $this->assertEquals(1, $this->authentication->getUser()->id);
    }

    /** @test */
    public function set_group_sets_the_group()
    {
        $this->mockControl('get', 'groups/1', ['id' => 1], true);
        $this->authentication = resolve(ApiAuthentication::class);
        $group = new Group(['id' => 1]);
        $this->authentication->setGroup($group);
        $this->assertInstanceOf(Group::class, $this->authentication->getGroup());
        $this->assertEquals(1, $this->authentication->getGroup()->id);
    }

    /** @test */
    public function set_role_sets_the_role()
    {
        $this->mockControl('get', 'roles/1', ['id' => 1], true);
        $this->authentication = resolve(ApiAuthentication::class);
        $role = new Role(['id' => 1]);
        $this->authentication->setRole($role);
        $this->assertInstanceOf(Role::class, $this->authentication->getRole());
        $this->assertEquals(1, $this->authentication->getRole()->id);
    }
}
