<?php


namespace BristolSU\Support\Tests\Permissions\Models;

use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Permissions\Models\ModelPermission;
use BristolSU\Support\Tests\TestCase;

class ModelPermissionTest extends TestCase
{
    /** @test */
    public function user_returns_all_user_permissions()
    {
        $userPermissions = ModelPermission::factory()->count(5)->user()->create();
        $groupPermissions = ModelPermission::factory()->count(5)->group()->create();

        $permissions = ModelPermission::user()->get();

        foreach ($userPermissions as $permission) {
            $this->assertTrue($permissions->contains($permission));
        }
        foreach ($groupPermissions as $permission) {
            $this->assertFalse($permissions->contains($permission));
        }
    }

    /** @test */
    public function user_can_select_by_user_id_and_ability()
    {
        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $user4 = $this->newUser();
        $userPermission1 = ModelPermission::factory()->user()->create(['ability' => 'permission1', 'model_id' => $user1->id]);
        $userPermission2 = ModelPermission::factory()->user()->create(['ability' => 'permission2', 'model_id' => $user2->id]);
        $userPermission3 = ModelPermission::factory()->user()->create(['ability' => 'permission3', 'model_id' => $user1->id]);
        $userPermission4 = ModelPermission::factory()->user()->create(['ability' => 'permission1', 'model_id' => $user4->id]);

        $permission = ModelPermission::user($user1->id, 'permission1')->get();

        $this->assertCount(1, $permission);
        $this->assertModelEquals($userPermission1, $permission->first());
    }

    /** @test */
    public function group_can_select_by_group_id_and_ability()
    {
        $group1 = $this->newGroup();
        $group2 = $this->newGroup();
        $group4 = $this->newGroup();
        $groupPermission1 = ModelPermission::factory()->group()->create(['ability' => 'permission1', 'model_id' => $group1->id]);
        $groupPermission2 = ModelPermission::factory()->group()->create(['ability' => 'permission2', 'model_id' => $group2->id]);
        $groupPermission3 = ModelPermission::factory()->group()->create(['ability' => 'permission3', 'model_id' => $group1->id]);
        $groupPermission4 = ModelPermission::factory()->group()->create(['ability' => 'permission1', 'model_id' => $group4->id]);

        $permission = ModelPermission::group($group1->id, 'permission1')->get();

        $this->assertCount(1, $permission);
        $this->assertEquals($groupPermission1->id, $permission->first()->id);
    }

    /** @test */
    public function group_returns_all_group_permissions()
    {
        $userPermissions = ModelPermission::factory()->count(5)->user()->create();
        $groupPermissions = ModelPermission::factory()->count(5)->group()->create();

        $permissions = ModelPermission::group()->get();

        foreach ($userPermissions as $permission) {
            $this->assertFalse($permissions->contains($permission));
        }
        foreach ($groupPermissions as $permission) {
            $this->assertTrue($permissions->contains($permission));
        }
    }

    /** @test */
    public function role_can_select_by_role_id_and_ability()
    {
        $role1 = $this->newRole();
        $role2 = $this->newRole();
        $role4 = $this->newRole();
        $rolePermission1 = ModelPermission::factory()->role()->create(['ability' => 'permission1', 'model_id' => $role1->id]);
        $rolePermission2 = ModelPermission::factory()->role()->create(['ability' => 'permission2', 'model_id' => $role2->id]);
        $rolePermission3 = ModelPermission::factory()->role()->create(['ability' => 'permission3', 'model_id' => $role1->id]);
        $rolePermission4 = ModelPermission::factory()->role()->create(['ability' => 'permission1', 'model_id' => $role4->id]);

        $permission = ModelPermission::role($role1->id, 'permission1')->get();

        $this->assertCount(1, $permission);
        $this->assertEquals($rolePermission1->id, $permission->first()->id);
    }

    /** @test */
    public function role_returns_all_role_permissions()
    {
        $userPermissions = ModelPermission::factory()->count(5)->user()->create();
        $rolePermissions = ModelPermission::factory()->count(5)->role()->create();

        $permissions = ModelPermission::role()->get();

        foreach ($userPermissions as $permission) {
            $this->assertFalse($permissions->contains($permission));
        }
        foreach ($rolePermissions as $permission) {
            $this->assertTrue($permissions->contains($permission));
        }
    }

    /** @test */
    public function logic_returns_all_logic_permissions()
    {
        $logicPermissions = ModelPermission::factory()->count(5)->logic()->create();
        $groupPermissions = ModelPermission::factory()->count(5)->group()->create();

        $permissions = ModelPermission::logic()->get();

        foreach ($logicPermissions as $permission) {
            $this->assertTrue($permissions->contains($permission));
        }
        foreach ($groupPermissions as $permission) {
            $this->assertFalse($permissions->contains($permission));
        }
    }

    /** @test */
    public function logic_can_select_by_logic_id_and_ability()
    {
        $logic1 = Logic::factory()->create();
        $logic2 = Logic::factory()->create();
        $logic4 = Logic::factory()->create();
        $logicPermission1 = ModelPermission::factory()->logic()->create(['ability' => 'permission1', 'model_id' => $logic1->id]);
        $logicPermission2 = ModelPermission::factory()->logic()->create(['ability' => 'permission2', 'model_id' => $logic2->id]);
        $logicPermission3 = ModelPermission::factory()->logic()->create(['ability' => 'permission3', 'model_id' => $logic1->id]);
        $logicPermission4 = ModelPermission::factory()->logic()->create(['ability' => 'permission1', 'model_id' => $logic4->id]);

        $permission = ModelPermission::logic($logic1->id, 'permission1')->get();

        $this->assertCount(1, $permission);
        $this->assertModelEquals($logicPermission1, $permission->first());
    }

