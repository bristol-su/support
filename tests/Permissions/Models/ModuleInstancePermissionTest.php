<?php

namespace BristolSU\Support\Tests\Permissions\Models;

use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Tests\TestCase;

class ModuleInstancePermissionTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_module_instance()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $moduleInstancePermission = ModuleInstancePermission::factory()->create(['module_instance_id' => $moduleInstance->id]);

        $this->assertInstanceOf(ModuleInstance::class, $moduleInstancePermission->moduleInstance);
        $this->assertModelEquals($moduleInstance, $moduleInstancePermission->moduleInstance);
    }
    
    /** @test */
    public function it_has_a_logic_group()
    {
        $logic = Logic::factory()->create();
        $moduleInstancePermission = ModuleInstancePermission::factory()->create(['logic_id' => $logic->id]);

        $this->assertInstanceOf(Logic::class, $moduleInstancePermission->logic);
        $this->assertModelEquals($logic, $moduleInstancePermission->logic);
    }
    
    /** @test */
    public function it_retrieves_a_permission_model()
    {
        $permission = new Permission('ability-1', 'Name1', 'Description1');
        $permissionRepository = $this->prophesize(PermissionRepository::class);
        $permissionRepository->get('ability-1')->shouldBeCalled()->willReturn($permission);
        $this->app->instance(PermissionRepository::class, $permissionRepository->reveal());
        $moduleInstancePermission = ModuleInstancePermission::factory()->create(['ability' => 'ability-1']);

        $this->assertInstanceOf(Permission::class, $moduleInstancePermission->permission);
        $this->assertSame($permission, $moduleInstancePermission->permission);
    }
    
    /** @test */
    public function it_has_a_type_attribute_from_the_permission()
    {
        $permission = new Permission('ability-1', 'Name1', 'Description1');
        $permission->setType('module');
        $permissionRepository = $this->prophesize(PermissionRepository::class);
        $permissionRepository->get('ability-1')->shouldBeCalled()->willReturn($permission);
        $this->app->instance(PermissionRepository::class, $permissionRepository->reveal());
        
        $moduleInstancePermission = ModuleInstancePermission::factory()->create(['ability' => 'ability-1']);

        $this->assertEquals('module', $moduleInstancePermission->type);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $moduleInstancePermission = ModuleInstancePermission::factory()->create(['ability' => 'OldAbility']);

        $moduleInstancePermission->ability = 'NewAbility';
        $moduleInstancePermission->save();

        $this->assertEquals(1, $moduleInstancePermission->revisionHistory->count());
        $this->assertEquals($moduleInstancePermission->id, $moduleInstancePermission->revisionHistory->first()->revisionable_id);
        $this->assertEquals(ModuleInstancePermission::class, $moduleInstancePermission->revisionHistory->first()->revisionable_type);
        $this->assertEquals('ability', $moduleInstancePermission->revisionHistory->first()->key);
        $this->assertEquals('OldAbility', $moduleInstancePermission->revisionHistory->first()->old_value);
        $this->assertEquals('NewAbility', $moduleInstancePermission->revisionHistory->first()->new_value);
    }
}
