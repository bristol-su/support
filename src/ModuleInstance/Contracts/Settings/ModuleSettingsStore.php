<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Settings;


use FormSchema\Schema\Form;

interface ModuleSettingsStore
{

    public function register(string $alias, Form $settings);

    public function get(string $alias): Form;
    
}