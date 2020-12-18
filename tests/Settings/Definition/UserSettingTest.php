<?php


namespace BristolSU\Support\Tests\Settings\Definition;


use BristolSU\Support\Settings\Definition\UserSetting;
use BristolSU\Support\Settings\SettingRepository;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class UserSettingTest extends TestCase
{

    /** @test */
    public function value_gets_the_value_of_the_setting_with_the_user_id(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->getUserValue('my key', 2)->shouldBeCalled()->willReturn('Setting value');
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        $userSetting = new UserSettingTestDummyUserSetting();
        $this->assertEquals('Setting value', $userSetting->value(2));
    }

    /** @test */
    public function getValue_gets_the_value_of_the_setting(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->getUserValue('my key', 3)->shouldBeCalled()->willReturn('Setting value');
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        $this->assertEquals('Setting value', UserSettingTestDummyUserSetting::getValue(3));
    }

    /** @test */
    public function getKey_gets_the_key_of_the_setting(){
        $this->assertEquals('my key', UserSettingTestDummyUserSetting::getKey());
    }

    /** @test */
    public function validator_returns_a_validator_instance_with_the_right_rules(){
        $userSetting = new UserSettingTestDummyUserSetting();
        $validator = $userSetting->validator('sometest');

        $this->assertInstanceOf(Validator::class, $validator);
        $this->assertFalse($validator->fails());

        $userSetting2 = new UserSettingTestDummyUserSetting();
        $validator2 = $userSetting2->validator(['sometest']);
        $this->assertInstanceOf(Validator::class, $validator2);
        $this->assertTrue($validator2->fails());
    }

    /** @test */
    public function inputName_hashes_the_key(){
        $setting = new UserSettingTestDummyUserSetting();
        $this->assertEquals(sha1('my key'), $setting->inputName());
    }

    /** @test */
    public function setValue_sets_the_value_for_a_specific_user(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->setForUser('my key', 'value1', 2)->shouldBeCalled();
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        UserSettingTestDummyUserSetting::setValue('value1', 2);
    }

    /** @test */
    public function setSettingValue_sets_the_value_for_a_specific_user(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->setForUser('my key', 'value1', 2)->shouldBeCalled();
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        $setting = new UserSettingTestDummyUserSetting();
        $setting->setSettingValue('value1', 2);
    }

    /** @test */
    public function setDefault_sets_the_value_for_all_users(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->setForAllUsers('my key', 'value1')->shouldBeCalled();
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        UserSettingTestDummyUserSetting::setDefault('value1');
    }

    /** @test */
    public function setSettingDefault_sets_the_value_for_all_users(){
        $settingRepository = $this->prophesize(SettingRepository::class);
        $settingRepository->setForAllUsers('my key', 'value1')->shouldBeCalled();
        $this->instance(SettingRepository::class, $settingRepository->reveal());

        $setting = new UserSettingTestDummyUserSetting();
        $setting->setSettingDefault('value1');
    }

}

class UserSettingTestDummyUserSetting extends UserSetting
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
}
