<?php

namespace BristolSU\Support\Tests\Permissions\Testers;

use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Permissions\Models\Permission;
use BristolSU\Support\Permissions\Testers\SystemUserPermission;
use BristolSU\Support\Tests\TestCase;

class SystemUserPermissionTest extends TestCase
{
    /** @test */
    public function can_returns_null_if_no_user_given()
    {
        $tester = new SystemUserPermission();
        
        $this->assertNull(
            $tester->can(new Permission('ability'), null, null, null)
        );
    }
    
    /** @test */
    public function can_returns_null_if_the_permission_is_not_global()
    {
        $tester = new SystemUserPermission();
        $user = $this->newUser();
        
        $this->assertNull(
            $tester->can(new Permission('ability', '', '', 'module'), $user, null, null)
        );
    }
    
    /** @test */
    public function can_returns_true_if_there_is_a_system_override_in_the_database_with_a_true_result()
    {
        $tester = new SystemUserPermission();
        $user = $this->newUser();

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'user',
            'model_id' => 1,
            'result' => true
        ]);
        
        $this->assertTrue(
            $tester->can(new Permission('ability1'), $user, null, null)
        );
    }

    /** @test */
    public function can_returns_false_if_there_is_a_system_override_in_the_database_with_a_false_result()
    {
        $tester = new SystemUserPermission();
        $user = $this->newUser();

        ModelPermission::create([
            'ability' => 'ability1',
            'model' => 'user',
            'model_id' => 1,
            'result' => false
        ]);

        $this->assertFalse(
            $tester->can(new Permission('ability1'), $user, null, null)
        );
    }

    /** @test */
    public function can_returns_null_if_there_is_no_system_override_in_the_database()
    {
        $tester = new SystemUserPermission();
        $user = $this->newUser();

        $this->assertNull(
            $tester->can(new Permission('ability1'), $user, null, null)
        );
    }
}
