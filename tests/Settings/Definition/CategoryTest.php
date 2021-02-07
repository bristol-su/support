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

class CategoryTest extends TestCase
{
    public function newSettingCategory(string $key, $name = 'CategoryName', $description = 'CategoryDescription')
    {
        return new CategoryTestDummyCategory($key, $name, $description);
    }

    public function newSettingGroup(string $key, $name = 'GroupName', $description = 'GroupDescription')
    {
        return new CategoryTestDummyGroup($key, $name, $description);
    }

    public function newGlobalSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if ($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if ($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }

        return new CategoryTestDummyGlobalSetting($key, $defaultValue, $field, $validator);
    }

    public function newUserSetting(string $key, $defaultValue = 'DefaultValue', Field $field = null, Validator $validator = null)
    {
        if ($field === null) {
            $field = $this->prophesize(Field::class)->reveal();
        }
        if ($validator === null) {
            $validator = $this->prophesize(Validator::class)->reveal();
        }

        return new CategoryTestDummyUserSetting($key, $defaultValue, $field, $validator);
    }

    /** @test */
    public function icon_returns_null()
    {
        $category = $this->newSettingCategory('ck');
        $this->assertNull($category->icon());
    }

    /** @test */
    public function groups_returns_the_groups_in_the_category()
    {
        $category = $this->newSettingCategory('ck');
        $group1 = $this->newSettingGroup('g1');
        $group2 = $this->newSettingGroup('g2');
        $group3 = $this->newSettingGroup('g3');
        $group4 = $this->newSettingGroup('g4');

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getAllGroupsInCategory($category)->shouldBeCalled()->willReturn([
            $group1, $group2, $group3, $group4
        ]);
        $this->instance(SettingStore::class, $settingStore->reveal());

        $this->assertEquals(
            [$group1, $group2, $group3, $group4],
            $category->groups()
        );
    }

    /** @test */
    public function group_with_user_settings_returns_groups_that_have_user_settings()
    {
        $category = $this->newSettingCategory('ck');
        $group1 = $this->prophesize(Group::class);
        $group1->hasUserSettings($category)->willReturn(false);

        $group2 = $this->prophesize(Group::class);
        $group2->hasUserSettings($category)->willReturn(true);

        $group3 = $this->prophesize(Group::class);
        $group3->hasUserSettings($category)->willReturn(true);

        $group4 = $this->prophesize(Group::class);
        $group4->hasUserSettings($category)->willReturn(false);

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getAllGroupsInCategory($category)->shouldBeCalled()->willReturn([
            $group1->reveal(), $group2->reveal(), $group3->reveal(), $group4->reveal()
        ]);
        $this->instance(SettingStore::class, $settingStore->reveal());
        $this->assertEquals(
            [$group2->reveal(), $group3->reveal()],
            $category->groupsWithUserSettings()
        );
    }

    /** @test */
    public function groups_with_global_settings_returns_groups_that_have_global_settings()
    {
        $category = $this->newSettingCategory('ck');
        $group1 = $this->prophesize(Group::class);
        $group1->hasGlobalSettings($category)->willReturn(false);

        $group2 = $this->prophesize(Group::class);
        $group2->hasGlobalSettings($category)->willReturn(true);

        $group3 = $this->prophesize(Group::class);
        $group3->hasGlobalSettings($category)->willReturn(true);

        $group4 = $this->prophesize(Group::class);
        $group4->hasGlobalSettings($category)->willReturn(false);

        $settingStore = $this->prophesize(SettingStore::class);
        $settingStore->getAllGroupsInCategory($category)->shouldBeCalled()->willReturn([
            $group1->reveal(), $group2->reveal(), $group3->reveal(), $group4->reveal()
        ]);
        $this->instance(SettingStore::class, $settingStore->reveal());
        $this->assertEquals(
            [$group2->reveal(), $group3->reveal()],
            $category->groupsWithGlobalSettings()
        );
    }
}


class CategoryTestDummyCategory extends Category
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

class CategoryTestDummyGroup extends Group
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

class CategoryTestDummyUserSetting extends UserSetting
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

class CategoryTestDummyGlobalSetting extends GlobalSetting
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
