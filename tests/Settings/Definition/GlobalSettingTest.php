<?php


namespace BristolSU\Support\Tests\Settings\Definition;


use BristolSU\Support\Settings\Definition\GlobalSetting;
use BristolSU\Support\Settings\SettingRepository;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;

class GlobalSettingTest extends TestCase
{

    /** @test */
    public function value_gets_the_value_of_the_setting(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->getGlobalValue('my key')->shouldBeCalled()->willReturn('Setting value');
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        $globalSetting = new GlobalSettingTestDummyGlobalSetting();
        $this->assertEquals('Setting value', $globalSetting->value());
    }

    /** @test */
    public function getValue_gets_the_value_of_the_setting(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->getGlobalValue('my key')->shouldBeCalled()->willReturn('Setting value');
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        $this->assertEquals('Setting value', GlobalSettingTestDummyGlobalSetting::getValue());
    }

    /** @test */
    public function getKey_gets_the_key_of_the_setting(){
        $this->assertEquals('my key', GlobalSettingTestDummyGlobalSetting::getKey());
    }

    /** @test */
    public function validator_returns_a_validator_instance_with_the_right_rules(){
        $globalSetting = new GlobalSettingTestDummyGlobalSetting();
        $validator = $globalSetting->validator('sometest');

        $this->assertInstanceOf(Validator::class, $validator);
        $this->assertEquals([$globalSetting->inputName() => 'sometest'], $validator->validate());
        $this->assertFalse($validator->fails());

        $globalSetting2 = new GlobalSettingTestDummyGlobalSetting();
        $validator2 = $globalSetting2->validator(['sometest']);
        $this->assertInstanceOf(Validator::class, $validator2);
        $this->assertTrue($validator2->fails());
    }

    /** @test */
    public function inputName_hashes_the_key(){
        $setting = new GlobalSettingTestDummyGlobalSetting();
        $this->assertEquals(sha1('my key'), $setting->inputName());
    }

    /** @test */
    public function setValue_sets_the_value(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->setGlobal('my key', 'value1')->shouldBeCalled();
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        GlobalSettingTestDummyGlobalSetting::setValue('value1');
    }

    /** @test */
    public function setSettingValue_sets_the_value(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->setGlobal('my key', 'value1')->shouldBeCalled();
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        $setting = new GlobalSettingTestDummyGlobalSetting();
        $setting->setSettingValue('value1');
    }

    /** @test */
    public function shouldEncrypt_returns_the_value_of_the_encrypt_property(){
        $setting = new GlobalSettingTestDummyGlobalSetting();

        $setting->setShouldEncrypt(true);
        $this->assertTrue($setting->shouldEncrypt());

        $setting->setShouldEncrypt(false);
        $this->assertFalse($setting->shouldEncrypt());
    }

}

class GlobalSettingTestDummyGlobalSetting extends GlobalSetting
{

    /**
     * The key for the setting
     *
     * @return string
     */
    public function key(): string
    {
        return 'my key';
    }

    /**
     * The default value of the setting
     *
     * @return mixed
     */
    public function defaultValue()
    {
        return 'abc';
    }

    /**
     * The field schema to show the user when editing the value
     *
     * @return Field
     */
    public function fieldOptions(): Field
    {
        return \FormSchema\Generator\Field::input($this->inputName())->inputType('text')->getSchema();
    }


    /**
     * Return the validation rules for the setting.
     *
     * The key to use for the rules is data. You may also override the validator method to customise the validator further
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            $this->inputName() => 'required|string'
        ];
    }

    public function setShouldEncrypt(bool $val)
    {
        $this->encrypt = $val;
    }
}
