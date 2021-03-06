<?php

namespace BristolSU\Support\Tests\ActivityInstance;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\ActivityInstance\WebRequestActivityInstanceResolver;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class WebRequestActivityInstanceResolverTest extends TestCase
{
    /** @test */
    public function set_activity_instance_sets_the_exception_and_overrides_the_global_variables()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        
        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->set('a', $activityInstance->id)->shouldBeCalled();
        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        $request->overrideGlobals()->shouldBeCalled();

        $resolver = new WebRequestActivityInstanceResolver($request->reveal(), $this->prophesize(ActivityInstanceRepository::class)->reveal());
        $resolver->setActivityInstance($activityInstance);
    }

    /** @test */
    public function clear_activity_instance_clears_the_activity_instance()
    {
        $activityInstance = factory(ActivityInstance::class)->create();

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->remove('a')->shouldBeCalled();
        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();
        $request->overrideGlobals()->shouldBeCalled();

        $resolver = new WebRequestActivityInstanceResolver($request->reveal(), $this->prophesize(ActivityInstanceRepository::class)->reveal());
        $resolver->clearActivityInstance();
    }
    
    /** @test */
    public function get_activity_instance_returns_an_activity_instance_using_the_request()
    {
        $activityInstance = factory(ActivityInstance::class)->create();

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->has('a')->willReturn(true);
        $parameterBag->get('a')->willReturn($activityInstance->id);
        
        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();

        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->getById($activityInstance->id)->shouldBeCalled()->willReturn($activityInstance);
        
        $resolver = new WebRequestActivityInstanceResolver($request->reveal(), $activityInstanceRepository->reveal());
        $resolvedActivityInstance = $resolver->getActivityInstance();
        $this->assertInstanceOf(ActivityInstance::class, $resolvedActivityInstance);
        $this->assertModelEquals($activityInstance, $resolvedActivityInstance);
    }

    /** @test */
    public function get_activity_instance_throws_an_exception_if_activity_instance_not_passed_in_request()
    {
        $this->expectException(NotInActivityInstanceException::class);
        $request = $this->prophesize(Request::class);

        $parameterBag = $this->prophesize(ParameterBag::class);
        $parameterBag->has('a')->willReturn(false);

        $request = $this->prophesize(Request::class);
        $request->query = $parameterBag->reveal();

        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);

        $resolver = new WebRequestActivityInstanceResolver($request->reveal(), $this->prophesize(ActivityInstanceRepository::class)->reveal());
        $resolver->getActivityInstance();
    }
}
