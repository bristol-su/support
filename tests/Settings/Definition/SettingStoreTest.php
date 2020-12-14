<?php

namespace BristolSU\Support\Tests\Settings\Definition;

use BristolSU\Support\Settings\Definition\Category;
use BristolSU\Support\Settings\Definition\GlobalSetting;
use BristolSU\Support\Settings\Definition\Group;
use BristolSU\Support\Settings\Definition\Setting;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Definition\UserSetting;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;
use Prophecy\PhpUnit\ProphecyTrait;

class SettingStoreTest extends TestCase
{

    public function newSettingCategory(string $key, $name = 'CategoryName', $description = 'CategoryDescription')
    {
        return new SettingStoreTestDummyCategory($key, $name, $description);
    }

    public function newSettingGroup(string $key, $name = 'GroupName', $description = 'GroupDescription')
    {
        return new SettingStoreTestDummyGroup($key, $name, $description);
    }

    public function newGlobalSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }
        return new SettingStoreTestDummyGlobalSetting($key, $defaultValue, $field, $validator);
    }

    public function newUserSetting(string $key, $defaultValue, Field $field = null, Validator $validator = null)
    {
        if($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }
        return new SettingStoreTestDummyUserSetting($key, $defaultValue, $field, $validator);
    }

    /** @test */
    public function a_global_setting_can_be_registered_and_retrieved(){
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newGlobalSetting('setting_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);

        $this->assertSame($setting, $store->getSetting('setting_key'));
    }

    /** @test */
    public function getSetting_throws_an_exception_if_the_setting_is_not_registered(){
        $this->expectException(\Exception::class);
        $this->expectDeprecationMessage('Setting definition with key [setting_key] could not be found');

        $store = new SettingStore();

        $store->getSetting('setting_key');
    }

    /** @test */
    public function a_group_can_be_registered_and_retrieved(){

    }

    /** @test */
    public function getGroup_throws_an_exception_if_the_group_is_not_registered(){

    }

    /** @test */
    public function a_category_can_be_registered_and_retrieved(){

    }

    /** @test */
    public function getCategory_throws_an_exception_if_the_category_is_not_registered(){

    }

    /** @test */
    public function getGlobalSettingsInGroup_gets_all_global_setting_in_the_given_group_and_category(){

    }

    /** @test */
    public function getUserSettingsInGroup_gets_all_user_setting_in_the_given_group_and_category(){

    }

    /** @test */
    public function getAllSettingsInGroup_gets_all_the_settings_in_the_given_group_and_category(){

    }

    /** @test */
    public function getAllGroupsInCategory_gets_all_the_groups_in_the_given_category(){

    }

    /** @test */
    public function getCategories_gets_all_categories(){

    }
}

class SettingStoreTestDummyCategory extends Category
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

class SettingStoreTestDummyGroup extends Group
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

class SettingStoreTestDummyUserSetting extends UserSetting
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
}

class SettingStoreTestDummyGlobalSetting extends GlobalSetting
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
}
