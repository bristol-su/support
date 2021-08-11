<?php

namespace BristolSU\Support\Tests\ActivityInstance\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\ActivityInstance\Middleware\CheckActivityInstanceForActivity;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckActivityInstanceForActivityTest extends TestCase
{
    /** @test */
    public function handle_throws_an_exception_if_the_activity_instance_activity_id_is_not_equal_to_the_activity_id()
    {
        $this->expectException(NotInActivityInstanceException::class);
        $this->expectExceptionMessage('Not logged into the correct activity instance for the activity');
        
        $activity1 = Activity::factory()->create();
        $activity2 = Activity::factory()->create();
        $activityInstance = ActivityInstance::factory()->create(['activity_id' => $activity1->id]);
        
        $resolver = $this->prophesize(ActivityInstanceResolver::class);
        $resolver->getActivityInstance()->willReturn($activityInstance);
        
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn($activity2);
        
        $middleware = new CheckActivityInstanceForActivity($resolver->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }
    
    /** @test */
    public function handle_calls_the_callback_if_the_activity_instance_activity_id_is_equal_to_the_activity_id()
    {
        $activity1 = Activity::factory()->create();
        $activityInstance = ActivityInstance::factory()->create(['activity_id' => $activity1->id]);

        $resolver = $this->prophesize(ActivityInstanceResolver::class);
        $resolver->getActivityInstance()->willReturn($activityInstance);

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn($activity1);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $middleware = new CheckActivityInstanceForActivity($resolver->reveal());
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
}
