<?php

if(!function_exists('settings')) {
    /**
     * @param null $key
     * @param null $default
     * @return |null |null |null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function settings($key = null, $default = null)
    {
        $settings = app()->make(\BristolSU\Support\ModuleInstance\ModuleInstance::class)->moduleInstanceSettings->settings;
        if($key === null) {
            return $settings;
        } elseif(array_key_exists($key, $settings)) {
            return $settings[$key];
        }
        return $default;
    }
}