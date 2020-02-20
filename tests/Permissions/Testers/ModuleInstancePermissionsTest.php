<?php

namespace BristolSU\Support\Tests\Permissions\Testers;

use BristolSU\ControlDB\Models\Group;
use BristolSU\ControlDB\Models\Role;
use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Testers\ModuleInstancePermissions;
use BristolSU\Support\Tests\TestCase;

class ModuleInstancePermissionsTest extends TestCase
{

    /** @test */
    public function can_returns_null_if_no_module_instance_is_in_the_container()
    {
        $logicTester = $this->prophesize(LogicTester::class);
        $tester = new ModuleInstancePermissions($logicTester->reveal());
        
        $this->assertNull($tester->can(new Permission('ability'), null, null, null));
    }

    /** @test */
    public function can_returns_null_if_permission_not_in_the_module_permission_for_the_module_instance()
    {
        $logicTester = $this->prophesize(LogicTester::class);
        $tester = new ModuleInstancePermissions($logicTester->reveal());
        
        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstancePermission = factory(ModuleInstancePermission::class)->create([
            'ability' => 'permission1', 'logic_id' => factory(Logic::class)->create(), 'module_instance_id' => $moduleInstance->id
        ]);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertNull(
            $tester->can(new Permission('permission2'), null, null, null)
        );
    }

    /** @test */
    public function can_returns_null_if_the_logic_for_the_permission_could_not_be_found()
    {
        $logicTester = $this->prophesize(LogicTester::class);
        $tester = new ModuleInstancePermissions($logicTester->reveal());

        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstancePermission = factory(ModuleInstancePermission::class)->create([
            'ability' => 'permission1', 'logic_id' => 100, 'module_instance_id' => $moduleInstance->id
        ]);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertDatabaseMissing('logics', ['id' => 100]);
        
        $this->assertNull(
            $tester->can(new Permission('permission1'), null, null, null)
        );
    }

    /** @test */
    public function can_returns_true_if_the_logic_is_true()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $logic = factory(Logic::class)->create();
        $this->logicTester()->forLogic($logic)->pass($user, $group, $role);
        $tester = new ModuleInstancePermissions($this->logicTester());

        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstancePermission = factory(ModuleInstancePermission::class)->create([
            'ability' => 'permission1', 'logic_id' => $logic->id, 'module_instance_id' => $moduleInstance->id
        ]);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertTrue(
            $tester->can(new Permission('permission1'), $user, $group, $role)
        );
    }

    /** @test */
    public function can_returns_false_if_the_logic_is_false()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $logic = factory(Logic::class)->create();
        $this->logicTester()->forLogic($logic)->fail($user, $group, $role);
        $tester = new ModuleInstancePermissions($this->logicTester());

        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstancePermission = factory(ModuleInstancePermission::class)->create([
            'ability' => 'permission1', 'logic_id' => $logic->id, 'module_instance_id' => $moduleInstance->id
        ]);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertFalse(
            $tester->can(new Permission('permission1'), $user, $group, $role)
        );
    }

}