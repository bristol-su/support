<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Middleware\CheckAdminIsAtLeastUser;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\Contracts\UserAuthentication;
use BristolSU\Support\User\User;
use Illuminate\Http\Request;
use Prophecy\Argument;

class CheckAdminIsAtLeastUserTest extends TestCase
{
    /** @test */
    public function it_sets_the_user_if_none_logged_in()
    {
        $controlUser = $this->newUser();
        $databaseUser = factory(User::class)->create(['control_id' => $controlUser->id()]);
        
        $authentication = $this->prophesize(Authentication::class);
        $userAuthentication = $this->prophesize(UserAuthentication::class);
        
        $authentication->getUser()->shouldBeCalled()->willReturn(null);
        $userAuthentication->getUser()->shouldBeCalled()->willReturn($databaseUser);
        $authentication->setUser(Argument::that(function ($arg) use ($controlUser) {
            return $arg instanceof \BristolSU\ControlDB\Contracts\Models\User && $arg->id() === $controlUser->id();
        }))->shouldBeCalled();
        
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);
        
        $middleware = new CheckAdminIsAtLeastUser($authentication->reveal(), $userAuthentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }

    /** @test */
    public function it_calls_the_callback_if_a_user_is_already_logged_in()
    {
        $controlUser = $this->newUser();

        $authentication = $this->prophesize(Authentication::class);
        $userAuthentication = $this->prophesize(UserAuthentication::class);

        $authentication->getUser()->shouldBeCalled()->willReturn($controlUser);
        $userAuthentication->getUser()->shouldNotBeCalled();
        
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);

        $middleware = new CheckAdminIsAtLeastUser($authentication->reveal(), $userAuthentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }
}
