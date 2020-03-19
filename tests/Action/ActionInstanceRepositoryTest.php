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
    
    /** @test */
    public function forModuleInstance_returns_all_action_instances_with_the_given_module_instance_id(){
        factory(ActionInstance::class, 5)->create(['module_instance_id' => 1, 'event' => 'event1']);
        $actionInstances = factory(ActionInstance::class, 5)->create(['module_instance_id' => 2, 'event' => 'event2']);
        factory(ActionInstance::class, 5)->create(['module_instance_id' => 3, 'event' => 'event2']);

        $repository = new ActionInstanceRepository();
        $resolvedActionInstances = $repository->forModuleInstance(2);

        $this->assertInstanceOf(Collection::class, $resolvedActionInstances);
        $this->assertContainsOnlyInstancesOf(ActionInstance::class, $resolvedActionInstances);
        $this->assertEquals(5, $resolvedActionInstances->count());
        foreach($actionInstances as $actionInstance) {
            $this->assertModelEquals($actionInstance, $resolvedActionInstances->shift());
        }
    }

    /** @test */
    public function all_returns_all_action_instances()
    {
        $actionInstances = factory(ActionInstance::class, 5)->create();

        $repository = new ActionInstanceRepository();
        $resolvedActionInstances = $repository->all();

        $this->assertInstanceOf(Collection::class, $resolvedActionInstances);
        $this->assertContainsOnlyInstancesOf(ActionInstance::class, $resolvedActionInstances);
        $this->assertEquals(5, $resolvedActionInstances->count());
        foreach($actionInstances as $actionInstance) {
            $this->assertModelEquals($actionInstance, $resolvedActionInstances->shift());
        }
    }
    
    /** @test */
    public function getById_returns_an_action_instance_by_id(){
        $actionInstance = factory(ActionInstance::class)->create();
        factory(ActionInstance::class, 5)->create();
        
        $repository = new ActionInstanceRepository();
        $resolvedActionInstance = $repository->getById($actionInstance->id);
        
        $this->assertInstanceOf(ActionInstance::class, $resolvedActionInstance);
        $this->assertModelEquals($actionInstance, $resolvedActionInstance);
    }
    
    /** @test */
    public function update_updates_an_action_instance(){
        $actionInstance = factory(ActionInstance::class)->create([
            'name' => 'OldName', 'description' => 'OldDescription', 'event' => 'OldEvent', 'action' => 'OldAction', 'module_instance_id' => 1, 'user_id' => 2
        ]);

        $this->assertDatabaseHas('action_instances', [
            'id' => $actionInstance->id, 'name' => 'OldName', 'description' => 'OldDescription', 'event' => 'OldEvent', 'action' => 'OldAction', 'module_instance_id' => 1, 'user_id' => 2
        ]);
        
        $repository = new ActionInstanceRepository();
        $resolvedActionInstance = $repository->update($actionInstance->id, [
            'name' => 'NewName', 'description' => 'NewDescription', 'event' => 'NewEvent', 'action' => 'NewAction', 'module_instance_id' => 3, 'user_id' => 4
        ]);

        $this->assertDatabaseHas('action_instances', [
            'id' => $actionInstance->id, 'name' => 'NewName', 'description' => 'NewDescription', 'event' => 'NewEvent', 'action' => 'NewAction', 'module_instance_id' => 3, 'user_id' => 4
        ]);
        
        $this->assertInstanceOf(ActionInstance::class, $resolvedActionInstance);
        $this->assertEquals($actionInstance->id, $resolvedActionInstance->id);
        $this->assertEquals('NewName', $resolvedActionInstance->name);
        $this->assertEquals('NewDescription', $resolvedActionInstance->description);
        $this->assertEquals('NewEvent', $resolvedActionInstance->event);
        $this->assertEquals('NewAction', $resolvedActionInstance->action);
        $this->assertEquals(3, $resolvedActionInstance->module_instance_id);
        $this->assertEquals(4, $resolvedActionInstance->user_id);
    }
    
    /** @test */
    public function delete_deletes_an_action_instance(){
        $actionInstance = factory(ActionInstance::class)->create();
        factory(ActionInstance::class, 5)->create();
        $this->assertDatabaseHas('action_instances', ['id' => $actionInstance->id]);

        $repository = new ActionInstanceRepository();
        $repository->delete($actionInstance->id);

        $this->assertDatabaseMissing('action_instances', ['id' => $actionInstance->id]);

    }
}

class Dummy1_test {}
class Dummy2_test {}
class Dummy3_test {}