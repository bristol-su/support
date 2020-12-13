<?php

namespace BristolSU\Support\Settings\Definition;

class SettingRegistrar
{

    /**
     * @var SettingStore
     */
    private SettingStore $settingStore;

    public function __construct(SettingStore $settingStore)
    {
        $this->settingStore = $settingStore;
    }

    private ?Category $useCategory;
    private ?Group $useGroup;

    public function registerGlobalSetting(GlobalSetting $setting, Group $group = null, Category $category = null)
    {
        if($category === null && $this->useCategory !== null) {
            $category = $this->useCategory;
        }
        if($group === null && $this->useGroup !== null) {
            $group = $this->useGroup;
        }
        $this->settingStore->addGlobalSetting($setting, $group, $category);
    }

    public function registerUserSetting(UserSetting $setting, Group $group = null, Category $category = null)
    {
        if($category === null && $this->useCategory !== null) {
            $category = $this->useCategory;
        }
        if($group === null && $this->useGroup !== null) {
            $group = $this->useGroup;
        }
        $this->settingStore->addUserSetting($setting, $group, $category);
    }

    public function category(Category $category, \Closure $callback)
    {
        $this->useCategory = $category;
        $callback($this);
        $this->useCategory = null;
    }

    public function group(Group $group, \Closure $callback)
    {
        $this->useGroup = $group;
        $callback($this);
        $this->useGroup = null;
    }


}
