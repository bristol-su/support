<?php

namespace BristolSU\Support\Tests\ModuleInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceGroupingTest extends TestCase
{

    /** @test */
    public function a_grouping_model_can_be_created(){
        $grouping = factory(ModuleInstanceGrouping::class)->create(['heading' => 'Test Heading']);
        $this->assertDatabaseHas('module_instance_grouping', [
            'id' => $grouping->id,
            'heading' => 'Test Heading'
        ]);
        $this->assertEquals('Test Heading', $grouping->heading());
    }
 
    /** @test */
    public function forActivity_returns_all_groupings_for_a_given_activity(){
        $activity1 = factory(Activity::class)->create();
        $act1Grouping1 = factory(ModuleInstanceGrouping::class)->create();
        $act1Grouping2 = factory(ModuleInstanceGrouping::class)->create();
        $act1ModuleInstance1 = factory(ModuleInstance::class)->create(['grouping_id' => null, 'activity_id' => $activity1->id]);
        $act1ModuleInstance2 = factory(ModuleInstance::class)->create(['grouping_id' => $act1Grouping1->id, 'activity_id' => $activity1->id]);
        $act1ModuleInstance3 = factory(ModuleInstance::class)->create(['grouping_id' => $act1Grouping1->id, 'activity_id' => $activity1->id]);
        $act1ModuleInstance4 = factory(ModuleInstance::class)->create(['grouping_id' => $act1Grouping2->id, 'activity_id' => $activity1->id]);

        $activity2 = factory(Activity::class)->create();
        $act2Grouping1 = factory(ModuleInstanceGrouping::class)->create();
        $act2Grouping2 = factory(ModuleInstanceGrouping::class)->create();
        $act2ModuleInstance1 = factory(ModuleInstance::class)->create(['grouping_id' => null, 'activity_id' => $activity2->id]);
        $act2ModuleInstance2 = factory(ModuleInstance::class)->create(['grouping_id' => $act2Grouping1->id, 'activity_id' => $activity2->id]);
        $act2ModuleInstance3 = factory(ModuleInstance::class)->create(['grouping_id' => $act2Grouping1->id, 'activity_id' => $activity2->id]);
        $act2ModuleInstance4 = factory(ModuleInstance::class)->create(['grouping_id' => $act2Grouping1->id, 'activity_id' => $activity2->id]);
        
        $groupings = ModuleInstanceGrouping::forActivity($activity1)->get();
        $this->assertCount(2, $groupings);
        $this->assertContainsOnlyInstancesOf(ModuleInstanceGrouping::class, $groupings);
        $this->assertTrue($act1Grouping1->is($groupings->shift()));
        $this->assertTrue($act1Grouping2->is($groupings->shift()));

        $groupings = ModuleInstanceGrouping::forActivity($activity2)->get();
        $this->assertCount(1, $groupings);
        $this->assertContainsOnlyInstancesOf(ModuleInstanceGrouping::class, $groupings);
        $this->assertTrue($act2Grouping1->is($groupings->shift()));
        
    }
    
}