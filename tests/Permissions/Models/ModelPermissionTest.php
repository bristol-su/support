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
        $userPermissions = factory(ModelPermission::class, 5)->state('user')->create();
        $groupPermissions = factory(ModelPermission::class, 5)->state('group')->create();

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
        $userPermission1 = factory(ModelPermission::class)->state('user')->create(['ability' => 'permission1', 'model_id' => $user1->id]);
        $userPermission2 = factory(ModelPermission::class)->state('user')->create(['ability' => 'permission2', 'model_id' => $user2->id]);
        $userPermission3 = factory(ModelPermission::class)->state('user')->create(['ability' => 'permission3', 'model_id' => $user1->id]);
        $userPermission4 = factory(ModelPermission::class)->state('user')->create(['ability' => 'permission1', 'model_id' => $user4->id]);

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
        $groupPermission1 = factory(ModelPermission::class)->state('group')->create(['ability' => 'permission1', 'model_id' => $group1->id]);
        $groupPermission2 = factory(ModelPermission::class)->state('group')->create(['ability' => 'permission2', 'model_id' => $group2->id]);
        $groupPermission3 = factory(ModelPermission::class)->state('group')->create(['ability' => 'permission3', 'model_id' => $group1->id]);
        $groupPermission4 = factory(ModelPermission::class)->state('group')->create(['ability' => 'permission1', 'model_id' => $group4->id]);

        $permission = ModelPermission::group($group1->id, 'permission1')->get();

        $this->assertCount(1, $permission);
        $this->assertEquals($groupPermission1->id, $permission->first()->id);
    }

    /** @test */
    public function group_returns_all_group_permissions()
    {
        $userPermissions = factory(ModelPermission::class, 5)->state('user')->create();
        $groupPermissions = factory(ModelPermission::class, 5)->state('group')->create();

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
        $rolePermission1 = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission1', 'model_id' => $role1->id]);
        $rolePermission2 = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission2', 'model_id' => $role2->id]);
        $rolePermission3 = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission3', 'model_id' => $role1->id]);
        $rolePermission4 = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission1', 'model_id' => $role4->id]);

        $permission = ModelPermission::role($role1->id, 'permission1')->get();

        $this->assertCount(1, $permission);
        $this->assertEquals($rolePermission1->id, $permission->first()->id);
    }

    /** @test */
    public function role_returns_all_role_permissions()
    {
        $userPermissions = factory(ModelPermission::class, 5)->state('user')->create();
        $rolePermissions = factory(ModelPermission::class, 5)->state('role')->create();

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
        $logicPermissions = factory(ModelPermission::class, 5)->state('logic')->create();
        $groupPermissions = factory(ModelPermission::class, 5)->state('group')->create();

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
        $logic1 = factory(Logic::class)->create();
        $logic2 = factory(Logic::class)->create();
        $logic4 = factory(Logic::class)->create();
        $logicPermission1 = factory(ModelPermission::class)->state('logic')->create(['ability' => 'permission1', 'model_id' => $logic1->id]);
        $logicPermission2 = factory(ModelPermission::class)->state('logic')->create(['ability' => 'permission2', 'model_id' => $logic2->id]);
        $logicPermission3 = factory(ModelPermission::class)->state('logic')->create(['ability' => 'permission3', 'model_id' => $logic1->id]);
        $logicPermission4 = factory(ModelPermission::class)->state('logic')->create(['ability' => 'permission1', 'model_id' => $logic4->id]);

        $permission = ModelPermission::logic($logic1->id, 'permission1')->get();

        $this->assertCount(1, $permission);
        $this->assertModelEquals($logicPermission1, $permission->first());
    }

    /** @test */
    public function logic_can_select_by_logic_id_and_ability_and_module_instance()
    {
        $logic1 = factory(Logic::class)->create();
        $logic2 = factory(Logic::class)->create();
        $logic4 = factory(Logic::class)->create();
        $logicPermission1 = factory(ModelPermission::class)->state('logic')->create(['ability' => 'permission1', 'model_id' => $logic1->id, 'module_instance_id' => 1]);
        $logicPermission2 = factory(ModelPermission::class)->state('logic')->create(['ability' => 'permission2', 'model_id' => $logic2->id]);
        $logicPermission3 = factory(ModelPermission::class)->state('logic')->create(['ability' => 'permission1', 'model_id' => $logic1->id, 'module_instance_id' => 2]);
        $logicPermission4 = factory(ModelPermission::class)->state('logic')->create(['ability' => 'permission1', 'model_id' => $logic4->id]);

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
        $userPermission1 = factory(ModelPermission::class)->state('user')->create(['ability' => 'permission1', 'model_id' => $user1->id, 'module_instance_id' => 1]);
        $userPermission2 = factory(ModelPermission::class)->state('user')->create(['ability' => 'permission2', 'model_id' => $user2->id]);
        $userPermission3 = factory(ModelPermission::class)->state('user')->create(['ability' => 'permission1', 'model_id' => $user1->id, 'module_instance_id' => 2]);
        $userPermission4 = factory(ModelPermission::class)->state('user')->create(['ability' => 'permission1', 'model_id' => $user4->id]);

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
        $groupPermission1 = factory(ModelPermission::class)->state('group')->create(['ability' => 'permission1', 'model_id' => $group1->id, 'module_instance_id' => 1]);
        $groupPermission2 = factory(ModelPermission::class)->state('group')->create(['ability' => 'permission2', 'model_id' => $group2->id]);
        $groupPermission3 = factory(ModelPermission::class)->state('group')->create(['ability' => 'permission1', 'model_id' => $group1->id, 'module_instance_id' => 2]);
        $groupPermission4 = factory(ModelPermission::class)->state('group')->create(['ability' => 'permission1', 'model_id' => $group4->id]);

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
        $rolePermission1 = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission1', 'model_id' => $role1->id, 'module_instance_id' => 1]);
        $rolePermission2 = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission2', 'model_id' => $role2->id]);
        $rolePermission3 = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission1', 'model_id' => $role1->id, 'module_instance_id' => 2]);
        $rolePermission4 = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission1', 'model_id' => $role4->id]);

        $permission = ModelPermission::role($role1->id, 'permission1', 1)->get();

        $this->assertCount(1, $permission);
        $this->assertModelEquals($rolePermission1, $permission->first());
    }

    /** @test */
    public function module_instance_returns_the_module_instance()
    {
        $role1 = $this->newRole();
        $moduleInstance = factory(ModuleInstance::class)->create();
        $permission = factory(ModelPermission::class)->state('role')->create(['ability' => 'permission1', 'model_id' => $role1->id, 'module_instance_id' => $moduleInstance->id]);
        
        $this->assertModelEquals($moduleInstance, $permission->moduleInstance);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $modelPermission = factory(ModelPermission::class)->create(['ability' => 'OldAbility']);

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
