<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Settings;

use FormSchema\Schema\Form;

/**
 * Store setting schemas for a module.
 */
interface ModuleSettingsStore
{
    /**
     * Register a new required settings schema.
     *
     * @param string $alias Alias of the module
     * @param Form $settings Settings schema
     */
    public function register(string $alias, Form $settings);

    /**
     * Get the settings schema for a given alias.
     *
     * @param string $alias Module alias
     * @throws \Exception If no schema registered
     * @return Form Settings Schema
     *
     */
    public function get(string $alias): Form;
}
