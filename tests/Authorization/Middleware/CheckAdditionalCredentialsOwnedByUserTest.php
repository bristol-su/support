<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\ControlDB\Contracts\Repositories\Pivots\UserGroup;
use BristolSU\ControlDB\Contracts\Repositories\Pivots\UserRole;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\IncorrectLogin;
use BristolSU\Support\Authorization\Middleware\CheckAdditionalCredentialsOwnedByUser;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckAdditionalCredentialsOwnedByUserTest extends TestCase
{

    /** @test */
    public function the_next_middleware_is_called_if_no_user_logged_in_to_authentication(){
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);
        
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        
        $middleware = new CheckAdditionalCredentialsOwnedByUser($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }

    /** @test */
    public function the_next_middleware_is_called_if_only_user_logged_in_to_authentication(){
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);

        $user = $this->newUser();
        
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $middleware = new CheckAdditionalCredentialsOwnedByUser($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }
    
    /** @test */
    public function the_next_middleware_is_called_if_the_user_has_a_membership_to_the_logged_in_group_and_no_role_logged_in(){
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);

        $user = $this->newUser();
        $group = $this->newGroup();
        app(UserGroup::class)->addUserToGroup($user, $group);
        
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $middleware = new CheckAdditionalCredentialsOwnedByUser($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }
    
    /** @test */
    public function the_next_middleware_is_called_if_the_user_is_in_the_logged_in_role_and_the_logged_in_group_belongs_to_the_role(){
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);

        $user = $this->newUser();
        $role = $this->newRole();
        app(UserRole::class)->addUserToRole($user, $role);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($role->group());
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $middleware = new CheckAdditionalCredentialsOwnedByUser($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }
    
    /** @test */
    public function an_exception_is_thrown_if_the_user_does_not_have_a_membership_to_the_logged_in_group_and_no_role_logged_in(){
        $this->expectException(IncorrectLogin::class);
        $this->expectExceptionMessage('The user must have a membership to the group');

        $request = $this->prophesize(Request::class);

        $user = $this->newUser();
        $group = $this->newGroup();

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $authentication->getRole()->shouldBeCalled()->willReturn(null);

        $middleware = new CheckAdditionalCredentialsOwnedByUser($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
        });
    }
    
    /** @test */
    public function an_exception_is_thrown_if_the_user_does_not_belong_to_the_role_but_the_group_logged_in_does(){
        $this->expectException(IncorrectLogin::class);
        $this->expectExceptionMessage('The user must own the current role');

        $request = $this->prophesize(Request::class);

        $user = $this->newUser();
        $role = $this->newRole();
        
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->willReturn($role->group());
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $middleware = new CheckAdditionalCredentialsOwnedByUser($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
        });
    }
    
    /** @test */
    public function an_exception_is_thrown_if_the_user_belongs_to_the_role_but_the_logged_in_group_does_not(){
        $this->expectException(IncorrectLogin::class);
        $this->expectExceptionMessage('The group must belong to the current role');

        $request = $this->prophesize(Request::class);

        $user = $this->newUser();
        $role = $this->newRole();
        app(UserRole::class)->addUserToRole($user, $role);
        $fakeGroup = $this->newGroup();
        
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($fakeGroup);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $middleware = new CheckAdditionalCredentialsOwnedByUser($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
        });
    }

    /** @test */
    public function an_exception_is_thrown_if_a_role_is_logged_in_but_no_group_is(){
        $this->expectException(IncorrectLogin::class);
        $this->expectExceptionMessage('The group must belong to the current role');

        $request = $this->prophesize(Request::class);

        $user = $this->newUser();
        $role = $this->newRole();
        app(UserRole::class)->addUserToRole($user, $role);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn(null);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $middleware = new CheckAdditionalCredentialsOwnedByUser($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
        });
    }
    
}