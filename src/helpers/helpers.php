<?php

if(!function_exists('settings')) {
    function settings($key = null, $default = null)
    {
        $settings = app()->make(\BristolSU\Support\ModuleInstance\Contracts\ModuleInstance::class);
        return $settings;
    }
}