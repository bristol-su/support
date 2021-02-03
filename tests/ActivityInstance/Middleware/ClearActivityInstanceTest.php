<?php

namespace BristolSU\Support\Tests\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Middleware\ClearActivityInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class ClearActivityInstanceTest extends TestCase
{
    /** @test */
    public function handle_clears_the_activity_instance_and_calls_the_callback()
    {
        $resolver = $this->prophesize(ActivityInstanceResolver::class);
        $resolver->clearActivityInstance()->shouldBeCalled();

        $request = $this->prophesize(Request::class);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $middleware = new ClearActivityInstance($resolver->reveal());
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
}
