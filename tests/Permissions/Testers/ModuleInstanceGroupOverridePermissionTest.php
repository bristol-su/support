<?php

namespace BristolSU\Support\Tests\Permissions\Testers;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Testers\ModuleInstanceGroupOverridePermission;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceGroupOverridePermissionTest extends TestCase
{
    /** @test */
    public function can_returns_null_if_no_group_given()
    {
        $tester = new ModuleInstanceGroupOverridePermission();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), null, null, null)
        );
    }

    /** @test */
    public function can_returns_null_if_no_module_instance_injected()
    {
        $tester = new ModuleInstanceGroupOverridePermission();
        $group = $this->newGroup();
        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), null, $group, null)
        );
    }

    /** @test */
    public function can_returns_null_if_the_permission_is_not_module()
    {
        $tester = new ModuleInstanceGroupOverridePermission();
        $group = $this->newGroup();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), null, $group, null)
        );
    }

    /** @test */
    public function can_returns_true_if_there_is_a_system_override_in_the_database_with_a_true_result()
    {
        $tester = new ModuleInstanceGroupOverridePermission();
        $group = $this->newGroup();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'group',
            'model_id' => 1,
            'result' => true,
            'module_instance_id' => $moduleInstance->id
        ]);

        $this->assertTrue(
            $tester->can(new Permission('ability1', '', '', 'module'), null, $group, null)
        );
    }

    /** @test */
    public function can_returns_false_if_there_is_a_system_override_in_the_database_with_a_false_result()
    {
        $tester = new ModuleInstanceGroupOverridePermission();
        $group = $this->newGroup();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'group',
            'model_id' => 1,
            'result' => false,
            'module_instance_id' => $moduleInstance->id
        ]);

        $this->assertFalse(
            $tester->can(new Permission('ability1', '', '', 'module'), null, $group, null)
        );
    }

    /** @test */
    public function can_returns_null_if_there_is_no_system_override_in_the_database()
    {
        $tester = new ModuleInstanceGroupOverridePermission();
        $group = $this->newGroup();

        $this->assertNull(
            $tester->can(new Permission('ability1', '', '', 'module'), null, $group, null)
        );
    }

    /** @test */
    public function can_returns_null_if_module_instance_id_not_in_permission_override()
    {
        $tester = new ModuleInstanceGroupOverridePermission();
        $group = $this->newGroup();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'group',
            'model_id' => 1,
            'result' => false,
        ]);

        $this->assertNull(
            $tester->can(new Permission('ability1', '', '', 'module'), null, $group, null)
        );
    }
}
