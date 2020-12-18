<?php

namespace BristolSU\Support\Settings\Definition;

abstract class Category
{

    /**
     * The key of the category
     *
     * @return string
     */
    abstract public function key(): string;

    /**
     * The name for the category
     *
     * @return string
     */
    abstract public function name(): string;

    /**
     * A description for the category
     *
     * @return string
     */
    abstract public function description(): string;

    /**
     * The icon for the category (optional)
     *
     * @return string|null
     */
    public function icon(): ?string
    {
        return null;
    }

    /**
     * All setting groups in this category
     *
     * @return Group[]
     */
    public function groups(): array
    {
        return app(SettingStore::class)->getAllGroupsInCategory($this);
    }

    /**
     * All setting groups in this category with user settings
     *
     * @return Group[]
     */
    public function groupsWithUserSettings(): array
    {
        return array_values(array_filter($this->groups(), fn (Group $group): bool => $group->hasUserSettings($this)));
    }

    /**
     * All setting groups in this category with global settings
     *
     * @return Group[]
     */
    public function groupsWithGlobalSettings(): array
    {
        return array_values(array_filter($this->groups(), fn (Group $group): bool => $group->hasGlobalSettings($this)));
    }

}
