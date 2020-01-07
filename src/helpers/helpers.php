<?php

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

if(!function_exists('settings')) {
    /**
     * Get the value of a setting for a module
     *
     * @param string $key Key of the setting to retrieve
     * @param mixed $default Default value to return if no setting found. Defaults to null
     * @return mixed
     * @throws BindingResolutionException If the module instance cannot be found
     */
    function settings($key = null, $default = null)
    {
        if($key === null) {
            $settings = [];
            app()->make(ModuleInstance::class)->moduleInstanceSettings->each(function($setting) use (&$settings) {
                $settings[$setting->key] = $setting->value;
            });
            return $settings;
        } else {
            try {
                return app()->make(ModuleInstance::class)->moduleInstanceSettings()->where('key', $key)->firstOrFail()->value;
            } catch (ModelNotFoundException $e) {
                return $default;
            }
        }
    }
}

if(!function_exists('alias')) {
    /**
     * Get the alias of the currently injected module instance
     * 
     * @return string Alias of the module instance
     * @throws BindingResolutionException If the module instance cannot be found
     */
    function alias() {
        $moduleInstance = app()->make(ModuleInstance::class);
        if($moduleInstance->exists) {
            return $moduleInstance->alias;
        }
        throw new Exception('Alias cannot be returned outside a module environment');
    }
}