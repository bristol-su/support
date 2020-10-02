<?php

namespace BristolSU\Support\Settings\Definition;

class DefinitionStore
{

    private array $settings = [];

    public function register(string $setting, string $category, string $group)
    {
        $this->checkStringInstanceOf(Definition::class, $setting);
        $this->checkStringInstanceOf(Category::class, $category);
        $this->checkStringInstanceOf(Group::class, $group);

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

    private function checkStringInstanceOf(string $expected, string $actual)
    {
        if(!class_exists($actual) || !(is_subclass_of($actual, $expected))) {
            throw new \Exception(
              sprintf('Setting definitions must extend %s, type [%s] given', $expected, $actual)
            );
        }
    }

}
