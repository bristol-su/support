<?php

namespace BristolSU\Support\Tests\Authorization\Exception;

use BristolSU\Support\Authorization\Exception\ModuleInactive;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class ModuleInactiveTest extends TestCase
{

    /** @test */
    public function getModuleInstance_returns_the_moduleInstance(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $exception = new ModuleInactive('message', 500, null, $moduleInstance);
        
        $this->assertModelEquals($moduleInstance, $exception->getModuleInstance());
    }
    
}