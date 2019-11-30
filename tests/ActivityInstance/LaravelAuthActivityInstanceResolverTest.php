<?php

namespace BristolSU\Support\Tests\ActivityInstance;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\ActivityInstance\LaravelAuthActivityInstanceResolver;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Prophecy\Argument;

class LaravelAuthActivityInstanceResolverTest extends TestCase
{

    /** @test */
    public function setActivityInstance_sets_the_activity_instance(){
        $activityInstance = factory(ActivityInstance::class)->create();
        $guard = $this->prophesize(StatefulGuard::class);
        $guard->login(Argument::that(function($arg) use ($activityInstance) {
            return $activityInstance->is($arg);
        }))->shouldBeCalled();
        
        $authFactory = $this->prophesize(Factory::class);
        $authFactory->guard('activity-instance')->willReturn($guard->reveal());
        
        $resolver = new LaravelAuthActivityInstanceResolver($authFactory->reveal());
        $resolver->setActivityInstance($activityInstance);
    }
    
    /** @test */
    public function getActivityInstance_returns_the_activity_instance_if_set(){
        $activityInstance = factory(ActivityInstance::class)->create();
        $guard = $this->prophesize(StatefulGuard::class);
        $guard->check()->shouldBeCalled()->willReturn(true);
        $guard->user()->shouldBeCalled()->willReturn($activityInstance);

        $authFactory = $this->prophesize(Factory::class);
        $authFactory->guard('activity-instance')->willReturn($guard->reveal());

        $resolver = new LaravelAuthActivityInstanceResolver($authFactory->reveal());
        $this->assertModelEquals($activityInstance, $resolver->getActivityInstance());
    }
    
    /** @test */
    public function getActivityInstance_throws_an_exception_if_the_activity_instance_is_not_found(){
        $this->expectException(NotInActivityInstanceException::class);
        $this->expectExceptionMessage('No activity instance found');
        $this->expectExceptionCode(404);
        
        $activityInstance = factory(ActivityInstance::class)->create();
        $guard = $this->prophesize(StatefulGuard::class);
        $guard->check()->shouldBeCalled()->willReturn(false);

        $authFactory = $this->prophesize(Factory::class);
        $authFactory->guard('activity-instance')->willReturn($guard->reveal());

        $resolver = new LaravelAuthActivityInstanceResolver($authFactory->reveal());
        $resolver->getActivityInstance();
    }
    
    /** @test */
    public function clearActivityInstance_logs_out_of_the_activity_instance(){
        $activityInstance = factory(ActivityInstance::class)->create();
        $guard = $this->prophesize(StatefulGuard::class);
        $guard->logout()->shouldBeCalled();

        $authFactory = $this->prophesize(Factory::class);
        $authFactory->guard('activity-instance')->willReturn($guard->reveal());

        $resolver = new LaravelAuthActivityInstanceResolver($authFactory->reveal());
        $resolver->clearActivityInstance();
    }
    
}