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
        if ($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if ($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }

        return new SettingStoreTestDummyGlobalSetting($key, $defaultValue, $field, $validator);
    }

    public function newUserSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if ($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if ($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }

        return new SettingStoreTestDummyUserSetting($key, $defaultValue, $field, $validator);
    }

    /** @test */
    public function a_global_setting_can_be_registered_and_retrieved()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newGlobalSetting('setting_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);

        $this->assertSame($setting, $store->getSetting('setting_key'));
    }

    /** @test */
    public function get_setting_throws_an_exception_if_the_setting_is_not_registered()
    {
        $this->expectException(\Exception::class);
        $this->expectDeprecationMessage('Setting definition with key [setting_key] could not be found');

        $store = new SettingStore();

        $store->getSetting('setting_key');
    }

    /** @test */
    public function a_setting_can_be_registered_twice_and_retrieved()
    {
        $category = $this->newSettingCategory('cat_key');
        $category2 = $this->newSettingCategory('cat2_key');
        $group = $this->newSettingGroup('group_key');
        $group2 = $this->newSettingGroup('group2_key');
        $setting = $this->newGlobalSetting('setting_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);
        $store->addSetting($setting, $group2, $category2);

        $this->assertSame($setting, $store->getSetting('setting_key'));
    }

    /** @test */
    public function a_group_can_be_registered_and_retrieved()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newGlobalSetting('setting_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);

        $this->assertSame($group, $store->getGroup('group_key'));
    }

    /** @test */
    public function a_group_can_be_registered_twice_and_retrieved()
    {
        $category = $this->newSettingCategory('cat_key');
        $category2 = $this->newSettingCategory('cat2_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newGlobalSetting('setting_key');
        $setting2 = $this->newGlobalSetting('setting2_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);
        $store->addSetting($setting2, $group, $category2);

        $this->assertSame($group, $store->getGroup('group_key'));
    }

    /** @test */
    public function get_group_throws_an_exception_if_the_group_is_not_registered()
    {
        $this->expectException(\Exception::class);
        $this->expectDeprecationMessage('Setting group [group_key] not registered');

        $store = new SettingStore();

        $store->getGroup('group_key');
    }

    /** @test */
    public function a_category_can_be_registered_and_retrieved()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newGlobalSetting('setting_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);

        $this->assertSame($category, $store->getCategory('cat_key'));
    }

    /** @test */
    public function a_category_can_be_registered_twice_and_retrieved()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $group2 = $this->newSettingGroup('group2_key');
        $setting = $this->newGlobalSetting('setting_key');
        $setting2 = $this->newGlobalSetting('setting2_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);
        $store->addSetting($setting2, $group2, $category);

        $this->assertSame($category, $store->getCategory('cat_key'));
    }

    /** @test */
    public function get_category_throws_an_exception_if_the_category_is_not_registered()
    {
        $this->expectException(\Exception::class);
        $this->expectDeprecationMessage('Setting category [category_key] not registered');

        $store = new SettingStore();

        $store->getCategory('category_key');
    }

    /** @test */
    public function get_global_settings_in_group_gets_all_global_setting_in_the_given_group_and_category()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');
        $setting2 = $this->newGlobalSetting('setting2_key');
        $setting3 = $this->newUserSetting('setting3_key');
        $setting4 = $this->newGlobalSetting('setting4_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);
        $store->addSetting($setting2, $group, $category);
        $store->addSetting($setting3, $group, $category);
        $store->addSetting($setting4, $group, $category);

        $settings = $store->getGlobalSettingsInGroup($category, $group);
        $this->assertCount(2, $settings);
        $this->assertEquals([
            $setting2, $setting4
        ], $settings);
    }

    /** @test */
    public function get_user_settings_in_group_gets_all_user_setting_in_the_given_group_and_category()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');
        $setting2 = $this->newGlobalSetting('setting2_key');
        $setting3 = $this->newUserSetting('setting3_key');
        $setting4 = $this->newGlobalSetting('setting4_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);
        $store->addSetting($setting2, $group, $category);
        $store->addSetting($setting3, $group, $category);
        $store->addSetting($setting4, $group, $category);

        $settings = $store->getUserSettingsInGroup($category, $group);
        $this->assertCount(2, $settings);
        $this->assertEquals([
            $setting, $setting3
        ], $settings);
    }

    /** @test */
    public function get_all_settings_in_group_gets_all_the_settings_in_the_given_group_and_category()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');
        $setting2 = $this->newGlobalSetting('setting2_key');
        $setting3 = $this->newUserSetting('setting3_key');
        $setting4 = $this->newGlobalSetting('setting4_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);
        $store->addSetting($setting2, $group, $category);
        $store->addSetting($setting3, $group, $category);
        $store->addSetting($setting4, $group, $category);

        $settings = $store->getAllSettingsInGroup($category, $group);
        $this->assertCount(4, $settings);
        $this->assertEquals([
            $setting, $setting2, $setting3, $setting4
        ], $settings);
    }

    /** @test */
    public function get_all_groups_in_category_gets_all_the_groups_in_the_given_category()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $group2 = $this->newSettingGroup('group2_key');
        $group3 = $this->newSettingGroup('group3_key');
        $setting = $this->newUserSetting('setting_1');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);
        $store->addSetting($setting, $group2, $category);
        $store->addSetting($setting, $group3, $category);

        $groups = $store->getAllGroupsInCategory($category);
        $this->assertCount(3, $groups);
        $this->assertEquals([
            $group, $group2, $group3
        ], $groups);
    }

    /** @test */
    public function get_categories_gets_all_categories()
    {
        $category = $this->newSettingCategory('cat_key');
        $category2 = $this->newSettingCategory('cat2_key');
        $category3 = $this->newSettingCategory('cat3_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_1');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);
        $store->addSetting($setting, $group, $category2);
        $store->addSetting($setting, $group, $category3);

        $categories = $store->getCategories();
        $this->assertCount(3, $categories);
        $this->assertEquals([
            $category, $category2, $category3
        ], $categories);
    }

    /** @test */
    public function get_all_settings_in_group_returns_an_empty_array_if_category_not_registered()
    {
        $category = $this->newSettingCategory('cat_key');
        $category2 = $this->newSettingCategory('cat2_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);

        $this->assertEquals([], $store->getAllSettingsInGroup($category2, $group));
    }

    /** @test */
    public function get_all_settings_in_group_returns_an_empty_array_if_group_not_registered()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $group2 = $this->newSettingGroup('group2_key');
        $setting = $this->newUserSetting('setting_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);

        $this->assertEquals([], $store->getAllSettingsInGroup($category, $group2));
    }

    /** @test */
    public function get_all_groups_in_category_returns_an_empty_array_if_category_not_registered()
    {
        $category = $this->newSettingCategory('cat_key');
        $category2 = $this->newSettingCategory('cat2_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');

        $store = new SettingStore();
        $store->addSetting($setting, $group, $category);

        $this->assertEquals([], $store->getAllGroupsInCategory($category2));
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
     * The key of the category.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * The name for the category.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * A description for the category.
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
     * The key of the group.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * The name for the group.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * A description for the group.
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
     * The key for the setting.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * The default value of the setting.
     *
     * @return mixed
     */
    public function defaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * The field schema to show the user when editing the value.
     *
     * @return Field
     */
    public function fieldOptions(): Field
    {
        return $this->fieldOptions;
    }

    /**
     * A validator to validate any new values.
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
     * The key for the setting.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * The default value of the setting.
     *
     * @return mixed
     */
    public function defaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * The field schema to show the global when editing the value.
     *
     * @return Field
     */
    public function fieldOptions(): Field
    {
        return $this->fieldOptions;
    }

    /**
     * A validator to validate any new values.
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
