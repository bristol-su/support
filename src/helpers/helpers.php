<?php

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\View;

if(!function_exists('settings')) {
    /**
     * @param null $key
     * @param null $default
     * @return |null |null |null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
    function alias() {
        $moduleInstance = app()->make(ModuleInstance::class);
        if($moduleInstance->exists) {
            return $moduleInstance->alias;
        }
        throw new Exception('Alias cannot be returned outside a module environment');
    }
}