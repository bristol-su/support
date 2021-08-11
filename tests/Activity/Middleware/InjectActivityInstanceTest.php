<?php


namespace BristolSU\Support\Tests\Activity\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Middleware\InjectActivity;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Prophecy\Argument;

class InjectActivityInstanceTest extends TestCase
{
    /** @test */
    public function it_passes_the_activity_in_the_request_to_the_container()
    {
        $activity = Activity::factory()->create();
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->shouldBeCalled()->willReturn($activity);

        $app = $this->prophesize(Container::class);
        $app->instance(Activity::class, Argument::that(function ($arg) use ($activity) {
            return $arg->id === $activity->id;
        }))->shouldBeCalled();

        $middleware = new InjectActivity($app->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }
}
