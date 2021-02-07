<?php

namespace BristolSU\Support\Tests\ActivityInstance\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\ActivityInstance\Middleware\CheckActivityInstanceAccessible;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckActivityInstanceAccessibleTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_if_the_group_activity_instance_is_not_logged_into_the_current_group()
    {
        $this->expectException(NotInActivityInstanceException::class);
        $this->expectExceptionMessage('Incorrect activity instance set');
        
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldNotBeCalled();

        $group = $this->newGroup();
        $activity = factory(Activity::class)->create(['activity_for' => 'group']);
        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'group', 'resource_id' => $group->id(), 'activity_id' => $activity->id]);
        
        app(ActivityInstanceResolver::class)->setActivityInstance($activityInstance);
        $this->beGroup($this->newGroup());

        $middleware = app(CheckActivityInstanceAccessible::class);
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }

    /** @test */
    public function it_throws_an_exception_if_the_user_activity_instance_is_not_logged_into_the_current_user()
    {
        $this->expectException(NotInActivityInstanceException::class);
        $this->expectExceptionMessage('Incorrect activity instance set');

        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldNotBeCalled();

        $user = $this->newUser();
        $activity = factory(Activity::class)->create(['activity_for' => 'user']);
        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'user', 'resource_id' => $user->id(), 'activity_id' => $activity->id]);

        app(ActivityInstanceResolver::class)->setActivityInstance($activityInstance);
        $this->beUser($this->newUser());

        $middleware = app(CheckActivityInstanceAccessible::class);
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }

    /** @test */
    public function it_throws_an_exception_if_the_role_activity_instance_is_not_logged_into_the_current_role()
    {
        $this->expectException(NotInActivityInstanceException::class);
        $this->expectExceptionMessage('Incorrect activity instance set');

        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldNotBeCalled();

        $role = $this->newRole();
        $activity = factory(Activity::class)->create(['activity_for' => 'role']);
        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'role', 'resource_id' => $role->id(), 'activity_id' => $activity->id]);

        app(ActivityInstanceResolver::class)->setActivityInstance($activityInstance);
        $this->beRole($this->newRole());

        $middleware = app(CheckActivityInstanceAccessible::class);
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }
    
    /** @test */
    public function it_calls_the_next_middleware_if_the_activity_instance_is_owned_by_the_user()
    {
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);
        
        $user = $this->newUser();
        $activity = factory(Activity::class)->create(['activity_for' => 'user']);
        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'user', 'resource_id' => $user->id(), 'activity_id' => $activity->id]);

        app(ActivityInstanceResolver::class)->setActivityInstance($activityInstance);
        $this->beUser($user);

        $middleware = app(CheckActivityInstanceAccessible::class);
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }

    /** @test */
    public function it_calls_the_next_middleware_if_the_activity_instance_is_owned_by_the_group()
    {
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);

        $group = $this->newGroup();
        $activity = factory(Activity::class)->create(['activity_for' => 'group']);
        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'group', 'resource_id' => $group->id(), 'activity_id' => $activity->id]);

        app(ActivityInstanceResolver::class)->setActivityInstance($activityInstance);
        $this->beGroup($group);

        $middleware = app(CheckActivityInstanceAccessible::class);
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }

    /** @test */
    public function it_calls_the_next_middleware_if_the_activity_instance_is_owned_by_the_role()
    {
        $request = $this->prophesize(Request::class);
        $request->route('test_callback_called')->shouldBeCalled()->willReturn(true);

        $role = $this->newRole();
        $activity = factory(Activity::class)->create(['activity_for' => 'role']);
        $activityInstance = factory(ActivityInstance::class)->create(['resource_type' => 'role', 'resource_id' => $role->id(), 'activity_id' => $activity->id]);

        app(ActivityInstanceResolver::class)->setActivityInstance($activityInstance);
        $this->beRole($role);

        $middleware = app(CheckActivityInstanceAccessible::class);
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue($request->route('test_callback_called'));
        });
    }
}
