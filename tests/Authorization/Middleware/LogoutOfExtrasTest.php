<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Middleware\LogoutOfExtras;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class LogoutOfExtrasTest extends TestCase
{
    /** @test */
    public function it_calls_reset_on_the_authentication()
    {
        $authentication = $this->prophesize(Authentication::class);
        $authentication->reset()->shouldBeCalled();
        
        $middleware = new LogoutOfExtras($authentication->reveal());
        
        $request = $this->prophesize(Request::class);
        $middleware->handle($request->reveal(), function () {
        });
    }
    
    /** @test */
    public function it_calls_the_callback()
    {
        $authentication = $this->prophesize(Authentication::class);
        $authentication->reset()->shouldBeCalled();

        $middleware = new LogoutOfExtras($authentication->reveal());

        $request = $this->prophesize(Request::class);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
}
