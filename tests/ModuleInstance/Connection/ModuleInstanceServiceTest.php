<?php

namespace BristolSU\Support\Tests\ModuleInstance\Connection;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use BristolSU\Support\User\User;

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
        $user = $this->newUser();
        $this->beUser($user);
        $this->be(factory(User::class)->create(['control_id' => $user->id()]));
        
        $connection = factory(Connection::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstanceService = factory(ModuleInstanceService::class)->create([
            'module_instance_id' => $moduleInstance->id, 'connection_id' => $connection->id
        ]);

        $this->assertInstanceOf(Connection::class, $moduleInstanceService->connection);
        $this->assertModelEquals($connection, $moduleInstanceService->connection);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $moduleInstanceService = factory(ModuleInstanceService::class)->create(['service' => 'OldService']);

        $moduleInstanceService->service = 'NewService';
        $moduleInstanceService->save();

        $this->assertEquals(1, $moduleInstanceService->revisionHistory->count());
        $this->assertEquals($moduleInstanceService->id, $moduleInstanceService->revisionHistory->first()->revisionable_id);
        $this->assertEquals(ModuleInstanceService::class, $moduleInstanceService->revisionHistory->first()->revisionable_type);
        $this->assertEquals('service', $moduleInstanceService->revisionHistory->first()->key);
        $this->assertEquals('OldService', $moduleInstanceService->revisionHistory->first()->old_value);
        $this->assertEquals('NewService', $moduleInstanceService->revisionHistory->first()->new_value);
    }
    
}