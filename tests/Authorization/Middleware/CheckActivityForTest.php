<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ActivityRequiresGroup;
use BristolSU\Support\Authorization\Exception\ActivityRequiresRole;
use BristolSU\Support\Authorization\Exception\ActivityRequiresUser;
use BristolSU\Support\Authorization\Middleware\CheckActivityFor;
use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckActivityForTest extends TestCase
{
    /** @test */
    public function it_throws_an_ActivityRequiresUser_exception_if_user_not_in_activity_for()
    {
        $this->expectException(ActivityRequiresUser::class);
        $authentication = $this->prophesize(Authentication::class);
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 5]);
        $role = new Role(['id' => 10]);
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);
        $logic = factory(Logic::class)->create();
        $this->createLogicTester([], [$logic], $user, null, null);
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'user', 'for_logic' => $logic->id])
        );
        $middleware = new CheckActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_throws_an_ActivityRequiresGroup_exception_if_group_not_in_activity_for()
    {
        $this->expectException(ActivityRequiresGroup::class);
        $authentication = $this->prophesize(Authentication::class);
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 5]);
        $role = new Role(['id' => 10]);
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);
        $logic = factory(Logic::class)->create();
        $this->createLogicTester([], [$logic], $user, $group, null);
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'group', 'for_logic' => $logic->id])
        );
        $middleware = new CheckActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_throws_an_ActivityRequiresRole_exception_if_role_not_in_activity_for()
    {
        $this->expectException(ActivityRequiresRole::class);
        $authentication = $this->prophesize(Authentication::class);
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);
        $logic = factory(Logic::class)->create();
        $this->createLogicTester([], [$logic], $user, $group, $role);
        
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'role', 'for_logic' => $logic->id])
        );
        $middleware = new CheckActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_calls_the_callback()
    {
        $activity = factory(Activity::class)->create([
            'activity_for' => 'user'
        ]);
        $authentication = $this->prophesize(Authentication::class);
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 5]);
        $role = new Role(['id' => 10]);
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);
        $this->createLogicTester([$activity->forLogic], [], $user, $group, $role);
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn($activity);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);
        $middleware = new CheckActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
}