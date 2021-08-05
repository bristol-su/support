<?php

namespace BristolSU\Support\Tests\helpers;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\Permissions\Contracts\PermissionTester;
use BristolSU\Support\Settings\Setting;
use BristolSU\Support\Tests\TestCase;

class helpersTest extends TestCase
{
    /** @test */
    public function settings_returns_the_given_setting_for_the_module_instance()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        ModuleInstanceSetting::factory()->create(['key' => 'setting1', 'value' => 'value1', 'module_instance_id' => $moduleInstance->id]);
        ModuleInstanceSetting::factory()->create(['key' => 'setting2', 'value' => 'value2', 'module_instance_id' => $moduleInstance->id]);

        $this->instance(ModuleInstance::class, $moduleInstance);
        $this->assertEquals('value1', settings('setting1'));
        $this->assertEquals('value2', settings('setting2'));
    }

    /** @test */
    public function settings_returns_the_default_setting_if_setting_not_found()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        ModuleInstanceSetting::factory()->create(['key' => 'setting1', 'value' => 'value1', 'module_instance_id' => $moduleInstance->id]);
        ModuleInstanceSetting::factory()->create(['key' => 'setting2', 'value' => 'value2', 'module_instance_id' => $moduleInstance->id]);

        $this->instance(ModuleInstance::class, $moduleInstance);
        $this->assertEquals('default', settings('setting3', 'default'));
    }

    /** @test */
    public function settings_returns_all_settings_if_no_key_given()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        ModuleInstanceSetting::factory()->create(['key' => 'setting1', 'value' => 'value1', 'module_instance_id' => $moduleInstance->id]);
        ModuleInstanceSetting::factory()->create(['key' => 'setting2', 'value' => 'value2', 'module_instance_id' => $moduleInstance->id]);

        $this->instance(ModuleInstance::class, $moduleInstance);
        $this->assertEquals([
            'setting1' => 'value1',
            'setting2' => 'value2'
        ], settings());
    }

    /** @test */
    public function alias_returns_the_alias_of_a_module()
    {
        $moduleInstance = ModuleInstance::factory()->create(['alias' => 'alias1']);
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertEquals('alias1', alias());
    }

    /** @test */
    public function alias_throws_an_exception_if_no_module_bound_to_container()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Alias cannot be returned outside a module environment');

        alias();
    }

    /** @test */
    public function has_permission_evaluates_a_permission_using_evaluate_if_no_credentials_given()
    {
        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluate('ability1')->shouldBeCalled()->willReturn(true);
        \BristolSU\Support\Permissions\Facade\PermissionTester::swap($permissionTester->reveal());
        hasPermission('ability1');
    }

    /** @test */
    public function has_permission_returns_true_if_no_credentials_given_and_evaluate_is_true()
    {
        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluate('ability1')->shouldBeCalled()->willReturn(true);
        \BristolSU\Support\Permissions\Facade\PermissionTester::swap($permissionTester->reveal());

        $this->assertTrue(hasPermission('ability1'));
    }

    /** @test */
    public function has_permission_returns_false_if_no_credentials_given_and_evaluate_is_false()
    {
        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluate('ability1')->shouldBeCalled()->willReturn(false);
        \BristolSU\Support\Permissions\Facade\PermissionTester::swap($permissionTester->reveal());

        $this->assertFalse(hasPermission('ability1'));
    }

    /** @test */
    public function has_permission_evaluates_a_permission_using_evaluate_for_if_credentials_given()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluateFor('ability1', $user, $group, $role)->shouldBeCalled()->willReturn(true);
        \BristolSU\Support\Permissions\Facade\PermissionTester::swap($permissionTester->reveal());

        hasPermission('ability1', $user, $group, $role);
    }

    /** @test */
    public function has_permission_returns_true_if_credentials_given_and_evaluate_for_is_true()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluateFor('ability1', $user, $group, $role)->shouldBeCalled()->willReturn(true);
        \BristolSU\Support\Permissions\Facade\PermissionTester::swap($permissionTester->reveal());

        $this->assertTrue(hasPermission('ability1', $user, $group, $role));
    }

    /** @test */
    public function has_permission_returns_false_if_credentials_given_and_evaluate_for_is_false()
    {
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $permissionTester = $this->prophesize(PermissionTester::class);
        $permissionTester->evaluateFor('ability1', $user, $group, $role)->shouldBeCalled()->willReturn(false);
        \BristolSU\Support\Permissions\Facade\PermissionTester::swap($permissionTester->reveal());

        $this->assertFalse(hasPermission('ability1', $user, $group, $role));
    }

    /** @test */
    public function global_setting_returns_the_value_of_a_global_setting()
    {
        $setting = $this->prophesize(Setting::class);
        $setting->getGlobalValue('key')->shouldBeCalled()->willReturn('val');
        $this->instance(Setting::class, $setting->reveal());

        $this->assertEquals('val', globalSetting('key'));
    }

    /** @test */
    public function user_setting_returns_the_value_of_a_user_setting()
    {
        $setting = $this->prophesize(Setting::class);
        $setting->getUserValue('key', 3)->shouldBeCalled()->willReturn('val');
        $this->instance(Setting::class, $setting->reveal());

        $this->assertEquals('val', userSetting('key', 3));
    }
}
