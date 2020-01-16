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

}
