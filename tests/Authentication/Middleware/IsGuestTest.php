<?php

namespace BristolSU\Support\Tests\Authentication\Middleware;

use BristolSU\Auth\Settings\Access\DefaultHome;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Exception\IsAuthenticatedException;
use BristolSU\Support\Authentication\Middleware\IsGuest;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class IsGuestTest extends TestCase
{

    /** @test */
    public function it_throws_an_exception_if_a_user_is_logged_in(){
        $this->expectException(IsAuthenticatedException::class);

        $request = $this->prophesize(Request::class);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->hasUser()->shouldBeCalled()->willReturn(true);

        $middleware = new IsGuest($authentication->reveal());

        $response = $middleware->handle($request->reveal(), function($paramRequest) use ($request) {
            $this->assertTrue(false, 'The callback was called.');
        });

    }

    /** @test */
    public function it_calls_the_callback_if_a_user_is_not_logged_in(){
        $request = $this->prophesize(Request::class);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->hasUser()->shouldBeCalled()->willReturn(false);

        $middleware = new IsGuest($authentication->reveal());

        $this->assertTrue(
            $middleware->handle($request->reveal(), function($paramRequest) use ($request) {
                $this->assertSame($paramRequest, $request->reveal());
                return true;
            })
        );
    }

}
