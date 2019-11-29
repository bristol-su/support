<?php

use BristolSU\Support\ModuleInstance\ModuleInstance;

if (!function_exists('settings')) {
    /**
     * @param null $key
     * @param null $default
     * @return |null |null |null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function settings($key = null, $default = null)
    {
        $settings = app()->make(ModuleInstance::class)->moduleInstanceSettings->settings;
        if ($key === null) {
            return $settings;
        } elseif (array_key_exists($key, $settings)) {
            return $settings[$key];
        }
        return $default;
    }
}

if (!function_exists('alias')) {
    function alias() {
        $moduleInstance = app()->make(ModuleInstance::class);
        if ($moduleInstance->exists) {
            return $moduleInstance->alias;
        }
        throw new Exception('Alias cannot be returned outside a module environment');
    }
}