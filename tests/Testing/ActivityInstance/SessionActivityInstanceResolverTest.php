<?php

namespace BristolSU\Support\Tests\Testing\ActivityInstance;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Testing\ActivityInstance\SessionActivityInstanceResolver;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Session\Session;

class SessionActivityInstanceResolverTest extends TestCase
{
    /** @test */
    public function set_activity_instance_sets_the_activity_instance()
    {
        $activityInstance = factory(ActivityInstance::class)->create();

        $session = $this->prophesize(Session::class);
        $session->put('activity-instance', $activityInstance->id)->shouldBeCalled();

        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->getById($activityInstance->id)->willReturn($activityInstance);

        $resolver = new SessionActivityInstanceResolver($session->reveal(), $activityInstanceRepository->reveal());
        $resolver->setActivityInstance($activityInstance);
    }

    /** @test */
    public function get_activity_instance_returns_the_activity_instance_if_set()
    {
        $activityInstance = factory(ActivityInstance::class)->create();

        $session = $this->prophesize(Session::class);
        $session->has('activity-instance')->willReturn(true);
        $session->get('activity-instance')->shouldBeCalled()->willReturn($activityInstance->id);

        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->getById($activityInstance->id)->willReturn($activityInstance);

        $resolver = new SessionActivityInstanceResolver($session->reveal(), $activityInstanceRepository->reveal());

        $this->assertModelEquals($activityInstance, $resolver->getActivityInstance());
    }

    /** @test */
    public function get_activity_instance_throws_an_exception_if_the_activity_instance_is_not_found_in_the_repository()
    {
        $this->expectException(NotInActivityInstanceException::class);
        $this->expectExceptionMessage('No activity instance with an id 5000 found');
        $this->expectExceptionCode(404);

        $session = $this->prophesize(Session::class);
        $session->has('activity-instance')->willReturn(true);
        $session->get('activity-instance')->shouldBeCalled()->willReturn(5000);

        $activityInstanceRepository = app(ActivityInstanceRepository::class);

        $resolver = new SessionActivityInstanceResolver($session->reveal(), $activityInstanceRepository);
        $resolver->getActivityInstance();
    }

    /** @test */
    public function get_activity_instance_throws_an_exception_if_the_activity_instance_is_not_found_in_the_session()
    {
        $this->expectException(NotInActivityInstanceException::class);
        $this->expectExceptionMessage('No activity instance found');
        $this->expectExceptionCode(404);

        $session = $this->prophesize(Session::class);
        $session->has('activity-instance')->shouldBeCalled()->willReturn(false);

        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);

        $resolver = new SessionActivityInstanceResolver($session->reveal(), $activityInstanceRepository->reveal());
        $resolver->getActivityInstance();
    }

    /** @test */
    public function clear_activity_instance_logs_out_of_the_activity_instance()
    {
        $session = $this->prophesize(Session::class);
        $session->remove('activity-instance')->shouldBeCalled();

        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);

        $resolver = new SessionActivityInstanceResolver($session->reveal(), $activityInstanceRepository->reveal());
        $resolver->clearActivityInstance();
    }
}
