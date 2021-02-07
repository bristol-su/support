<?php


namespace BristolSU\Support\Tests\Settings\Definition;

use BristolSU\Support\Settings\Definition\Category;
use BristolSU\Support\Settings\Definition\GlobalSetting;
use BristolSU\Support\Settings\Definition\Group;
use BristolSU\Support\Settings\Definition\SettingRegistrar;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Definition\UserSetting;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;

class SettingRegistrarTest extends TestCase
{
    public \Prophecy\Prophecy\ObjectProphecy $settingStore;

    public function newSettingCategory(string $key, $name = 'CategoryName', $description = 'CategoryDescription')
    {
        return new SettingRegistrarTestDummyCategory($key, $name, $description);
    }

    public function newSettingGroup(string $key, $name = 'GroupName', $description = 'GroupDescription')
    {
        return new SettingRegistrarTestDummyGroup($key, $name, $description);
    }

    public function newGlobalSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if ($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if ($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }

        return new SettingRegistrarTestDummyGlobalSetting($key, $defaultValue, $field, $validator);
    }

    public function newUserSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if ($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if ($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }

        return new SettingRegistrarTestDummyUserSetting($key, $defaultValue, $field, $validator);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->settingStore = $this->prophesize(SettingStore::class);
    }

    /** @test */
    public function register_setting_can_be_called_to_register_a_setting_fully()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');

        $this->settingStore->addSetting($setting, $group, $category)->shouldBeCalled();

        $registrar = new SettingRegistrar($this->settingStore->reveal());
        $registrar->registerSetting($setting, $group, $category);
    }

    /** @test */
    public function register_setting_can_be_chained()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');
        $setting2 = $this->newUserSetting('setting2_key');
        $setting3 = $this->newUserSetting('setting3_key');

        $this->settingStore->addSetting($setting, $group, $category)->shouldBeCalledOnce();
        $this->settingStore->addSetting($setting2, $group, $category)->shouldBeCalledOnce();
        $this->settingStore->addSetting($setting3, $group, $category)->shouldBeCalledOnce();

        $registrar = new SettingRegistrar($this->settingStore->reveal());
        $registrar->registerSetting($setting, $group, $category)
            ->registerSetting($setting2, $group, $category)
            ->registerSetting($setting3, $group, $category);
    }

    /** @test */
    public function category_and_group_can_be_called_to_set_the_category_and_group_for_that_instance()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');
        $setting2 = $this->newUserSetting('setting2_key');
        $setting3 = $this->newUserSetting('setting3_key');

        $this->settingStore->addSetting($setting, $group, $category)->shouldBeCalledOnce();
        $this->settingStore->addSetting($setting2, $group, $category)->shouldBeCalledOnce();
        $this->settingStore->addSetting($setting3, $group, $category)->shouldBeCalledOnce();

        $registrar = new SettingRegistrar($this->settingStore->reveal());
        $registrar->category($category);
        $registrar->group($group);
        $registrar->registerSetting($setting);
        $registrar->registerSetting($setting2);
        $registrar->registerSetting($setting3);
    }

    /** @test */
    public function category_and_group_can_be_chained()
    {
        $category = $this->newSettingCategory('cat_key');
        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');
        $setting2 = $this->newUserSetting('setting2_key');
        $setting3 = $this->newUserSetting('setting3_key');

        $this->settingStore->addSetting($setting, $group, $category)->shouldBeCalledOnce();
        $this->settingStore->addSetting($setting2, $group, $category)->shouldBeCalledOnce();
        $this->settingStore->addSetting($setting3, $group, $category)->shouldBeCalledOnce();

        $registrar = new SettingRegistrar($this->settingStore->reveal());
        $registrar->category($category)
            ->group($group)
            ->registerSetting($setting)
            ->registerSetting($setting2)
            ->registerSetting($setting3);
    }

    /** @test */
    public function category_and_group_accept_a_callback_to_only_change_the_category_or_group_temporarily()
    {
        $category = $this->newSettingCategory('cat_key');
        $category2 = $this->newSettingCategory('cat2_key');
        $group = $this->newSettingGroup('group_key');
        $group2 = $this->newSettingGroup('group2_key');
        $setting = $this->newUserSetting('setting_key');
        $setting2 = $this->newUserSetting('setting2_key');
        $setting3 = $this->newUserSetting('setting3_key');

        $this->settingStore->addSetting($setting, $group, $category)->shouldBeCalledOnce();
        $this->settingStore->addSetting($setting2, $group, $category)->shouldBeCalledOnce();
        $this->settingStore->addSetting($setting3, $group2, $category2)->shouldBeCalledOnce();

        $registrar = new SettingRegistrar($this->settingStore->reveal());
        $registrar->category($category, function ($registrar) use ($group, $setting, $setting2) {
            $registrar->group($group, function ($registrar) use ($setting, $setting2) {
                $registrar->registerSetting($setting);
                $registrar->registerSetting($setting2);
            });
        });

        $registrar->registerSetting($setting3, $group2, $category2);
    }

    /** @test */
    public function register_setting_throws_an_exception_if_no_group_given()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Group must be given to register a setting');

        $category = $this->newSettingCategory('cat_key');
        $setting = $this->newUserSetting('setting_key');

        $registrar = new SettingRegistrar($this->settingStore->reveal());
        $registrar->registerSetting($setting, null, $category);
    }

    /** @test */
    public function register_setting_throws_an_exception_if_no_category_given()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Category must be given to register a setting');

        $group = $this->newSettingGroup('group_key');
        $setting = $this->newUserSetting('setting_key');

        $registrar = new SettingRegistrar($this->settingStore->reveal());
        $registrar->registerSetting($setting, $group, null);
    }
}


class SettingRegistrarTestDummyCategory extends Category
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

class SettingRegistrarTestDummyGroup extends Group
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

class SettingRegistrarTestDummyUserSetting extends UserSetting
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

class SettingRegistrarTestDummyGlobalSetting extends GlobalSetting
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
