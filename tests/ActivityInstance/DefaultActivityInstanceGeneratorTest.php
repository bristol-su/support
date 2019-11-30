<?php

namespace BristolSU\Support\Tests\ActivityInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\DefaultActivityInstanceGenerator;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DefaultActivityInstanceGeneratorTest extends TestCase
{

    /** @test */
    public function generate_returns_the_first_activity_instance(){
        $activity = factory(Activity::class)->create();
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => $activity]);
        $repository = $this->prophesize(ActivityInstanceRepository::class);
        $repository->firstFor($activity->id, 'user', 3)->shouldBeCalled()->willReturn($activityInstance1);
        
        $generator = new DefaultActivityInstanceGenerator($repository->reveal());
        $this->assertModelEquals($activityInstance1, $generator->generate($activity, 'user', 3));
    }
    
    /** @test */
    public function generate_creates_and_returns_an_activity_if_none_found(){
        $activity = factory(Activity::class)->create(['id' => 20, 'name' => 'activity1']);
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 20]);
        $repository = $this->prophesize(ActivityInstanceRepository::class);
        $repository->firstFor(20, 'user', 3)->shouldBeCalled()->willThrow(new ModelNotFoundException);

        $repository->create(20, 'user', 3, 'activity1', 'Default activity instance for activity activity1 (#20)')->shouldBeCalled()->willReturn($activityInstance1);
        
        $generator = new DefaultActivityInstanceGenerator($repository->reveal());
        $this->assertModelEquals($activityInstance1, $generator->generate($activity, 'user', 3));
    }
    
}