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
        $settings = factory(ModuleInstanceSetting::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create(['module_instance_settings_id' => $settings->id]);
        $this->assertInstanceOf(ModuleInstance::class, $settings->moduleInstance);
        $this->assertModelEquals($moduleInstance, $settings->moduleInstance);
    }

}
