<?php

namespace BristolSU\Support\Settings\Saved;

interface SavedSettingRepository
{

    /**
     * Check if a setting has been saved with the given key
     *
     * @param $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * Get the specified setting.
     *
     * @param array|string $key Key of the setting
     * @param mixed $default Default value if the setting does not exist
     * @return mixed Value of the setting, or the default value
     */
    public function get($key);

    /**
     * Set a given setting.
     *
     * @param array|string $key Key of the settings
     * @param mixed $value Value to set
     * @return void
     */
    public function set($key, $value = null): void;

}
