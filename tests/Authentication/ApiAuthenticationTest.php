<?php

namespace BristolSU\Support\Tests\Authentication;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Authentication\ApiAuthentication;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class ApiAuthenticationTest extends TestCase
{
    /** @test */
    public function get_group_returns_null_if_not_logged_into_a_group_or_role()
    {
        $authentication = resolve(ApiAuthentication::class);
        $this->assertNull($authentication->getGroup());
    }

    /** @test */
    public function get_group_returns_a_group_if_given_in_url()
    {
        $group = $this->newGroup();
        $groupRepository = $this->prophesize(GroupRepository::class);
        $groupRepository->getById($group->id())->shouldBeCalled()->willReturn($group);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());
        
        $query = $this->prophesize(ParameterBag::class);
        $query->has('role_id')->shouldBeCalled()->willReturn(false);
        $query->has('group_id')->shouldBeCalled()->willReturn(true);
        $query->get('group_id')->shouldBeCalled()->willReturn($group->id());
        
        $request = $this->prophesize(Request::class);
        $request->query = $query->reveal();
        $this->app->instance(Request::class, $request->reveal());

        $authentication = resolve(ApiAuthentication::class);
        
        $this->assertInstanceOf(Group::class, $authentication->getGroup());
        $this->assertModelEquals($group, $authentication->getGroup());
        $this->assertEquals(1, $authentication->getGroup()->id);
    }

    /** @test */
    public function get_group_returns_a_group_given_by_a_role_if_role_given()
    {
        $group = $this->newGroup();
        $role = $this->newRole(['group_id' => $group->id()]);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById($role->id())->shouldBeCalled()->willReturn($role);
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $query = $this->prophesize(ParameterBag::class);
        $query->has('role_id')->shouldBeCalled()->willReturn(true);
        $query->get('role_id')->shouldBeCalled()->willReturn($role->id());
        
        $request = $this->prophesize(Request::class);
        $request->query = $query->reveal();
        $this->app->instance(Request::class, $request->reveal());

        $authentication = resolve(ApiAuthentication::class);
        $this->assertInstanceOf(Group::class, $authentication->getGroup());
        $this->assertEquals($group->id(), $authentication->getGroup()->id());
    }

    /** @test */
    public function get_role_returns_null_if_not_logged_into_role()
    {
        $authentication = resolve(ApiAuthentication::class);
        $this->assertNull($authentication->getRole());
    }

    /** @test */
    public function get_role_returns_a_role_if_given_in_query()
    {
        $role = $this->newRole();
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById($role->id())->shouldBeCalled()->willReturn($role);
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $query = $this->prophesize(ParameterBag::class);
        $query->has('role_id')->shouldBeCalled()->willReturn(true);
        $query->get('role_id')->shouldBeCalled()->willReturn($role->id());

        $request = $this->prophesize(Request::class);
        $request->query = $query->reveal();
        $this->app->instance(Request::class, $request->reveal());
        
        $authentication = resolve(ApiAuthentication::class);
        $this->assertInstanceOf(Role::class, $authentication->getRole());
        $this->assertModelEquals($role, $authentication->getRole());
    }

    /** @test */
    public function get_user_returns_null_if_not_logged_into_a_user()
    {
        $authentication = resolve(ApiAuthentication::class);
        $this->assertNull($authentication->getUser());
    }

    /** @test */
    public function get_user_returns_a_user_if_given_in_query()
    {
        $user = $this->newUser();
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->getById($user->id())->shouldBeCalled()->willReturn($user);
        $this->app->instance(UserRepository::class, $userRepository->reveal());

        $query = $this->prophesize(ParameterBag::class);
        $query->has('user_id')->shouldBeCalled()->willReturn(true);
        $query->get('user_id')->shouldBeCalled()->willReturn($user->id());

        $request = $this->prophesize(Request::class);
        $request->query = $query->reveal();
        $this->app->instance(Request::class, $request->reveal());
        
        $authentication = resolve(ApiAuthentication::class);
        $this->assertInstanceOf(User::class, $authentication->getUser());
        $this->assertModelEquals($user, $authentication->getUser());
    }

    /** @test */
    public function set_user_sets_the_user()
    {
        $user = $this->newUser();
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->getById($user->id())->shouldBeCalled()->willReturn($user);
        $this->app->instance(UserRepository::class, $userRepository->reveal());
        
        $authentication = resolve(ApiAuthentication::class);
        
        $authentication->setUser($user);
        $this->assertInstanceOf(User::class, $authentication->getUser());
        $this->assertEquals($user, $authentication->getUser());
    }

    /** @test */
    public function set_group_sets_the_group()
    {
        $group = $this->newGroup();
        $groupRepository = $this->prophesize(GroupRepository::class);
        $groupRepository->getById($group->id())->shouldBeCalled()->willReturn($group);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());

        $authentication = resolve(ApiAuthentication::class);

        $authentication->setGroup($group);
        $this->assertInstanceOf(Group::class, $authentication->getGroup());
        $this->assertEquals($group, $authentication->getGroup());
    }

    /** @test */
    public function set_role_sets_the_role()
    {
        $role = $this->newRole();
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById($role->id())->shouldBeCalled()->willReturn($role);
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $authentication = resolve(ApiAuthentication::class);

        $authentication->setRole($role);
        $this->assertInstanceOf(Role::class, $authentication->getRole());
        $this->assertEquals($role, $authentication->getRole());
    }
    
    /** @test */
    public function get_group_returns_null_if_exception_thrown_in_repository()
    {
        $query = $this->prophesize(ParameterBag::class);
        $query->has('role_id')->shouldBeCalled()->willReturn(false);
        $query->has('group_id')->shouldBeCalled()->willReturn(true);
        $query->get('group_id')->shouldBeCalled()->willReturn(1);

        $request = $this->prophesize(Request::class);
        $request->query = $query->reveal();
        
        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $groupRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());
        
        $authentication = new ApiAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($authentication->getGroup());
    }

    /** @test */
    public function get_role_returns_null_if_exception_thrown_in_repository()
    {
        $query = $this->prophesize(ParameterBag::class);
        $query->has('role_id')->shouldBeCalled()->willReturn(true);
        $query->get('role_id')->shouldBeCalled()->willReturn(1);
        
        $request = $this->prophesize(Request::class);
        $request->query = $query->reveal();

        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());

        $authentication = new ApiAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($authentication->getRole());
    }

    /** @test */
    public function get_user_returns_null_if_exception_thrown_in_repository()
    {
        $query = $this->prophesize(ParameterBag::class);
        $query->has('user_id')->shouldBeCalled()->willReturn(true);
        $query->get('user_id')->shouldBeCalled()->willReturn(1);

        $request = $this->prophesize(Request::class);
        $request->query = $query->reveal();

        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $userRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());

        $authentication = new ApiAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($authentication->getUser());
    }
    
    /** @test */
    public function reset_resets_the_query()
    {
        $request = $this->prophesize(Request::class);
        $query = $this->prophesize(ParameterBag::class);
        $query->set('user_id', null)->shouldBeCalled();
        $query->set('group_id', null)->shouldBeCalled();
        $query->set('role_id', null)->shouldBeCalled();
        $request->query = $query->reveal();
        
        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        
        $authentication = new ApiAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $authentication->reset();
    }
}
