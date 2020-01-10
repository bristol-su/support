<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Settings;

use FormSchema\Schema\Form;

/**
 * Store setting schemas for a module
 */
interface ModuleSettingsStore
{

    /**
     * Register a new required settings schema
     * 
     * @param string $alias Alias of the module
     * @param Form $settings Settings schema
     * @return void
     */
    public function register(string $alias, Form $settings);

    /**
     * Get the settings schema for a given alias
     * 
     * @param string $alias Module alias
     * @return Form Settings Schema
     * 
     * @throws \Exception If no schema registered
     */
    public function get(string $alias): Form;
    
}