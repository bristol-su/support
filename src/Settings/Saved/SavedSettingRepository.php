<?php

namespace BristolSU\Support\Settings\Saved;

use Illuminate\Database\Eloquent\ModelNotFoundException;

interface SavedSettingRepository
{

    /**
     * Check if a global setting has been saved with the given key
     *
     * @param $key
     * @return bool
     */
    public function hasGlobal($key): bool;

    /**
     * Get the specified setting value.
     *
     * @param array|string $key Key of the setting
     * @return mixed Value of the setting, or the default value
     * @throws ModelNotFoundException
     */
    public function getGlobalValue($key);

    /**
     * Check if a user setting has been saved with the given key
     *
     * @param $key
     * @return bool
     */
    public function hasUser($key): bool;

    /**
     * Get the specified setting value.
     *
     * @param array|string $key Key of the setting
     * @return mixed Value of the setting, or the default value
     * @throws ModelNotFoundException
     */
    public function getUserValue($key);

    /**
     * Set a setting value for a user
     *
     * @param string $key The key of the setting to set
     * @param mixed $value The value of the setting
     * @param int $userId The user ID of the user to set the setting for
     */
    public function setForUser(string $key, $value, int $userId);

    /**
     * Set a setting value for all users. This will be used if a user hasn't specified a value themselves.
     *
     * @param string $key The key of the setting to set
     * @param mixed $value The value of the setting
     */
    public function setForAllUsers(string $key, $value);

    /**
     * Set a global setting value.
     *
     * @param string $key The key of the setting to set
     * @param mixed $value The value of the setting
     */
    public function setGlobal(string $key, $value);
}
