<?php

namespace BristolSU\Support\Tests\Permissions\Testers;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Testers\ModuleInstanceUserOverridePermission;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceUserOverridePermissionTest extends TestCase
{
    /** @test */
    public function can_returns_null_if_no_user_given()
    {
        $tester = new ModuleInstanceUserOverridePermission();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);
        
        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), null, null, null)
        );
    }

    /** @test */
    public function can_returns_null_if_no_module_instance_injected()
    {
        $tester = new ModuleInstanceUserOverridePermission();
        $user = $this->newUser();
        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), $user, null, null)
        );
    }
    
    /** @test */
    public function can_returns_null_if_the_permission_is_not_module()
    {
        $tester = new ModuleInstanceUserOverridePermission();
        $user = $this->newUser();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);
        
        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), $user, null, null)
        );
    }

    /** @test */
    public function can_returns_true_if_there_is_a_system_override_in_the_database_with_a_true_result()
    {
        $tester = new ModuleInstanceUserOverridePermission();
        $user = $this->newUser();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'user',
            'model_id' => 1,
            'result' => true,
            'module_instance_id' => $moduleInstance->id
        ]);

        $this->assertTrue(
            $tester->can(new Permission('ability1', '', '', 'module'), $user, null, null)
        );
    }

    /** @test */
    public function can_returns_false_if_there_is_a_system_override_in_the_database_with_a_false_result()
    {
        $tester = new ModuleInstanceUserOverridePermission();
        $user = $this->newUser();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'user',
            'model_id' => 1,
            'result' => false,
            'module_instance_id' => $moduleInstance->id
        ]);

        $this->assertFalse(
            $tester->can(new Permission('ability1', '', '', 'module'), $user, null, null)
        );
    }
    
    /** @test */
    public function can_returns_null_if_there_is_no_system_override_in_the_database()
    {
        $tester = new ModuleInstanceUserOverridePermission();
        $user = $this->newUser();

        $this->assertNull(
            $tester->can(new Permission('ability1', '', '', 'module'), $user, null, null)
        );
    }

    /** @test */
    public function can_returns_null_if_module_instance_id_not_in_permission_override()
    {
        $tester = new ModuleInstanceUserOverridePermission();
        $user = $this->newUser();
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'user',
            'model_id' => 1,
            'result' => false,
        ]);

        $this->assertNull(
            $tester->can(new Permission('ability1', '', '', 'module'), $user, null, null)
        );
    }
}
