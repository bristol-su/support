<?php

namespace BristolSU\Support\Tests\ActivityInstance;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\ApiActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;

class ApiActivityInstanceResolverTest extends TestCase
{
    /** @test */
    public function set_activity_instance_throws_an_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot set an activity instance when using the API');
        
        $resolver = new ApiActivityInstanceResolver($this->prophesize(Request::class)->reveal(), $this->prophesize(ActivityInstanceRepository::class)->reveal());
        
        $activityInstance = factory(ActivityInstance::class)->create();
        $resolver->setActivityInstance($activityInstance);
    }

    /** @test */
    public function clear_activity_instance_throws_an_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot clear an activity instance when using the API');

        $resolver = new ApiActivityInstanceResolver($this->prophesize(Request::class)->reveal(), $this->prophesize(ActivityInstanceRepository::class)->reveal());

        $resolver->clearActivityInstance();
    }
    
    /** @test */
    public function get_activity_instance_returns_an_activity_instance_using_the_request()
    {
        $request = $this->prophesize(Request::class);
        $repository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstance = factory(ActivityInstance::class)->create();
        
        $repository->getById($activityInstance->id)->shouldBeCalled()->willReturn($activityInstance);
        
        $request->has('activity_instance_id')->shouldBeCalled()->willReturn(true);
        $request->input('activity_instance_id')->shouldBeCalled()->willReturn($activityInstance->id);
        
        $resolver = new ApiActivityInstanceResolver($request->reveal(), $repository->reveal());
        $this->assertModelEquals($activityInstance, $resolver->getActivityInstance());
    }

    /** @test */
    public function get_activity_instance_throws_an_exception_if_activity_instance_not_passed_in_request()
    {
        $this->expectException(NotInActivityInstanceException::class);
        $request = $this->prophesize(Request::class);
        $repository = $this->prophesize(ActivityInstanceRepository::class);

        $request->has('activity_instance_id')->shouldBeCalled()->willReturn(false);

        $resolver = new ApiActivityInstanceResolver($request->reveal(), $repository->reveal());
        $resolver->getActivityInstance();
    }
}
