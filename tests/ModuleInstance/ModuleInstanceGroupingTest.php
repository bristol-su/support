<?php

namespace BristolSU\Support\Tests\ModuleInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceGroupingTest extends TestCase
{
    /** @test */
    public function a_grouping_model_can_be_created()
    {
        $grouping = ModuleInstanceGrouping::factory()->create(['heading' => 'Test Heading']);
        $this->assertDatabaseHas('module_instance_grouping', [
            'id' => $grouping->id,
            'heading' => 'Test Heading'
        ]);
        $this->assertEquals('Test Heading', $grouping->heading());
    }

    /** @test */
    public function for_activity_returns_all_groupings_for_a_given_activity()
    {
        $activity1 = Activity::factory()->create();
        $act1Grouping1 = ModuleInstanceGrouping::factory()->create(['activity_id' => $activity1->id]);
        $act1Grouping2 = ModuleInstanceGrouping::factory()->create(['activity_id' => $activity1->id]);
        $act1ModuleInstance1 = ModuleInstance::factory()->create(['grouping_id' => null, 'activity_id' => $activity1->id]);
        $act1ModuleInstance2 = ModuleInstance::factory()->create(['grouping_id' => $act1Grouping1->id, 'activity_id' => $activity1->id]);
        $act1ModuleInstance3 = ModuleInstance::factory()->create(['grouping_id' => $act1Grouping1->id, 'activity_id' => $activity1->id]);
        $act1ModuleInstance4 = ModuleInstance::factory()->create(['grouping_id' => $act1Grouping2->id, 'activity_id' => $activity1->id]);

        $groupings = ModuleInstanceGrouping::forActivity($activity1)->get();
        $this->assertCount(2, $groupings);
        $this->assertContainsOnlyInstancesOf(ModuleInstanceGrouping::class, $groupings);
        $this->assertTrue($act1Grouping1->is($groupings->shift()));
        $this->assertTrue($act1Grouping2->is($groupings->shift()));
    }

    /** @test */
    public function groups_can_be_ordered(){
        $activity = Activity::factory()->create();
        $grouping1 = ModuleInstanceGrouping::factory()->create(['activity_id' => $activity->id]);
        $grouping2 = ModuleInstanceGrouping::factory()->create(['activity_id' => $activity->id]);
        $retrievedInstances = ModuleInstanceGrouping::ordered()->get();
        $this->assertModelEquals($grouping1, $retrievedInstances->shift());
        $this->assertModelEquals($grouping2, $retrievedInstances->shift());

        $grouping2->moveOrderUp();
        $retrievedInstances = ModuleInstanceGrouping::ordered()->get();
        $this->assertModelEquals($grouping2, $retrievedInstances->shift());
        $this->assertModelEquals($grouping1, $retrievedInstances->shift());
    }
}
