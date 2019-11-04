<?php

namespace BristolSU\Support\Tests\Activity\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Middleware\CheckAdminActivityFor;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Prophecy\Argument;

class CheckAdminActivityForTest extends TestCase
{

    /** @test */
    public function it_throws_an_exception_if_the_logic_tester_returns_false(){
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage('You do not have access to this activity');
        
        $logic = factory(Logic::class)->create();
        $activity = factory(Activity::class)->create(['admin_logic' => $logic->id]);
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn($activity);
        $authentication = $this->prophesize(Authentication::class);
        
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 5]);
        $role = new Role(['id' => 10]);
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);
        
        $this->createLogicTester([], [$logic], $user, $group, $role);
        $middleware = new CheckAdminActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function(){ });
    }

    /** @test */
    public function it_calls_the_callback_if_the_logic_tester_returns_true(){
        $logic = factory(Logic::class)->create();
        $activity = factory(Activity::class)->create(['admin_logic' => $logic->id]);
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn($activity);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);
        
        $authentication = $this->prophesize(Authentication::class);

        $user = new User(['id' => 1]);
        $group = new Group(['id' => 5]);
        $role = new Role(['id' => 10]);
        $authentication->getUser()->willReturn($user);
        $authentication->getGroup()->willReturn($group);
        $authentication->getRole()->willReturn($role);

        $this->createLogicTester([$logic], [], $user, $group, $role);
        $middleware = new CheckAdminActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function($request){
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
    
}