<?php

namespace BristolSU\Support\Tests\Settings;

use BristolSU\Support\Settings\Definition\Category;
use BristolSU\Support\Settings\Definition\GlobalSetting;
use BristolSU\Support\Settings\Definition\Group;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Definition\UserSetting;
use BristolSU\Support\Settings\Saved\SavedSettingRepository;
use BristolSU\Support\Settings\Setting;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SettingTest extends TestCase
{

    public function newSettingCategory(string $key, $name = 'CategoryName', $description = 'CategoryDescription')
    {
        return new SettingTestDummyCategory($key, $name, $description);
    }

    public function newSettingGroup(string $key, $name = 'GroupName', $description = 'GroupDescription')
    {
        return new SettingTestDummyGroup($key, $name, $description);
    }

    public function newGlobalSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }
        return new SettingTestDummyGlobalSetting($key, $defaultValue, $field, $validator);
    }

    public function newUserSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }
        return new SettingTestDummyUserSetting($key, $defaultValue, $field, $validator);
    }

    /** @test */
    public function getUserValue_returns_the_user_value(){
        $settingStore = $this->prophesize(SettingStore::class);
        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->getUserValue('setting_key', 44)->shouldBeCalled()->willReturn('global_value');

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals('global_value', $class->getUserValue('setting_key', 44));
    }

    /** @test */
    public function getUserValue_uses_the_authenticated_user_if_user_id_is_null(){
        $user = $this->newUser();
        $this->beUser($user);

        $settingStore = $this->prophesize(SettingStore::class);
        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->getUserValue('setting_key', $user->id())->shouldBeCalled()->willReturn('global_value');

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals('global_value', $class->getUserValue('setting_key'));
    }

    /** @test */
    public function getUserValue_returns_the_default_value_if_no_user_id_is_given_and_no_user_is_logged_in(){
        $setting = $this->newUserSetting('setting_key', 'Default Value - Test');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('setting_key')->willReturn($setting);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals('Default Value - Test', $class->getUserValue('setting_key'));
    }

    /** @test */
    public function getUserValue_returns_the_default_value_if_the_saved_settings_repository_throws_an_exception(){
        $setting = $this->newUserSetting('setting_key', 'Default Value - Test');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('setting_key')->willReturn($setting);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->getUserValue('setting_key', 3)->shouldBeCalled()->willThrow(new ModelNotFoundException());

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals('Default Value - Test', $class->getUserValue('setting_key', 3));
    }

    /** @test */
    public function getGlobalValue_gets_the_global_value_for_a_setting(){
        $settingStore = $this->prophesize(SettingStore::class);
        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->getGlobalValue('setting_key')->shouldBeCalled()->willReturn('global_value');

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals('global_value', $class->getGlobalValue('setting_key'));
    }

    /** @test */
    public function getGlobalValue_returns_the_default_value_if_the_saved_settings_repository_throws_an_exception(){
        $setting = $this->newGlobalSetting('setting_key', 'Default Value - Test');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getSetting('setting_key')->willReturn($setting);

        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->getGlobalValue('setting_key')->shouldBeCalled()->willThrow(new ModelNotFoundException());

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $this->assertEquals('Default Value - Test', $class->getGlobalValue('setting_key'));
    }

    /** @test */
    public function setForUser_sets_the_setting_for_a_user(){
        $settingStore = $this->prophesize(SettingStore::class);
        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->setForUser('setting_key', 'setting_value', 4)->shouldBeCalled();

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $class->setForUser('setting_key', 'setting_value', 4);
    }

    /** @test */
    public function setForUser_sets_the_setting_for_the_logged_in_user_if_no_user_id_given(){
        $user = $this->newUser();
        $this->beUser($user);

        $settingStore = $this->prophesize(SettingStore::class);
        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->setForUser('setting_key', 'setting_value', $user->id())->shouldBeCalled();

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $class->setForUser('setting_key', 'setting_value');
    }

    /** @test */
    public function setForUser_prioritises_the_given_user_id(){
        $user = $this->newUser();
        $this->beUser($user);

        $settingStore = $this->prophesize(SettingStore::class);
        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->setForUser('setting_key', 'setting_value', $user->id() + 1)->shouldBeCalled();

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $class->setForUser('setting_key', 'setting_value',  $user->id() + 1);
    }

    /** @test */
    public function setForAllUsers_sets_a_default_user_setting(){
        $settingStore = $this->prophesize(SettingStore::class);
        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->setForAllUsers('setting_key', 'setting_value')->shouldBeCalled();

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $class->setForAllUsers('setting_key', 'setting_value');
    }

    /** @test */
    public function setGlobal_sets_a_global_setting_value(){
        $settingStore = $this->prophesize(SettingStore::class);
        $savedSettingRepository = $this->prophesize(SavedSettingRepository::class);
        $savedSettingRepository->setGlobal('setting_key', 'setting_value')->shouldBeCalled();

        $class = new Setting($settingStore->reveal(), $savedSettingRepository->reveal());
        $class->setGlobal('setting_key', 'setting_value');
    }


}

class SettingTestDummyCategory extends Category
{

    public string $key;
    public string $name;
    public string $description;

    public function __construct(string $key, string $name = 'SettingName', string $description = 'SettingDescription')
    {
        $this->key = $key;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * The key of the category
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * The name for the category
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * A description for the category
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }
}

class SettingTestDummyGroup extends Group
{

    public string $key;
    public string $name;
    public string $description;

    public function __construct(string $key, string $name = 'SettingName', string $description = 'SettingDescription')
    {
        $this->key = $key;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * The key of the group
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * The name for the group
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * A description for the group
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }
}

class SettingTestDummyUserSetting extends UserSetting
{

    public string $key;
    public $defaultValue;
    /**
     * @var Field
     */
    public Field $fieldOptions;
    /**
     * @var Validator
     */
    public Validator $validator;

    public function __construct(string $key, $defaultValue, Field $fieldOptions, Validator $validator)
    {
        $this->key = $key;
        $this->defaultValue = $defaultValue;
        $this->fieldOptions = $fieldOptions;
        $this->validator = $validator;
    }

    /**
     * The key for the setting
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * The default value of the setting
     *
     * @return mixed
     */
    public function defaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * The field schema to show the user when editing the value
     *
     * @return Field
     */
    public function fieldOptions(): Field
    {
        return $this->fieldOptions;
    }

    /**
     * A validator to validate any new values
     *
     * @param mixed $value The new value
     * @return Validator
     */
    public function validator($value): Validator
    {
        return $this->validator;
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
        return [];
    }
}

class SettingTestDummyGlobalSetting extends GlobalSetting
{

    public string $key;
    public $defaultValue;
    /**
     * @var Field
     */
    public Field $fieldOptions;
    /**
     * @var Validator
     */
    public Validator $validator;

    public function __construct(string $key, $defaultValue, Field $fieldOptions, Validator $validator)
    {
        $this->key = $key;
        $this->defaultValue = $defaultValue;
        $this->fieldOptions = $fieldOptions;
        $this->validator = $validator;
    }

    /**
     * The key for the setting
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * The default value of the setting
     *
     * @return mixed
     */
    public function defaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * The field schema to show the global when editing the value
     *
     * @return Field
     */
    public function fieldOptions(): Field
    {
        return $this->fieldOptions;
    }

    /**
     * A validator to validate any new values
     *
     * @param mixed $value The new value
     * @return Validator
     */
    public function validator($value): Validator
    {
        return $this->validator;
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
        return [];
    }
}

