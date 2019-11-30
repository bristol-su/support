<?php

namespace BristolSU\Support\Tests\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Middleware\CheckLoggedIntoActivityInstance;
use BristolSU\Support\ActivityInstance\Middleware\LogIntoActivityInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;
use Prophecy\Argument;

class LogIntoActivityInstanceTest extends TestCase
{
    
    /** @test */
    public function handle_sets_the_activity_instance_if_given_in_the_request(){
        $activityInstance = factory(ActivityInstance::class)->create();

        $repository = $this->prophesize(ActivityInstanceRepository::class);
        $repository->getById(5)->shouldBeCalled()->willReturn($activityInstance);
        
        $resolver = $this->prophesize(ActivityInstanceResolver::class);
        $resolver->setActivityInstance(Argument::that(function($arg) use ($activityInstance) {
            return $activityInstance->is($arg);
        }))->shouldBeCalled();

        $request = $this->prophesize(Request::class);
        $request->has('aiid')->shouldBeCalled()->willReturn(true);
        $request->input('aiid')->shouldBeCalled()->willReturn(5);
        
        $middleware = new LogIntoActivityInstance($repository->reveal(), $resolver->reveal());
        $middleware->handle($request->reveal(), function($request) {
        });
    }
    
    /** @test */
    public function handle_calls_the_callback(){
        $activityInstance = factory(ActivityInstance::class)->create();

        $repository = $this->prophesize(ActivityInstanceRepository::class);
        $resolver = $this->prophesize(ActivityInstanceResolver::class);

        $request = $this->prophesize(Request::class);
        $request->has('aiid')->shouldBeCalled()->willReturn(false);
        $request->route('test_callback_is_called')->shouldBeCalled()->willReturn(true);

        $middleware = new LogIntoActivityInstance($repository->reveal(), $resolver->reveal());
        $middleware->handle($request->reveal(), function($request) {
            $this->assertTrue(
                $request->route('test_callback_is_called')
            );
        });
    }
    
}