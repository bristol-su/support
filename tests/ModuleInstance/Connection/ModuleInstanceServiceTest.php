<?php

namespace BristolSU\Support\Tests\ModuleInstance\Connection;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceServiceTest extends TestCase
{

    /** @test */
    public function it_belongs_to_a_module_instance(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstanceService = factory(ModuleInstanceService::class)->create(['module_instance_id' => $moduleInstance->id]);
        
        $this->assertInstanceOf(ModuleInstance::class, $moduleInstanceService->moduleInstance);
        $this->assertModelEquals($moduleInstance, $moduleInstanceService->moduleInstance);
    }
    
    /** @test */
    public function it_belongs_to_a_connection(){
        $connection = factory(Connection::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstanceService = factory(ModuleInstanceService::class)->create([
            'module_instance_id' => $moduleInstance->id, 'connection_id' => $connection->id
        ]);

        $this->assertInstanceOf(Connection::class, $moduleInstanceService->connection);
        $this->assertModelEquals($connection, $moduleInstanceService->connection);
    }
    
}