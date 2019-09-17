<?php


namespace BristolSU\Support\Tests\Integration\EventStore;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\EventStore\EventStore;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Testing\TestCase;

class EventStoreTest extends TestCase
{

    /** @test */
    public function it_has_a_module_instance(){
        $eventStore = factory(EventStore::class)->create();
        $this->assertInstanceOf(ModuleInstance::class, $eventStore->moduleInstance);
        $this->assertEquals($eventStore->module_instance_id, $eventStore->moduleInstance->id);
    }

}
