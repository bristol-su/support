<?php


namespace BristolSU\Support\Tests\Authentication;


use BristolSU\Support\Authentication\ApiAuthentication;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;

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
        $this->mockControl('get', 'roles/1', ['id' => 1, 'group_id' => 5], true);
        $this->mockControl('get', 'groups/5', ['id' => 5], true);
        
        $request = $this->prophesize(Request::class);
        
        $request->has('role_id')->shouldBeCalled()->willReturn(true);
        $request->query('role_id')->shouldBeCalled()->willReturn(1);
        
        $this->authentication = resolve(ApiAuthentication::class, ['request' => $request->reveal()]);
        $this->assertInstanceOf(Group::class, $this->authentication->getGroup());
        $this->assertEquals(5, $this->authentication->getGroup()->id());
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
    
    /** @test */
    public function getGroup_returns_null_if_exception_thrown_in_repository(){
        $request = $this->prophesize(Request::class);
        $request->has('role_id')->shouldBeCalled()->willReturn(false);
        $request->has('group_id')->shouldBeCalled()->willReturn(true);
        $request->query('group_id')->shouldBeCalled()->willReturn(1);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $groupRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());
        
        $this->authentication = new ApiAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($this->authentication->getGroup());
    }

    /** @test */
    public function getRole_returns_null_if_exception_thrown_in_repository(){
        $request = $this->prophesize(Request::class);
        $request->has('role_id')->shouldBeCalled()->willReturn(true);
        $request->query('role_id')->shouldBeCalled()->willReturn(1);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());

        $this->authentication = new ApiAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($this->authentication->getRole());
    }

    /** @test */
    public function getUser_returns_null_if_exception_thrown_in_repository(){
        $request = $this->prophesize(Request::class);
        $request->has('user_id')->shouldBeCalled()->willReturn(true);
        $request->query('user_id')->shouldBeCalled()->willReturn(1);

        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $userRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());

        $this->authentication = new ApiAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($this->authentication->getUser());
    }
    
    /** @test */
    public function reset_resets_the_query(){
        $request = $this->prophesize(Request::class);
        $query = $this->prophesize(ParameterBag::class);
        $query->set('user_id', null)->shouldBeCalled();
        $query->set('group_id', null)->shouldBeCalled();
        $query->set('role_id', null)->shouldBeCalled();
        $request->query = $query;
        
        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        
        $this->authentication = new ApiAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->authentication->reset();
    }
    
}
