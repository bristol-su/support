<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authorization\Exception\ModuleInstanceDisabled;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceDisabledTest extends TestCase
{
    /** @test */
    public function an_module_instance_can_be_set_and_retrieved()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $exception = new ModuleInstanceDisabled();

        $exception->setModuleInstance($moduleInstance);

        $this->assertModelEquals($moduleInstance, $exception->moduleInstance());
    }

    /** @test */
    public function an_module_instance_can_be_set_through_the_static_method_and_retrieved()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $exception = ModuleInstanceDisabled::fromModuleInstance($moduleInstance);

        $this->assertModelEquals($moduleInstance, $exception->moduleInstance());
    }

    /** @test */
    public function a_suitable_message_and_code_are_set()
    {
        $activity = Activity::factory()->create(['name' => 'Our Testing Activity']);
        $moduleInstance = ModuleInstance::factory()->create(['activity_id' => $activity->id, 'name' => 'Note Taking Module']);
        $exception = ModuleInstanceDisabled::fromModuleInstance($moduleInstance);

        $this->assertEquals('Note Taking Module from Our Testing Activity has been disabled', $exception->getMessage());
        $this->assertEquals(403, $exception->getCode());
    }

}
