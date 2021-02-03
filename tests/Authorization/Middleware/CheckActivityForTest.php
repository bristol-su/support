<?php

namespace BristolSU\Support\Tests\Authorization\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ActivityRequiresParticipant;
use BristolSU\Support\Authorization\Middleware\CheckActivityFor;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class CheckActivityForTest extends TestCase
{
    /** @test */
    public function it_throws_an__activity_requires_user_exception_if_user_not_in_activity_for()
    {
        $this->expectException(ActivityRequiresParticipant::class);
        
        $authentication = $this->prophesize(Authentication::class);
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);
        
        $logic = factory(Logic::class)->create();
        $this->logicTester()->forLogic($logic)->fail($user);
        $this->logicTester()->bind();
        
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'user', 'for_logic' => $logic->id])
        );
        $middleware = new CheckActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_throws_an__activity_requires_group_exception_if_group_not_in_activity_for()
    {
        $this->expectException(ActivityRequiresParticipant::class);
        
        $authentication = $this->prophesize(Authentication::class);
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);

        $logic = factory(Logic::class)->create();
        $this->logicTester()->forLogic($logic)->fail($user, $group);
        $this->logicTester()->bind();
        
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'group', 'for_logic' => $logic->id])
        );
        $middleware = new CheckActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function ($request) {
        });
    }

    /** @test */
    public function it_throws_an__activity_requires_role_exception_if_role_not_in_activity_for()
    {
        $this->expectException(ActivityRequiresParticipant::class);
        
        $authentication = $this->prophesize(Authentication::class);
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);

        $logic = factory(Logic::class)->create();
        $this->logicTester()->forLogic($logic)->fail($user, $group, $role);
        $this->logicTester()->bind();
        
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
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);

        $this->logicTester()->forLogic($activity->forLogic)->pass($user, $group, $role);
        $this->logicTester()->bind();
        
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
