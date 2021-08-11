<?php

namespace BristolSU\Support\Settings\Definition;

class SettingStore
{
    /**
     * A map of the settings related to the categories and groups
     * [
     *  'category-key' => [
     *     'group-key' => ['settingkey1', 'settingkey2']
     *  ]
     * ].
     *
     * @var array
     */
    private array $map = [];

    /**
     * An array of categories with the category key as the index and the category as the value.
     *
     * @var Category[]
     */
    private array $categories = [];

    /**
     * An array of groups with the group key as the index and the group object as the value.
     *
     * @var Group[]
     */
    private array $groups = [];

    /**
     * An array of settings with the key as the index.
     *
     * @var Setting[]
     */
    private array $settings = [];

    /**
     * Add a setting to the store.
     *
     * @param Setting $setting The setting object
     * @param Group $group The group the setting exists under
     * @param Category $category The category the setting should sit under
     */
    public function addSetting(Setting $setting, Group $group, Category $category)
    {
        if (!array_key_exists($category->key(), $this->map)) {
            $this->categories[$category->key()] = $category;
            $this->map[$category->key()] = [];
        }

        if (!array_key_exists($group->key(), $this->map[$category->key()])) {
            $this->groups[$group->key()] = $group;
            $this->map[$category->key()][$group->key()] = [];
        }

        if (!array_key_exists($setting->key(), $this->map[$category->key()][$group->key()])) {
            $this->settings[$setting->key()] = $setting;
            $this->map[$category->key()][$group->key()][] = $setting->key();
        }
    }

    /**
     * Get a setting by the setting key.
     *
     * @param string $key The setting key
     * @throws \Exception If the setting wasn't foung
     * @return Setting
     */
    public function getSetting(string $key): Setting
    {
        if (array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        }

        throw new \Exception(
            sprintf('Setting definition with key [%s] could not be found', $key)
        );
    }

    /**
     * Get a category.
     *
     * @param string $key The key of the category
     * @throws \Exception If the category wasn't foung
     * @return Category The category requested
     */
    public function getCategory(string $key): Category
    {
        if (array_key_exists($key, $this->categories)) {
            return $this->categories[$key];
        }

        throw new \Exception(sprintf('Setting category [%s] not registered', $key));
    }

    /**
     * Get a group.
     *
     * @param string $key The key of the group
     * @throws \Exception If the group wasn't foung
     * @return Group The group requested
     *
     */
    public function getGroup(string $key): Group
    {
        if (array_key_exists($key, $this->groups)) {
            return $this->groups[$key];
        }

        throw new \Exception(sprintf('Setting group [%s] not registered', $key));
    }

    /**
     * Get all global settings in the given group and category.
     *
     * @param Category $category
     * @param Group $group
     * @throws \Exception If a setting key wasn't found
     * @return GlobalSetting[] The global settings in the category and group
     */
    public function getGlobalSettingsInGroup(Category $category, Group $group): array
    {
        return array_values(array_filter(
            $this->getAllSettingsInGroup($category, $group),
            fn (Setting $setting): bool => $setting instanceof GlobalSetting
        ));
    }

    /**
     * Get all user settings in the given group.
     *
     * @param Category $category
     * @param Group $group
     * @throws \Exception If a setting key wasn't found
     * @return UserSetting[]
     */
    public function getUserSettingsInGroup(Category $category, Group $group): array
    {
        return array_values(array_filter(
            $this->getAllSettingsInGroup($category, $group),
            fn (Setting $setting): bool => $setting instanceof UserSetting
        ));
    }

    /**
     * Get all settings for the given group.
     *
     * @param Category $category
     * @param Group $group
     * @throws \Exception If a setting key wasn't found
     * @return Setting[]
     */
    public function getAllSettingsInGroup(Category $category, Group $group): array
    {
        if (
            array_key_exists($category->key(), $this->map) &&
            array_key_exists($group->key(), $this->map[$category->key()])) {
            return array_map(fn ($settingKey): Setting => $this->getSetting($settingKey), $this->map[$category->key()][$group->key()]);
        }

        return [];
    }

    /**
     * Get all the groups in the category.
     *
     * @param Category $category
     * @throws \Exception If a group key could not be found
     * @return Group[]
     */
    public function getAllGroupsInCategory(Category $category): array
    {
        if (array_key_exists($category->key(), $this->map)) {
            return array_map(fn ($groupKey): Group => $this->getGroup($groupKey), array_keys($this->map[$category->key()]));
        }

        return [];
    }

    /**
     * Get all categories.
     *
     * @return Category[]
     */
    public function getCategories(): array
    {
        return array_values($this->categories);
    }
}
