<?php

namespace BristolSU\Support\Settings\Definition;

class SettingRegistrar
{

    /*
     * registerGlobalSetting
     * registerUserSetting
     * category(
     * group(
     */

    private array $settings = [];

    private ?Category $useCategory;
    private ?Group $useGroup;

    public function registerGlobalSetting(GlobalSetting $setting, Group $group = null, Category $category = null)
    {
        if($category === null)
        if(!array_key_exists($category, $this->settings)) {
            $this->settings[$category] = [];
        }
        if(!array_key_exists($group, $this->settings[$category])) {
            $this->settings[$category][$group] = [];
        }
        if(!in_array($setting, $this->settings[$category][$group])) {
            $this->settings[$category][$group][] = $setting;
        }
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
