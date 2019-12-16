<?php


namespace BristolSU\Support\ModuleInstance\Settings;


use BristolSU\Support\ModuleInstance\Contracts\Settings\ModuleSettingsStore as ModuleSettingsStoreContract;
use FormSchema\Schema\Form;

class ModuleSettingsStore implements ModuleSettingsStoreContract
{

    private $settings;
    
    public function register(string $alias, Form $settings)
    {
        $this->settings[$alias] = $settings;
    }

    public function get(string $alias): Form
    {
        if(array_key_exists($alias, $this->settings)) {
            return $this->settings[$alias];
        }
        throw new \Exception(sprintf('Settings not found for alias %s', $alias));
    }
}