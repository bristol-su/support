<?php

namespace BristolSU\Support\Settings\Saved;

use Illuminate\Database\Eloquent\ModelNotFoundException;

interface SavedSettingRepository
{
    /**
     * Check if a global setting has been saved with the given key.
     *
     * @param string $key
     * @return bool
     */
    public function hasGlobal(string $key): bool;

    /**
     * Get the specified setting value.
     *
     * @param string $key Key of the setting
     * @throws ModelNotFoundException
     * @return mixed Value of the setting, or the default value
     */
    public function getGlobalValue(string $key);

    /**
     * Check if a user setting has been saved with the given key.
     *
     * @param string $key
     * @return bool
     */
    public function hasUser(string $key, int $userId): bool;

    /**
     * Get the specified setting value.
     *
     * @param string $key Key of the setting
     * @throws ModelNotFoundException
     * @return mixed Value of the setting, or the default value
     */
    public function getUserValue(string $key, int $userId);

    /**
     * Set a setting value for a user.
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

    /**
     * See if a user setting has a value for all users.
     *
     * These are for user settings that don't belong to any particular user, and can be thought of as the default value
     * for that setting.
     *
     * @param string $key
     * @return bool
     */
    public function hasUserDefault(string $key): bool;

    /**
     * Get the value of a setting set for all users.
     *
     * These are for user settings that don't belong to any particular user, and can be thought of as the default value
     * for that setting.
     *
     * @param string $key
     * @return mixed
     */
    public function getUserDefault(string $key);

    /**
     * Get the value of a setting set for all users.
     *
     * These are for user settings that don't belong to any particular user, and can be thought of as the default value
     * for that setting.
     *
     * @return array Index as the setting key, value as the value
     */
    public function getAllUserDefaults(): array;
}
