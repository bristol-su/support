<?php

namespace BristolSU\Support\Tests\ActivityInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class ActivityInstanceTest extends TestCase
{

    /** @test */
    public function runNumber_returns_1_if_the_activity_instance_is_the_first(){
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $this->assertEquals(1, $activityInstance->run_number);
    }

    /** @test */
    public function runNumber_returns_2_if_the_activity_instance_is_the_second(){
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $this->assertEquals(2, $activityInstance2->run_number);
    }

    /** @test */
    public function runNumber_returns_3_if_the_activity_instance_is_the_third(){
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSeconds(2)]);
        $this->assertEquals(3, $activityInstance3->run_number);
    }
    
    /** @test */
    public function runNumber_only_looks_at_similar_activity_instances(){
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSecond()]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()->addSeconds(2)]);
        $activityInstancefake1 = factory(ActivityInstance::class)->create(['activity_id' => 2, 'resource_type' => 'user', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstancefake2 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'group', 'resource_id' => 1, 'created_at' => Carbon::now()]);
        $activityInstancefake3 = factory(ActivityInstance::class)->create(['activity_id' => 1, 'resource_type' => 'user', 'resource_id' => 2, 'created_at' => Carbon::now()]);
        $this->assertEquals(3, $activityInstance3->run_number);
    }
    
    /** @test */
    public function it_belongs_to_an_activity(){
        $activity = factory(Activity::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        
        $this->assertModelEquals($activity, $activityInstance->activity);
    }
    
}