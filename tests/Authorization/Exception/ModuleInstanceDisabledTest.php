<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Authorization\Exception\ModuleInstanceDisabled;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceDisabledTest extends TestCase
{
    /** @test */
    public function an_module_instance_can_be_set_and_retrieved()
    {
        $moduleInstance = factory(ModuleInstance::class)->create();
        $exception = new ModuleInstanceDisabled();
        
        $exception->setModuleInstance($moduleInstance);
        
        $this->assertModelEquals($moduleInstance, $exception->moduleInstance());
    }

    /** @test */
    public function an_module_instance_can_be_set_through_the_static_method_and_retrieved()
    {
        $moduleInstance = factory(ModuleInstance::class)->create();
        $exception = ModuleInstanceDisabled::fromModuleInstance($moduleInstance);

        $this->assertModelEquals($moduleInstance, $exception->moduleInstance());
    }
}
