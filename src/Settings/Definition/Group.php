<?php

namespace BristolSU\Support\Settings\Definition;

abstract class Group
{

    /**
     * The key of the group
     *
     * @return string
     */
    abstract public function key(): string;

    /**
     * The name for the group
     *
     * @return string
     */
    abstract public function name(): string;

    /**
     * A description for the group
     *
     * @return string
     */
    abstract public function description(): string;

    /**
     * The icon for the group (optional)
     *
     * @return string|null
     */
    public function icon(): ?string
    {
        return null;
    }

    /**
     * Get all settings registered in this group
     *
     * @param Category $category The category to search in
     * @return Setting[]
     */
    public function settings(Category $category): array
    {
        return app(SettingStore::class)->getAllSettingsInGroup($category, $this);
    }

    /**
     * Get all user settings registered in this group
     *
     * @param Category $category The category to search in
     * @return UserSetting[]
     */
    public function userSettings(Category $category): array
    {
        return app(SettingStore::class)->getUserSettingsInGroup($category, $this);
    }

    /**
     * Get all global settings registered in this group
     *
     * @param Category $category The category to search in
     * @return GlobalSetting[]
     */
    public function globalSettings(Category $category): array
    {
        return app(SettingStore::class)->getGlobalSettingsInGroup($category, $this);

    }

    /**
     * Does this group have any user settings?
     *
     * @param Category $category
     * @return bool
     */
    public function hasUserSettings(Category $category): bool
    {
        return count($this->userSettings($category)) > 0;
    }

    /**
     * Does this group have any global settings?
     *
     * @return bool
     */
    public function hasGlobalSettings(Category $category): bool
    {
        return count($this->globalSettings($category)) > 0;
    }
}
