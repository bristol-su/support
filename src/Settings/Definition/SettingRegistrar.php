<?php

namespace BristolSU\Support\Settings\Definition;

/**
 * Settings can be registered by passing a callback to the group, or through calling the right functions (using chaining optional), or just calling register group
 */
class SettingRegistrar
{

    /**
     * The store to register settings in
     *
     * @var SettingStore
     */
    private SettingStore $settingStore;

    /**
     * The category to register all settings in
     *
     * @var Category|null
     */
    private ?Category $useCategory;

    /**
     * The group to register all settings in
     *
     * @var Group|null
     */
    private ?Group $useGroup;

    /**
     * @param SettingStore $settingStore
     */
    public function __construct(SettingStore $settingStore)
    {
        $this->settingStore = $settingStore;
    }

    /**
     * Register a new setting
     * @param Setting $setting The setting to register
     * @param Group|null $group The group to register the setting under. If null, must be used in the group() function
     * @param Category|null $category The category to register the setting under. If null, must be used in the category() function
     * @return SettingRegistrar
     */
    public function registerSetting(Setting $setting, Group $group = null, Category $category = null): self
    {
        if($category === null && $this->useCategory !== null) {
            $category = $this->useCategory;
        }
        if($group === null && $this->useGroup !== null) {
            $group = $this->useGroup;
        }
        $this->settingStore->addSetting($setting, $group, $category);
        return $this;
    }

    /**
     * Any registrations in the callback will be registered with the given category
     *
     * @param Category $category The category to register settings under
     * @param \Closure|null $callback The callback to register settings. Given an instance of this registrar to use.
     * @return SettingRegistrar
     */
    public function category(Category $category, \Closure $callback = null): self
    {
        $this->useCategory = $category;
        if($callback !== null) {
            $callback($this);
            $this->useCategory = null;
        }
        return $this;
    }

    /**
     * Any registrations in the callback will be registered with the given group
     *
     * @param Group $group The group to register settings under
     * @param \Closure|null $callback The callback to register settings. Given an instance of this registrar to use.
     * @return SettingRegistrar
     */
    public function group(Group $group, \Closure $callback = null): self
    {
        $this->useGroup = $group;
        if($callback !== null) {
            $callback($this);
            $this->useGroup = null;
        }
        return $this;
    }


}
