<?php


namespace BristolSU\Support\Tests\Settings\Concerns;


use BristolSU\Support\Settings\Concerns\RegistersSettings;
use BristolSU\Support\Settings\Definition\SettingRegistrar;
use BristolSU\Support\Tests\TestCase;

class RegistersSettingsTest extends TestCase
{
    use RegistersSettings;

    /** @test */
    public function registerSettings_returns_an_instance_of_the_setting_registrar(){
        $this->assertInstanceOf(SettingRegistrar::class, $this->registerSettings());
    }

    /** @test */
    public function registerSettings_resolves_the_setting_registrar(){
        $settingRegistrar = $this->prophesize(SettingRegistrar::class);
        $this->instance(SettingRegistrar::class, $settingRegistrar->reveal());

        $resolved = $this->registerSettings();
        $this->assertInstanceOf(SettingRegistrar::class, $resolved);
        $this->assertSame($settingRegistrar->reveal(), $resolved);
    }

}
