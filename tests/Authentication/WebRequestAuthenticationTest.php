<?php

namespace BristolSU\Support\Tests\Authentication;

use BristolSU\Support\Authentication\WebRequestAuthentication;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use Illuminate\Http\Request;
use BristolSU\Support\Tests\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;

class WebRequestAuthenticationTest extends TestCase
{

    /** @test */
    public function get_group_returns_null_if_not_logged_into_a_group_or_role()
    {
        $authentication = resolve(WebRequestAuthentication::class);
        $this->assertNull($authentication->getGroup());
    }

    /** @test */
    public function get_group_returns_a_group_if_given_in_url()
    {
        $group = $this->newGroup();
        $groupRepository = $this->prophesize(GroupRepository::class);
        $groupRepository->getById($group->id())->shouldBeCalled()->willReturn($group);
        $this->app->instance(GroupRepository::class, $groupRepository->reveal());

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->has('g')->shouldBeCalled()->willReturn(true);
        $parameterBag->get('g')->shouldBeCalled()->willReturn($group->id());
        
        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        $this->app->instance(Request::class, $request->reveal());

        $authentication = resolve(WebRequestAuthentication::class);
        
        $this->assertInstanceOf(Group::class, $authentication->getGroup());
        $this->assertModelEquals($group, $authentication->getGroup());
        $this->assertEquals(1, $authentication->getGroup()->id);
    }

    /** @test */
    public function get_role_returns_null_if_not_logged_into_role()
    {
        $authentication = resolve(WebRequestAuthentication::class);
        $this->assertNull($authentication->getRole());
    }

    /** @test */
    public function get_role_returns_a_role_if_given_in_query()
    {
        
        $role = $this->newRole();
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById($role->id())->shouldBeCalled()->willReturn($role);
        $this->app->instance(RoleRepository::class, $roleRepository->reveal());

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->has('r')->shouldBeCalled()->willReturn(true);
        $parameterBag->get('r')->shouldBeCalled()->willReturn($role->id());

        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        $this->app->instance(Request::class, $request->reveal());
        
        $authentication = resolve(WebRequestAuthentication::class);
        $this->assertInstanceOf(Role::class, $authentication->getRole());
        $this->assertModelEquals($role, $authentication->getRole());
    }

    /** @test */
    public function get_user_returns_null_if_not_logged_into_a_user()
    {
        $authentication = resolve(WebRequestAuthentication::class);
        $this->assertNull($authentication->getUser());
    }

    /** @test */
    public function get_user_returns_a_user_if_given_in_query()
    {
        $user = $this->newUser();
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->getById($user->id())->shouldBeCalled()->willReturn($user);
        $this->app->instance(UserRepository::class, $userRepository->reveal());

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->has('u')->shouldBeCalled()->willReturn(true);
        $parameterBag->get('u')->shouldBeCalled()->willReturn($user->id());

        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        $this->app->instance(Request::class, $request->reveal());
        
        $authentication = resolve(WebRequestAuthentication::class);
        $this->assertInstanceOf(User::class, $authentication->getUser());
        $this->assertModelEquals($user, $authentication->getUser());
    }

    /** @test */
    public function set_user_sets_the_user()
    {
        $user = $this->newUser();

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->set('u', $user->id())->shouldBeCalled();
        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        $request->overrideGlobals()->shouldBeCalled();
        $this->app->instance(Request::class, $request->reveal());
        
        $authentication = resolve(WebRequestAuthentication::class);
        
        $authentication->setUser($user);
    }

    /** @test */
    public function set_group_sets_the_group()
    {
        $group = $this->newGroup();

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->set('g', $group->id())->shouldBeCalled();
        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        $request->overrideGlobals()->shouldBeCalled();
        $this->app->instance(Request::class, $request->reveal());
        
        $authentication = resolve(WebRequestAuthentication::class);

        $authentication->setGroup($group);
    }

    /** @test */
    public function set_role_sets_the_role()
    {
        $role = $this->newRole();

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->set('r', $role->id())->shouldBeCalled();
        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        $request->overrideGlobals()->shouldBeCalled();
        $this->app->instance(Request::class, $request->reveal());
        
        $authentication = resolve(WebRequestAuthentication::class);

        $authentication->setRole($role);
    }
    
    /** @test */
    public function getGroup_returns_null_if_exception_thrown_in_repository(){
        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->has('g')->shouldBeCalled()->willReturn(true);
        $parameterBag->get('g')->shouldBeCalled()->willReturn(1);

        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        
        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $groupRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());
        
        $authentication = new WebRequestAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($authentication->getGroup());
    }

    /** @test */
    public function getRole_returns_null_if_exception_thrown_in_repository(){
        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->has('r')->shouldBeCalled()->willReturn(true);
        $parameterBag->get('r')->shouldBeCalled()->willReturn(1);

        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();

        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $roleRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());

        $authentication = new WebRequestAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($authentication->getRole());
    }

    /** @test */
    public function getUser_returns_null_if_exception_thrown_in_repository(){
        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->has('u')->shouldBeCalled()->willReturn(true);
        $parameterBag->get('u')->shouldBeCalled()->willReturn(1);

        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();

        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        $userRepository->getById(1)->shouldBeCalled()->willThrow(new \Exception());

        $authentication = new WebRequestAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $this->assertNull($authentication->getUser());
    }
    
    /** @test */
    public function reset_resets_the_query(){
        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->remove('u')->shouldBeCalled();
        $parameterBag->remove('g')->shouldBeCalled();
        $parameterBag->remove('r')->shouldBeCalled();
        
        $request = $this->prophesize(Request::class);
        $request->overrideGlobals()->shouldBeCalled();
        $request->query = $parameterBag->reveal();
        
        $groupRepository = $this->prophesize(GroupRepository::class);
        $userRepository = $this->prophesize(UserRepository::class);
        $roleRepository = $this->prophesize(RoleRepository::class);
        
        $authentication = new WebRequestAuthentication($request->reveal(), $roleRepository->reveal(), $groupRepository->reveal(), $userRepository->reveal());

        $authentication->reset();
    }
    
}
