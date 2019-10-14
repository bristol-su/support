<?php

namespace BristolSU\Support\Tests\Activity\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Exception\ActivityRequiresGroup;
use BristolSU\Support\Activity\Exception\ActivityRequiresRole;
use BristolSU\Support\Activity\Exception\ActivityRequiresUser;
use BristolSU\Support\Activity\Middleware\CheckLoggedIntoActivityFor;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;
use Illuminate\Http\Request;

class CheckLoggedIntoActivityForTest extends TestCase
{

    /** @test */
    public function it_throws_an_ActivityRequiresUser_exception_if_user_not_logged_in_when_required(){
        $this->expectException(ActivityRequiresUser::class);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->willReturn(null);

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'user'])
        );
        
        $middleware = new CheckLoggedIntoActivityFor($authentication->reveal());
        
        $middleware->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_throws_an_ActivityRequiresGroup_exception_if_group_not_logged_in_when_required(){
        $this->expectException(ActivityRequiresGroup::class);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getGroup()->willReturn(null);

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'group'])
        );

        $middleware = new CheckLoggedIntoActivityFor($authentication->reveal());

        $middleware->handle($request->reveal(), function($request) {});
    }

    /** @test */
    public function it_throws_an_ActivityRequiresRole_exception_if_role_not_logged_in_when_required(){
        $this->expectException(ActivityRequiresRole::class);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getRole()->willReturn(null);

        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn(
            factory(Activity::class)->create(['activity_for' => 'role'])
        );

        $middleware = new CheckLoggedIntoActivityFor($authentication->reveal());

        $middleware->handle($request->reveal(), function($request) {});
    }
    
    
    
    /** @test */
    public function it_calls_the_callback(){
        $activity = factory(Activity::class)->create([
            'activity_for' => 'user'
        ]);
        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->willReturn(factory(User::class)->create());
        $authentication->getGroup()->willReturn(new Group(['id' => 1]));
        $authentication->getRole()->willReturn(new Role(['id' => 1]));
        
        $request = $this->prophesize(Request::class);
        $request->route('activity_slug')->willReturn($activity);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);
        $middleware = new CheckLoggedIntoActivityFor($authentication->reveal());
        $middleware->handle($request->reveal(), function($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
    
}