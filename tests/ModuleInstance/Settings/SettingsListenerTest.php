<?php

namespace BristolSU\Support\Tests\ModuleInstance\Settings;

use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\ModuleInstance\Settings\SettingListener;
use BristolSU\Support\Tests\TestCase;

class SettingsListenerTest extends TestCase
{
    /** @test */
    public function handle_returns_false_if_key_not_the_same_as_the_setting_key()
    {
        $setting = factory(ModuleInstanceSetting::class)->create(['key' => 'not-a-key']);
        
        $listener = new Listener();
        $this->assertFalse(
            $listener->handle('eloquent.saving: ' . ModuleInstanceSetting::class, [$setting])
        );
        $this->assertEquals([], $listener->calls);
    }
    
    /** @test */
    public function handle_calls_the_correct_functions_and_passes_the_correct_attribute()
    {
        $setting = factory(ModuleInstanceSetting::class)->create(['key' => 'a-key']);

        $listener = new Listener();
        
        $listener->handle('eloquent.saving: ' . ModuleInstanceSetting::class, [$setting]);
        
        $this->assertEquals([
            ['name' => 'saving', 'setting' => $setting->toArray()]
        ], $listener->calls);
    }
    
    /** @test */
    public function handle_returns_null_if_method_not_defined()
    {
        $setting = factory(ModuleInstanceSetting::class)->create(['key' => 'a-key']);

        $listener = new Listener();

        $this->assertNull($listener->handle('eloquent.updating: ' . ModuleInstanceSetting::class, [$setting]));
        $this->assertEquals([], $listener->calls);
    }
    
    /** @test */
    public function handle_returns_function_result_if_method_defined()
    {
        $setting = factory(ModuleInstanceSetting::class)->create(['key' => 'a-key']);

        $listener = new Listener();
        $listener->return = true;
        
        $this->assertTrue(
            $listener->handle('eloquent.saving: ' . ModuleInstanceSetting::class, [$setting])
        );
    }
}

class Listener extends SettingListener
{
    protected $key = 'a-key';

    public $calls = [];
    
    public $return = true;
    
    public function onSaving($setting)
    {
        $this->calls[] = ['name' => 'saving', 'setting' => $setting->toArray()];

        return $this->return;
    }
}
