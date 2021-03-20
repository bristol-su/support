<?php

namespace BristolSU\Support\Tests\Progress\Jobs;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\Progress\Jobs\UpdateProgress;
use BristolSU\Support\Progress\Jobs\UpdateProgressForGivenActivityInstances;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class UpdateProgressTest extends TestCase
{
    /** @test */
    public function it_dispatches_chunks_of_activity_instances()
    {
        Bus::fake(UpdateProgressForGivenActivityInstances::class);

        $activity = factory(Activity::class)->create();
        $activityInstances = factory(ActivityInstance::class, 83)->create(['activity_id' => $activity->id]);

        $activityInstanceRepository = $this->prophesize(ActivityInstanceRepository::class);
        $activityInstanceRepository->allForActivity($activity->id)->shouldBeCalled()->willReturn($activityInstances);

        $job = new UpdateProgress($activity, 'fake-setup');
        $job->handle($activityInstanceRepository->reveal());

        Bus::assertDispatchedTimes(UpdateProgressForGivenActivityInstances::class, 5);
    }
}
