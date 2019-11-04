<?php

namespace BristolSU\Support\Tests\Activity\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Exception\ActivityRequiresGroup;
use BristolSU\Support\Activity\Exception\ActivityRequiresRole;
use BristolSU\Support\Activity\Exception\ActivityRequiresUser;
use BristolSU\Support\Activity\Middleware\CheckActivityFor;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckActivityForTest extends TestCase
{

    /** @test */
    public function it_throws_an_ActivityRequiresUser_exception_if_user_not_in_activity_for(){
        $this->expectException(ActivityRequiresUser::class);
        $authentication = $this->prophesize(Authentication::class);
        $user = new User(['id' => 1]);
        $logic = factory(Logic::class)->create();
        $this->createLogicTester([], [$logic], $user, null, null);
        $authentication->getUser()->willReturn($user);

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'user', 'for_logic' => $logic->id])
        );

        $middleware = new CheckActivityFor($authentication->reveal());

        $middleware->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_throws_an_ActivityRequiresGroup_exception_if_group_not_in_activity_for(){
        $this->expectException(ActivityRequiresGroup::class);
        $authentication = $this->prophesize(Authentication::class);
        $group = new Group(['id' => 1]);
        $logic = factory(Logic::class)->create();
        $this->createLogicTester([], [$logic], null, $group, null);
        $authentication->getGroup()->willReturn($group);

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'group', 'for_logic' => $logic->id])
        );

        $middleware = new CheckActivityFor($authentication->reveal());

        $middleware->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_throws_an_ActivityRequiresRole_exception_if_role_not_in_activity_for(){
        $this->expectException(ActivityRequiresRole::class);
        $authentication = $this->prophesize(Authentication::class);
        $role = new Role(['id' => 1]);
        $logic = factory(Logic::class)->create();
        $this->createLogicTester([], [$logic], null, null, $role);
        
        $authentication->getRole()->willReturn($role);

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'role', 'for_logic' => $logic->id])
        );

        $middleware = new CheckActivityFor($authentication->reveal());

        $middleware->handle($request->reveal(), function($request) {});
    }



    /** @test */
    public function it_calls_the_callback(){
        $activity = factory(Activity::class)->create([
            'activity_for' => 'user'
        ]);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->willReturn(new User(['id' => 1]));

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn($activity);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);
        $middleware = new CheckActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
    
}