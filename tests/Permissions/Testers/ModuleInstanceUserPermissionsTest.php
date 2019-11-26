<?php

namespace BristolSU\Support\Tests\Permissions\Testers;

use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Models\ModuleInstancePermissions;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Testers\ModuleInstanceUserPermissions;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceUserPermissionsTest extends TestCase
{
    /** @test */
    public function can_returns_null_if_no_module_instance_is_in_the_container()
    {
        $logicTester = $this->prophesize(LogicTester::class);
        $tester = new ModuleInstanceUserPermissions($logicTester->reveal());

        $this->assertNull($tester->can(new Permission('ability'), null, null, null));
    }

    /** @test */
    public function can_returns_null_if_permission_not_in_the_participant_permission_for_the_module_instance()
    {
        $logicTester = $this->prophesize(LogicTester::class);
        $tester = new ModuleInstanceUserPermissions($logicTester->reveal());

        $ModInstPermissions = factory(ModuleInstancePermissions::class)->create(['participant_permissions' => [
            'permission1' => factory(Logic::class)->create()->id
        ]]);
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_permissions_id' => $ModInstPermissions->id]);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertNull(
            $tester->can(new Permission('permission2'), null, null, null)
        );
    }

    /** @test */
    public function can_returns_null_if_the_logic_for_the_permission_could_not_be_found()
    {
        $logicTester = $this->prophesize(LogicTester::class);
        $tester = new ModuleInstanceUserPermissions($logicTester->reveal());

        $ModInstPermissions = factory(ModuleInstancePermissions::class)->create(['participant_permissions' => [
            'permission1' => 100
        ]]);
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_permissions_id' => $ModInstPermissions->id]);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertDatabaseMissing('logics', ['id' => 100]);

        $this->assertNull(
            $tester->can(new Permission('permission1'), null, null, null)
        );
    }

    /** @test */
    public function can_returns_true_if_the_logic_is_true()
    {
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        $logic = factory(Logic::class)->create();
        $logicTester = $this->createLogicTester([$logic], [], $user, $group, $role);
        $tester = new ModuleInstanceUserPermissions($logicTester->reveal());

        $ModInstPermissions = factory(ModuleInstancePermissions::class)->create(['participant_permissions' => [
            'permission1' => $logic->id
        ]]);
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_permissions_id' => $ModInstPermissions->id]);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertTrue(
            $tester->can(new Permission('permission1'), $user, $group, $role)
        );
    }

    /** @test */
    public function can_returns_false_if_the_logic_is_false()
    {
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        $logic = factory(Logic::class)->create();
        $logicTester = $this->createLogicTester([], [$logic], $user, $group, $role);
        $tester = new ModuleInstanceUserPermissions($logicTester->reveal());

        $ModInstPermissions = factory(ModuleInstancePermissions::class)->create(['participant_permissions' => [
            'permission1' => $logic->id
        ]]);
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_permissions_id' => $ModInstPermissions->id]);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertFalse(
            $tester->can(new Permission('permission1'), $user, $group, $role)
        );
    }

}