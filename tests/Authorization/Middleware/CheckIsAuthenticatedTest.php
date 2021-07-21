<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Middleware\CheckIsAuthenticated;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class CheckIsAuthenticatedTest extends TestCase
{

    /** @test */
    public function it_throws_an_exception_if_a_user_is_not_logged_in(){
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Unauthenticated.');

        $authentication = $this->prophesize(Authentication::class);
        $authentication->hasUser()->shouldBeCalled()->willReturn(false);

        $request = $this->prophesize(Request::class);

        $middleware = new CheckIsAuthenticated($authentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {});
    }

    /** @test */
    public function it_calls_the_callback_if_a_user_is_logged_in(){
        $authentication = $this->prophesize(Authentication::class);
        $authentication->hasUser()->shouldBeCalled()->willReturn(true);

        $request = $this->prophesize(Request::class);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $middleware = new CheckIsAuthenticated($authentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }

}
