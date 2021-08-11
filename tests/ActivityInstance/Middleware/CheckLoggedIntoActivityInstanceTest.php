<?php

namespace BristolSU\Support\Tests\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\ActivityInstance\Middleware\CheckLoggedIntoActivityInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckLoggedIntoActivityInstanceTest extends TestCase
{
    /** @test */
    public function handle_throws_an_exception_if_the_activity_instance_is_not_found()
    {
        $this->expectException(NotInActivityInstanceException::class);
        
        $activityInstance = ActivityInstance::factory()->create();

        $resolver = $this->prophesize(ActivityInstanceResolver::class);
        $resolver->getActivityInstance()->willThrow(new NotInActivityInstanceException());

        $request = $this->prophesize(Request::class);

        $middleware = new CheckLoggedIntoActivityInstance($resolver->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function handle_calls_the_callback_if_the_activity_instance_is_found()
    {
        $activityInstance = ActivityInstance::factory()->create();

        $resolver = $this->prophesize(ActivityInstanceResolver::class);
        $resolver->getActivityInstance()->willReturn($activityInstance);

        $request = $this->prophesize(Request::class);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $middleware = new CheckLoggedIntoActivityInstance($resolver->reveal());
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
}
