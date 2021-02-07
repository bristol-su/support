<?php


namespace BristolSU\Support\Tests\Settings\Definition;

use BristolSU\Support\Settings\Definition\Category;
use BristolSU\Support\Settings\Definition\GlobalSetting;
use BristolSU\Support\Settings\Definition\Group;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Definition\UserSetting;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;

class GroupTest extends TestCase
{
    public function newSettingCategory(string $key, $name = 'CategoryName', $description = 'CategoryDescription')
    {
        return new GroupTestDummyCategory($key, $name, $description);
    }

    public function newSettingGroup(string $key, $name = 'GroupName', $description = 'GroupDescription')
    {
        return new GroupTestDummyGroup($key, $name, $description);
    }

    public function newGlobalSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if ($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if ($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }

        return new GroupTestDummyGlobalSetting($key, $defaultValue, $field, $validator);
    }

    public function newUserSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if ($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if ($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }

        return new GroupTestDummyUserSetting($key, $defaultValue, $field, $validator);
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function icon_returns_null()
    {
        $this->assertNull($this->newSettingGroup('gk')->icon());
    }

    /** @test */
    public function settings_gets_all_settings_in_this_group()
    {
        $category = $this->newSettingCategory('ck');
        $group = $this->newSettingGroup('gk');
        $setting1 = $this->newUserSetting('s1');
        $setting2 = $this->newUserSetting('s2');
        $setting3 = $this->newGlobalSetting('s3');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getAllSettingsInGroup($category, $group)
            ->shouldBeCalled()
            ->willReturn([$setting1, $setting2, $setting3]);
        $this->instance(SettingStore::class, $settingStore->reveal());

        $this->assertEquals([
            $setting1, $setting2, $setting3
        ], $group->settings($category));
    }

    /** @test */
    public function user_settings_gets_all_user_settings_for_the_group()
    {
        $category = $this->newSettingCategory('ck');
        $group = $this->newSettingGroup('gk');
        $setting1 = $this->newUserSetting('s1');
        $setting2 = $this->newUserSetting('s2');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getUserSettingsInGroup($category, $group)
            ->shouldBeCalled()
            ->willReturn([$setting1, $setting2]);
        $this->instance(SettingStore::class, $settingStore->reveal());

        $this->assertEquals([
            $setting1, $setting2
        ], $group->userSettings($category));
    }

    /** @test */
    public function global_settings_gets_all_global_settings_for_the_group()
    {
        $category = $this->newSettingCategory('ck');
        $group = $this->newSettingGroup('gk');
        $setting3 = $this->newGlobalSetting('s3');
        $setting4 = $this->newGlobalSetting('s4');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getGlobalSettingsInGroup($category, $group)
            ->shouldBeCalled()
            ->willReturn([$setting3, $setting4]);
        $this->instance(SettingStore::class, $settingStore->reveal());

        $this->assertEquals([
            $setting3, $setting4
        ], $group->globalSettings($category));
    }

    /** @test */
    public function has_user_settings_returns_true_if_user_settings_were_found()
    {
        $category = $this->newSettingCategory('ck');
        $group = $this->newSettingGroup('gk');
        $setting1 = $this->newUserSetting('s1');
        $setting2 = $this->newUserSetting('s2');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getUserSettingsInGroup($category, $group)
            ->shouldBeCalled()
            ->willReturn([$setting1, $setting2]);
        $this->instance(SettingStore::class, $settingStore->reveal());

        $this->assertTrue($group->hasUserSettings($category));
    }

    /** @test */
    public function has_user_settings_returns_false_if_user_settings_were_not_found()
    {
        $category = $this->newSettingCategory('ck');
        $group = $this->newSettingGroup('gk');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getUserSettingsInGroup($category, $group)
            ->shouldBeCalled()
            ->willReturn([]);
        $this->instance(SettingStore::class, $settingStore->reveal());

        $this->assertFalse($group->hasUserSettings($category));
    }

    /** @test */
    public function has_global_settings_returns_true_if_user_settings_were_found()
    {
        $category = $this->newSettingCategory('ck');
        $group = $this->newSettingGroup('gk');
        $setting2 = $this->newGlobalSetting('s2');
        $setting3 = $this->newGlobalSetting('s3');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getGlobalSettingsInGroup($category, $group)
            ->shouldBeCalled()
            ->willReturn([$setting2, $setting3]);
        $this->instance(SettingStore::class, $settingStore->reveal());

        $this->assertTrue($group->hasGlobalSettings($category));
    }

    /** @test */
    public function has_global_settings_returns_false_if_user_settings_were_not_found()
    {
        $category = $this->newSettingCategory('ck');
        $group = $this->newSettingGroup('gk');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getGlobalSettingsInGroup($category, $group)
            ->shouldBeCalled()
            ->willReturn([]);
        $this->instance(SettingStore::class, $settingStore->reveal());

        $this->assertFalse($group->hasGlobalSettings($category));
    }
}

class GroupTestDummyCategory extends Category
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

class GroupTestDummyGroup extends Group
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

class GroupTestDummyUserSetting extends UserSetting
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

class GroupTestDummyGlobalSetting extends GlobalSetting
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
