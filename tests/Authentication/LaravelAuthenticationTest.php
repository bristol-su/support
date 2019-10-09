<?php


namespace BristolSU\Support\Tests\Authentication;


use BristolSU\Support\Authentication\LaravelAuthentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\User\User;
use Illuminate\Support\Facades\Auth;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class LaravelAuthenticationTest extends TestCase
{

    /**
     * @var LaravelAuthentication
     */
    private $authentication;

    public function setUp() : void
    {
        parent::setUp();
        $this->authentication = resolve(LaravelAuthentication::class);
    }

    /** @test */
    public function get_group_gets_a_group_from_a_role()
    {
        $role = new Role([
            'id' => 1,
            'group' => new Group([
                'id' => 2
            ])
        ]);
        $this->beRole($role);
        $group = $this->authentication->getGroup();
        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals(2, $group->id);
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
    public function get_user_returns_a_user_if_logged_into_a_user()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->assertModelEquals($user, $this->authentication->getUser());
    }

    /** @test */
    public function get_user_returns_a_user_if_logged_into_a_user_through_api(){
        $user = factory(User::class)->create();
        $this->be($user, 'api');
        $this->assertModelEquals($user, $this->authentication->getUser());
    }

    /** @test */
    public function get_user_prioritises_a_web_user_if_logged_in(){
        $user = factory(User::class)->create();
        $this->be($user, 'web');
        $userApi = factory(User::class)->create();
        $this->be($userApi, 'api');
        $this->assertModelEquals($user, $this->authentication->getUser());
    }

    /** @test */
    public function get_user_returns_null_if_not_logged_into_a_user()
    {
        $this->assertNull($this->authentication->getUser());
    }

    /** @test */
    public function set_user_sets_the_user(){
        $user = factory(User::class)->create();
        $this->authentication->setUser($user);
        $this->assertModelEquals($user, Auth::guard('web')->user());
    }

    /** @test */
    public function set_group_sets_the_group(){
        $group = new Group(['id' => 1]);
        $this->authentication->setGroup($group);
        $this->assertEquals(1, Auth::guard('group')->user()->id);
    }

    /** @test */
    public function set_role_sets_the_role(){
        $role = new Role(['id' => 1]);
        $this->authentication->setRole($role);
        $this->assertEquals(1, Auth::guard('role')->user()->id);
    }

}
