<?php

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

if (!function_exists('settings')) {
    /**
     * Get the value of a setting for a module.
     *
     * @param string $key Key of the setting to retrieve
     * @param mixed $default Default value to return if no setting found. Defaults to null
     * @throws BindingResolutionException If the module instance cannot be found
     * @return mixed
     */
    function settings($key = null, $default = null)
    {
        if ($key === null) {
            $settings = [];
            app()->make(ModuleInstance::class)->moduleInstanceSettings->each(function ($setting) use (&$settings) {
                $settings[$setting->key] = $setting->value;
            });

            return $settings;
        }

        try {
            return app()->make(ModuleInstance::class)->moduleInstanceSettings()->where('key', $key)->firstOrFail()->value ?? $default;
        } catch (ModelNotFoundException $e) {
            return $default;
        }
    }
}

if (!function_exists('alias')) {
    /**
     * Get the alias of the currently injected module instance.
     *
     * @throws BindingResolutionException If the module instance cannot be found
     * @throws \Exception
     * @return string Alias of the module instance
     */
    function alias()
    {
        $moduleInstance = app()->make(ModuleInstance::class);
        if ($moduleInstance->exists) {
            return $moduleInstance->alias;
        }

        throw new Exception('Alias cannot be returned outside a module environment');
    }
}

if (!function_exists('hasPermission')) {
    /**
     * Does the user/group/role have the given permission?
     *
     * Just pass the ability to test if the currently authenticated user has the permission
     * Pass a user, user and group or user, group and role to test them instead of the authenticated user/group/role
     *
     * @param string $ability Permission to test
     * @param User|null $userModel User to test. Leave as null to test the authenticated user.
     * @param Group|null $group Group to test. Leave as null to test the authenticated group.
     * @param Role|null $role Role to test. Leave as null to test the authenticated role.
     * @return bool Does the user have the permission?
     */
    function hasPermission(string $ability, ?User $userModel = null, ?Group $group = null, ?Role $role = null): bool
    {
        if ($userModel === null && $group === null && $role === null) {
            return \BristolSU\Support\Permissions\Facade\PermissionTester::evaluate($ability);
        }

        return \BristolSU\Support\Permissions\Facade\PermissionTester::evaluateFor($ability, $userModel, $group, $role);
    }
}



if (!function_exists('globalSetting')) {

    /**
     * Get the value of a global setting.
     *
     * @param string $key The key of the setting
     * @return mixed
     */
    function globalSetting(string $key)
    {
        return \BristolSU\Support\Settings\Facade\Setting::getGlobalValue($key);
    }
}

if (!function_exists('userSetting')) {

    /**
     * Get the value of a user setting.
     *
     * @param string $key The key of the setting
     * @param int|null $userId The user ID, or null to use the current user
     * @return mixed
     */
    function userSetting(string $key, ?int $userId = null)
    {
        return \BristolSU\Support\Settings\Facade\Setting::getUserValue($key, $userId);
    }
}
