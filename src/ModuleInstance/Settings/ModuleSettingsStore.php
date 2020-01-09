<?php

namespace BristolSU\Support\ModuleInstance\Settings;

use BristolSU\Support\ModuleInstance\Contracts\Settings\ModuleSettingsStore as ModuleSettingsStoreContract;
use FormSchema\Schema\Form;

/**
 * Store setting schemas for a module
 */
class ModuleSettingsStore implements ModuleSettingsStoreContract
{

    /**
     * Holds the form schemas for registered modules
     * 
     * Held with the module alias as the index, and a FormSchema object as the value
     * 
     * @var array
     */
    private $settings = [];

    /**
     * Register a new required settings schema
     *
     * @param string $alias Alias of the module
     * @param Form $settings Settings schema
     * @return void
     */
    public function register(string $alias, Form $settings)
    {
        $this->settings[$alias] = $settings;
    }

    /**
     * Get the settings schema for a given alias
     *
     * @param string $alias Module alias
     * @return Form Settings Schema
     *
     * @throws \Exception If no schema registered
     */
    public function get(string $alias): Form
    {
        if(array_key_exists($alias, $this->settings)) {
            return $this->settings[$alias];
        }
        throw new \Exception(sprintf('Settings not found for alias %s', $alias));
    }
}