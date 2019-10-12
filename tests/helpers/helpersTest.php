<?php

namespace BristolSU\Support\Tests\helpers;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class helpersTest extends TestCase
{

    /** @test */
    public function settings_returns_the_given_setting_for_the_module_instance(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstance->moduleInstanceSettings->settings = [
            'setting1' => 'value1',
            'setting2' => 'value2'
        ];
        $moduleInstance->moduleInstanceSettings->save();
        $this->instance(ModuleInstance::class, $moduleInstance);
        $this->assertEquals('value1', settings('setting1'));
        $this->assertEquals('value2', settings('setting2'));
    }

    /** @test */
    public function settings_returns_the_default_setting_if_setting_not_found(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstance->moduleInstanceSettings->settings = [
            'setting1' => 'value1',
            'setting2' => 'value2'
        ];
        $moduleInstance->moduleInstanceSettings->save();
        $this->instance(ModuleInstance::class, $moduleInstance);
        $this->assertEquals('default', settings('setting3', 'default'));
    }
    
    /** @test */
    public function settings_returns_all_settings_if_no_key_given(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $moduleInstance->moduleInstanceSettings->settings = [
            'setting1' => 'value1',
            'setting2' => 'value2'
        ];
        $moduleInstance->moduleInstanceSettings->save();
        $this->instance(ModuleInstance::class, $moduleInstance);
        $this->assertEquals([
            'setting1' => 'value1',
            'setting2' => 'value2'
            ], settings());
    }
    
}