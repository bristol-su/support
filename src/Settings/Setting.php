<?php

namespace BristolSU\Support\Settings;

use BristolSU\Support\Settings\Definition\DefinitionStore;
use BristolSU\Support\Settings\Saved\SavedSettingRepository;

class Setting implements SettingRepository
{

    /**
     * @var DefinitionStore
     */
    private DefinitionStore $definitionStore;
    /**
     * @var SavedSettingRepository
     */
    private SavedSettingRepository $savedSettingRepository;

    public function __construct(DefinitionStore $definitionStore, SavedSettingRepository $savedSettingRepository)
    {
        $this->definitionStore = $definitionStore;
        $this->savedSettingRepository = $savedSettingRepository;
    }

    public function all(): array
    {
        $overrides = $this->savedSettingRepository->all();
        $definitions = $this->definitionStore->all();
        $settings = [];
        foreach($definitions as $groups) {
            foreach($groups as $group) {
                foreach($group as $setting) {
                    $key = $setting::key();
                    if(array_key_exists($key, $overrides)) {
                        $settings[$key] = $overrides[$key];
                    } else {
                        $settings[$key] = $setting::defaultValue();
                    }
                }
            }
        }
        return $settings;
    }

    public function get(string $key)
    {
        if($this->savedSettingRepository->has($key)) {
            return $this->savedSettingRepository->get($key);
        }
        return $this->definitionStore->getByKey($key)::defaultValue();
    }

    public function set(string $key, $value = null): void
    {
        $this->savedSettingRepository->set($key, $value);
    }

}
