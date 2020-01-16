<?php

namespace BristolSU\Support\Tests\Permissions\Testers;

use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Testers\ModuleInstanceRoleOverridePermission;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceRoleOverridePermissionTest extends TestCase
{
    /** @test */
    public function can_returns_null_if_no_role_given(){
        $tester = new ModuleInstanceRoleOverridePermission;
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), null, null, null)
        );
    }

    /** @test */
    public function can_returns_null_if_no_module_instance_injected(){
        $tester = new ModuleInstanceRoleOverridePermission;
        $role = $this->newRole(['id' => 1]);
        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), null, null, $role)
        );
    }

    /** @test */
    public function can_returns_null_if_the_permission_is_not_module(){
        $tester = new ModuleInstanceRoleOverridePermission;
        $role = $this->newRole(['id' => 1]);
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), null, null, $role)
        );
    }

    /** @test */
    public function can_returns_true_if_there_is_a_system_override_in_the_database_with_a_true_result(){
        $tester = new ModuleInstanceRoleOverridePermission;
        $role = $this->newRole(['id' => 1]);
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'role',
            'model_id' => 1,
            'result' => true,
            'module_instance_id' => $moduleInstance->id
        ]);

        $this->assertTrue(
            $tester->can(new Permission('ability1', '', '', 'module'), null, null, $role)
        );
    }

    /** @test */
    public function can_returns_false_if_there_is_a_system_override_in_the_database_with_a_false_result(){
        $tester = new ModuleInstanceRoleOverridePermission;
        $role = $this->newRole(['id' => 1]);
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'role',
            'model_id' => 1,
            'result' => false,
            'module_instance_id' => $moduleInstance->id
        ]);

        $this->assertFalse(
            $tester->can(new Permission('ability1', '', '', 'module'), null, null, $role)
        );
    }

    /** @test */
    public function can_returns_null_if_there_is_no_system_override_in_the_database(){
        $tester = new ModuleInstanceRoleOverridePermission;
        $role = $this->newRole(['id' => 1]);

        $this->assertNull(
            $tester->can(new Permission('ability1', '', '', 'module'), null, null, $role)
        );
    }

    /** @test */
    public function can_returns_null_if_module_instance_id_not_in_permission_override(){
        $tester = new ModuleInstanceRoleOverridePermission;
        $role = $this->newRole(['id' => 1]);
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'role',
            'model_id' => 1,
            'result' => false,
        ]);

        $this->assertNull(
            $tester->can(new Permission('ability1', '', '', 'module'), null, null, $role)
        );
    }
}