<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authorization\Exception\ActivityDisabled;
use BristolSU\Support\Authorization\Middleware\CheckActivityEnabled;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckActivityEnabledTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_if_the_activity_is_not_enabled()
    {
        $this->expectException(ActivityDisabled::class);
        
        $activity = factory(Activity::class)->create(['enabled' => false]);
        
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->shouldBeCalled()->willReturn($activity);
        $request->route('test_callback')->shouldNotBeCalled();
        
        $middleware = new CheckActivityEnabled();
        $middleware->handle($request->reveal(), function ($request) {
            $request->route('test_callback');
        });
    }

    /** @test */
    public function it_calls_the_callback_if_the_activity_is_enabled()
    {
        $activity = factory(Activity::class)->create(['enabled' => true]);

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->shouldBeCalled()->willReturn($activity);
        $request->route('test_callback')->shouldBeCalled()->willReturn(true);

        $middleware = new CheckActivityEnabled();
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback'));
        });
    }
}
