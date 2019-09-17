<?php


namespace BristolSU\Support\Tests\ModuleInstance\Settings;


use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceSettingsTest extends TestCase
{

    /** @test */
    public function it_has_a_module_instance()
    {
        $moduleInstance = factory(ModuleInstance::class)->create();
        $setting = factory(ModuleInstanceSetting::class)->create(['module_instance_id' => $moduleInstance->id]);
        
        $this->assertInstanceOf(ModuleInstance::class, $setting->moduleInstance);
        $this->assertModelEquals($moduleInstance, $setting->moduleInstance);
    }
    
    /** @test */
    public function setValue_sets_a_value_in_attributes(){
        $setting = factory(ModuleInstanceSetting::class)->create();
        $setting->value = 'test';
        $setting->save();
        
        $this->assertDatabaseHas('module_instance_settings', [
            'id' => $setting->id,
            'value' => 'test',
            'encoded' => 0
        ]);
    }

    /** @test */
    public function getValue_gets_a_value_in_attributes(){
        $setting = factory(ModuleInstanceSetting::class)->create();
        $setting->value = 'test';
        $setting->save();

        $this->assertEquals('test', $setting->value);
    }
    
    /** @test */
    public function setValue_encodes_an_object_in_attributes(){
        $setting = factory(ModuleInstanceSetting::class)->create();
        $setting->value = ['test' => 1];
        $setting->save();

        $this->assertDatabaseHas('module_instance_settings', [
            'id' => $setting->id,
            'value' => '{"test":1}',
            'encoded' => 1
        ]);
    }
    
    /** @test */
    public function getValue_decodes_an_object_in_attributes(){
        $setting = factory(ModuleInstanceSetting::class)->create();
        $setting->value = ['test' => 1];
        $setting->save();

        $this->assertEquals(['test' => 1], $setting->value);
    }

    /** @test */
    public function setValue_encodes_an_array_in_attributes(){
        $setting = factory(ModuleInstanceSetting::class)->create();
        $setting->value = ['test', 'test2'];
        $setting->save();

        $this->assertDatabaseHas('module_instance_settings', [
            'id' => $setting->id,
            'value' => '["test","test2"]',
            'encoded' => 1
        ]);
    }

    /** @test */
    public function getValue_decodes_an_array_in_attributes(){
        $setting = factory(ModuleInstanceSetting::class)->create();
        $setting->value = ['test', 'test2'];
        $setting->save();

        $this->assertEquals(['test', 'test2'], $setting->value);
    }

}
