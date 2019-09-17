<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceRepository;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Collection;

class ActionInstanceRepositoryTest extends TestCase
{

    /** @test */
    public function forEvent_returns_all_action_instances_with_the_given_module_instance_id_and_event(){
        factory(ActionInstance::class, 5)->create(['module_instance_id' => 1, 'event' => 'event1']);
        $actionInstances = factory(ActionInstance::class, 5)->create(['module_instance_id' => 2, 'event' => 'event2']);
        factory(ActionInstance::class, 5)->create(['module_instance_id' => 2, 'event' => 'event3']);
        factory(ActionInstance::class, 5)->create(['module_instance_id' => 3, 'event' => 'event2']);
        
        $repository = new ActionInstanceRepository();
        $resolvedActionInstances = $repository->forEvent(2, 'event2');

        $this->assertInstanceOf(Collection::class, $resolvedActionInstances);
        $this->assertContainsOnlyInstancesOf(ActionInstance::class, $resolvedActionInstances);
        $this->assertEquals(5, $resolvedActionInstances->count());
        foreach($actionInstances as $actionInstance) {
            $this->assertModelEquals($actionInstance, $resolvedActionInstances->shift());
        }
        
    }
    
}

class Dummy1_test {}
class Dummy2_test {}
class Dummy3_test {}