<?php

namespace BristolSU\Support\Settings\Definition;

class SettingRegistrar
{

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

    public function getByKey(string $key): string
    {
        foreach($this->settings as $groups) {
            foreach($groups as $group) {
                foreach($group as $setting) {
                    if($setting::key() === $key) {
                        return $setting;
                    }
                }
            }
        }
        throw new \Exception(
          sprintf('Setting definition with key [%s] could not be found', $key)
        );
    }

    public function all(): array
    {
        return $this->settings;
    }

    public function allGlobal()
    {

    }

    public function allUser()
    {
        return array_filter($this->all(), fn($setting)
    }

    private function checkStringInstanceOf(string $expected, string $actual)
    {
        if(!class_exists($actual) || !(is_subclass_of($actual, $expected))) {
            throw new \Exception(
              sprintf('Setting definitions must extend %s, type [%s] given', $expected, $actual)
            );
        }
    }

}
