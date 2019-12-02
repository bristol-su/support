<?php

namespace BristolSU\Support\Tests\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Middleware\InjectActivityInstance;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class InjectActivityInstanceTest extends TestCase
{

    /** @test */
    public function it_injects_the_activity_instance(){
        $activityInstance = factory(ActivityInstance::class)->create();
        
        $app = $this->prophesize(Application::class);
        $app->instance(ActivityInstance::class, Argument::that(function($arg) use ($activityInstance) {
            return $activityInstance->is($arg);
        }))->shouldBeCalled();
        
        $resolver = $this->prophesize(ActivityInstanceResolver::class);
        $resolver->getActivityInstance()->shouldBeCalled()->willReturn($activityInstance);
        
        $request = $this->prophesize(Request::class);
        
        $middleware = new InjectActivityInstance($app->reveal(), $resolver->reveal());
        $middleware->handle($request->reveal(), function($request) {
            
        });
    }

    /** @test */
    public function it_calls_the_callback(){
        $activityInstance = factory(ActivityInstance::class)->create();

        $app = $this->prophesize(Application::class);

        $resolver = $this->prophesize(ActivityInstanceResolver::class);
        $resolver->getActivityInstance()->willReturn($activityInstance);

        $request = $this->prophesize(Request::class);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $middleware = new InjectActivityInstance($app->reveal(), $resolver->reveal());
        $middleware->handle($request->reveal(), function($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
    
}