    /** @test */
    public function logic_can_select_by_logic_id_and_ability_and_module_instance()
    {
        $logic1 = Logic::factory()->create();
        $logic2 = Logic::factory()->create();
        $logic4 = Logic::factory()->create();
        $logicPermission1 = ModelPermission::factory()->logic()->create(['ability' => 'permission1', 'model_id' => $logic1->id, 'module_instance_id' => 1]);
        $logicPermission2 = ModelPermission::factory()->logic()->create(['ability' => 'permission2', 'model_id' => $logic2->id]);
        $logicPermission3 = ModelPermission::factory()->logic()->create(['ability' => 'permission1', 'model_id' => $logic1->id, 'module_instance_id' => 2]);
        $logicPermission4 = ModelPermission::factory()->logic()->create(['ability' => 'permission1', 'model_id' => $logic4->id]);

        $permission = ModelPermission::logic($logic1->id, 'permission1', 1)->get();

        $this->assertCount(1, $permission);
        $this->assertModelEquals($logicPermission1, $permission->first());
    }

    /** @test */
    public function user_can_select_by_user_id_and_ability_and_module_instance()
    {
        $user1 = $this->newUser();
        $user2 = $this->newUser();
        $user4 = $this->newUser();
        $userPermission1 = ModelPermission::factory()->user()->create(['ability' => 'permission1', 'model_id' => $user1->id, 'module_instance_id' => 1]);
        $userPermission2 = ModelPermission::factory()->user()->create(['ability' => 'permission2', 'model_id' => $user2->id]);
        $userPermission3 = ModelPermission::factory()->user()->create(['ability' => 'permission1', 'model_id' => $user1->id, 'module_instance_id' => 2]);
        $userPermission4 = ModelPermission::factory()->user()->create(['ability' => 'permission1', 'model_id' => $user4->id]);

        $permission = ModelPermission::user($user1->id, 'permission1', 1)->get();

        $this->assertCount(1, $permission);
        $this->assertModelEquals($userPermission1, $permission->first());
    }

    /** @test */
    public function group_can_select_by_group_id_and_ability_and_module_instance()
    {
        $group1 = $this->newGroup();
        $group2 = $this->newGroup();
        $group4 = $this->newGroup();
        $groupPermission1 = ModelPermission::factory()->group()->create(['ability' => 'permission1', 'model_id' => $group1->id, 'module_instance_id' => 1]);
        $groupPermission2 = ModelPermission::factory()->group()->create(['ability' => 'permission2', 'model_id' => $group2->id]);
        $groupPermission3 = ModelPermission::factory()->group()->create(['ability' => 'permission1', 'model_id' => $group1->id, 'module_instance_id' => 2]);
        $groupPermission4 = ModelPermission::factory()->group()->create(['ability' => 'permission1', 'model_id' => $group4->id]);

        $permission = ModelPermission::group($group1->id, 'permission1', 1)->get();

        $this->assertCount(1, $permission);
        $this->assertModelEquals($groupPermission1, $permission->first());
    }

    /** @test */
    public function role_can_select_by_role_id_and_ability_and_module_instance()
    {
        $role1 = $this->newRole();
        $role2 = $this->newRole();
        $role4 = $this->newRole();
        $rolePermission1 = ModelPermission::factory()->role()->create(['ability' => 'permission1', 'model_id' => $role1->id, 'module_instance_id' => 1]);
        $rolePermission2 = ModelPermission::factory()->role()->create(['ability' => 'permission2', 'model_id' => $role2->id]);
        $rolePermission3 = ModelPermission::factory()->role()->create(['ability' => 'permission1', 'model_id' => $role1->id, 'module_instance_id' => 2]);
        $rolePermission4 = ModelPermission::factory()->role()->create(['ability' => 'permission1', 'model_id' => $role4->id]);

        $permission = ModelPermission::role($role1->id, 'permission1', 1)->get();

        $this->assertCount(1, $permission);
        $this->assertModelEquals($rolePermission1, $permission->first());
    }

    /** @test */
    public function module_instance_returns_the_module_instance()
    {
        $role1 = $this->newRole();
        $moduleInstance = ModuleInstance::factory()->create();
        $permission = ModelPermission::factory()->role()->create(['ability' => 'permission1', 'model_id' => $role1->id, 'module_instance_id' => $moduleInstance->id]);

        $this->assertModelEquals($moduleInstance, $permission->moduleInstance);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $modelPermission = ModelPermission::factory()->create(['ability' => 'OldAbility']);

        $modelPermission->ability = 'NewAbility';
        $modelPermission->save();

        $this->assertEquals(1, $modelPermission->revisionHistory->count());
        $this->assertEquals($modelPermission->id, $modelPermission->revisionHistory->first()->revisionable_id);
        $this->assertEquals(ModelPermission::class, $modelPermission->revisionHistory->first()->revisionable_type);
        $this->assertEquals('ability', $modelPermission->revisionHistory->first()->key);
        $this->assertEquals('OldAbility', $modelPermission->revisionHistory->first()->old_value);
        $this->assertEquals('NewAbility', $modelPermission->revisionHistory->first()->new_value);
    }
}
