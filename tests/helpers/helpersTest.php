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
    
    /** @test */
    public function alias_returns_the_alias_of_a_module(){
        $moduleInstance = factory(ModuleInstance::class)->create(['alias' => 'alias1']);
        $this->app->instance(ModuleInstance::class, $moduleInstance);
        
        $this->assertEquals('alias1', alias());
    }
    
    /** @test */
    public function alias_throws_an_exception_if_no_module_bound_to_container(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Alias cannot be returned outside a module environment');
        
        alias();
    }
    
}