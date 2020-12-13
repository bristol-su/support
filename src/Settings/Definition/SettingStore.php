<?php

namespace BristolSU\Support\Settings\Definition;

class SettingStore
{

    const GLOBAL_SETTING_KEY = 'global';

    const USER_SETTING_KEY = 'user';

    private array $map = [];

    /**
     * An array of categories with the category key as the index and the category as the value
     *
     * @var Category[]
     */
    private array $categories = [];

    /**
     * An array of groups with the group key as the index and the group object as the value
     *
     * @var Group[]
     */
    private array $groups = [];

    /**
     * An array of global and user settings
     *
     * @var array|array[]
     */
    private array $settings = [
        self::GLOBAL_SETTING_KEY => [],
        self::USER_SETTING_KEY => []
    ];

    private function addSetting(string $type, GlobalSetting $setting, Group $group, Category $category)
    {
        if(!array_key_exists($category->key(), $this->map)) {
            $this->categories[$category->key()] = $category;
            $this->map[$category->key()] = [];
        }

        if(!array_key_exists($group->key(), $this->map[$category->key()])) {
            $this->groups[$group->key()] = $group;
            $this->map[$category->key()][$group->key()] = [];
        }

        if(!array_key_exists($setting->key(), $this->map[$category->key()][$group->key()])) {
            $this->settings[$type][$setting->key()] = $setting;
            $this->map[$category->key()][$group->key()][] = $setting->key();
        }
    }

    public function addGlobalSetting(GlobalSetting $setting, Group $group, Category $category)
    {
        $this->addSetting(self::GLOBAL_SETTING_KEY, $setting, $group, $category);
    }

    public function addUserSetting(GlobalSetting $setting, Group $group, Category $category)
    {
        $this->addSetting(self::USER_SETTING_KEY, $setting, $group, $category);
    }

    /*
 * getByKey
 * getAllForCategory
 * getAllForGroup
 * getAll
 * getAllUser
     * getAllGlobal
 */

    /**
     * Get a setting by the setting key
     *
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function getByKey(string $key): string
    {
        if(array_key_exists($key, $this->settings[self::GLOBAL_SETTING_KEY])) {
            return $this->settings[self::GLOBAL_SETTING_KEY][$key];
        }

        if(array_key_exists($key, $this->settings[self::USER_SETTING_KEY])) {
            return $this->settings[self::USER_SETTING_KEY][$key];
        }

        throw new \Exception(
          sprintf('Setting definition with key [%s] could not be found', $key)
        );
    }

    /**
     * Get a category
     *
     * @param string $key The key of the category
     * @return Category The category requested
     */
    public function getCategory(string $key): Category
    {

    }

    /**
     * Get a group
     *
     * @param string $key The key of the group
     * @return Group The group requested
     */
    public function getGroup(string $key): Group
    {

    }

    /**
     * Get all global settings in the given group
     *
     * @param Group $group
     * @return GlobalSetting[]
     */
    public function getGlobalSettingsInGroup(Group $group): array
    {

    }

    /**
     * Get all user settings in the given group
     *
     * @param Group $group
     * @return UserSetting[]
     */
    public function getUserSettingsInGroup(Group $group): array
    {

    }

    /**
     * Get all the groups in the category
     *
     * @param Category $category
     * @return Group[]
     */
    public function getAllGroupsInCategory(Category $category)
    {

    }

    /**
     * Get all categories
     *
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->settings;
    }

}
