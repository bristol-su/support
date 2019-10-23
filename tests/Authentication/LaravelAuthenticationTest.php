<?php


namespace BristolSU\Support\Tests\Authentication;


use BristolSU\Support\Authentication\LaravelAuthentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class LaravelAuthenticationTest extends TestCase
{

    /**
     * @var LaravelAuthentication
     */
    private $authentication;

    public function setUp(): void
    {
        parent::setUp();
        $this->authentication = resolve(LaravelAuthentication::class);
    }

    /** @test */
    public function get_group_gets_a_group_from_a_group()
    {
        $group = new Group([
            'id' => 2
        ]);
        $this->beGroup($group);

        $this->assertEquals(2, $this->authentication->getGroup()->id);
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
        $request->has('group_id')->shouldBeCalled()->willReturn(true);
        $request->query('group_id')->shouldBeCalled()->willReturn(1);
        $this->authentication = resolve(LaravelAuthentication::class, ['request' => $request->reveal()]);
        $this->assertInstanceOf(Group::class, $this->authentication->getGroup());
        $this->assertEquals(1, $this->authentication->getGroup()->id);
    }

    /** @test */
    public function get_role_gets_role_if_logged_in()
    {
        $role = new Role(['id' => 2]);
        $this->beRole($role);

        $this->assertEquals(2, $this->authentication->getRole()->id);
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
        $this->authentication = resolve(LaravelAuthentication::class, ['request' => $request->reveal()]);
        $this->assertInstanceOf(Role::class, $this->authentication->getRole());
        $this->assertEquals(1, $this->authentication->getRole()->id);
    }

    /** @test */
    public function get_user_returns_a_user_if_logged_into_a_user()
    {
        $user = new User(['id' => 1]);
        $this->beUser($user);
        $this->assertEquals($user->id, $this->authentication->getUser()->id());
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
        $this->authentication = resolve(LaravelAuthentication::class, ['request' => $request->reveal()]);
        $this->assertInstanceOf(User::class, $this->authentication->getUser());
        $this->assertEquals(1, $this->authentication->getUser()->id);
    }

    /** @test */
    public function set_user_sets_the_user()
    {
        $user = new User(['id' => 1]);
        $this->authentication->setUser($user);
        $this->assertEquals($user, Auth::guard('user')->user());
    }

    /** @test */
    public function set_group_sets_the_group()
    {
        $group = new Group(['id' => 1]);
        $this->authentication->setGroup($group);
        $this->assertEquals(1, Auth::guard('group')->user()->id);
    }

    /** @test */
    public function set_role_sets_the_role()
    {
        $role = new Role(['id' => 1]);
        $this->authentication->setRole($role);
        $this->assertEquals(1, Auth::guard('role')->user()->id);
    }

}
