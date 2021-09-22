<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Authorization\Exception\ModuleInactive;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class ModuleInactiveTest extends TestCase
{
    /** @test */
    public function get_module_instance_returns_the_module_instance()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $exception = new ModuleInactive('message', 500, null, $moduleInstance);
        
        $this->assertModelEquals($moduleInstance, $exception->getModuleInstance());
    }

    /** @test */
    public function create_with_module_instance_creates_the_exception_with_a_module_instance()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $exception = ModuleInactive::createWithModuleInstance($moduleInstance, 'A Message', 404);

        $this->assertModelEquals($moduleInstance, $exception->getModuleInstance());
        $this->assertEquals(404, $exception->getCode());
        $this->assertEquals('A Message', $exception->getMessage());
    }
}
