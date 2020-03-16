<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\IncorrectLogin;
use BristolSU\Support\Authorization\Middleware\CheckDatabaseUserOwnsControlUser;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\Contracts\UserAuthentication;
use BristolSU\Support\User\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class CheckDatabaseUserOwnsControlUserTest extends TestCase
{

    /** @test */
    public function it_calls_the_next_request_if_the_database_user_is_not_logged_in(){
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldNotBeCalled();
        
        $userAuthentication = $this->prophesize(UserAuthentication::class);
        $userAuthentication->getUser()->shouldBeCalled()->willReturn(null);
        
        $middleware = new CheckDatabaseUserOwnsControlUser($userAuthentication->reveal(), $authentication->reveal());
        $middleware->handle($request->reveal(), function($request){
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
    
    /** @test */
    public function it_calls_the_next_request_if_the_control_user_is_not_logged_in(){
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $databaseUser = factory(User::class)->create();

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn(null);

        $userAuthentication = $this->prophesize(UserAuthentication::class);
        $userAuthentication->getUser()->shouldBeCalled()->willReturn($databaseUser);

        $middleware = new CheckDatabaseUserOwnsControlUser($userAuthentication->reveal(), $authentication->reveal());
        $middleware->handle($request->reveal(), function($request){
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
    
    /** @test */
    public function it_calls_the_next_request_if_the_control_user_is_owned_by_the_database_user(){
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $user = $this->newUser();
        $databaseUser = factory(User::class)->create(['control_id' => $user->id()]);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);

        $userAuthentication = $this->prophesize(UserAuthentication::class);
        $userAuthentication->getUser()->shouldBeCalled()->willReturn($databaseUser);

        $middleware = new CheckDatabaseUserOwnsControlUser($userAuthentication->reveal(), $authentication->reveal());
        $middleware->handle($request->reveal(), function($request){
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
    
    /** @test */
    public function it_throws_an_exception_if_the_control_user_is_different_to_the_database_user(){
        $this->expectException(IncorrectLogin::class);
        $this->expectExceptionMessage('Logged into incorrect user');
        
        $request = $this->prophesize(Request::class);

        $user = $this->newUser();
        $fakedUser = $this->newUser();
        $databaseUser = factory(User::class)->create(['control_id' => $fakedUser->id()]);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);

        $userAuthentication = $this->prophesize(UserAuthentication::class);
        $userAuthentication->getUser()->shouldBeCalled()->willReturn($databaseUser);

        $middleware = new CheckDatabaseUserOwnsControlUser($userAuthentication->reveal(), $authentication->reveal());
        $middleware->handle($request->reveal(), function($request){
        });
    }
    
}