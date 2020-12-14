<?php

namespace BristolSU\Support\Settings;

use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface SettingRepository
{

    /**
     * Get the value of a user setting for the given/authenticated user
     *
     * @param string $key The key of the setting
     * @param int|null $userId The ID of the user, or null to use the authenticated user
     * @return mixed The value of the setting
     */
    public function getUserValue(string $key, int $userId = null);

    /**
     * Get the value of a global setting
     *
     * @param string $key The setting key
     * @return mixed The value of the setting
     */
    public function getGlobalValue(string $key);

    /**
     * Set a setting for a user
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     * @param int $userId The ID of the user to save the setting against
     */
    public function setForUser(string $key, $value, int $userId);

    /**
     * Set a setting for all user (this will be overridden by a user changing it, so acts as the default)
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     */
    public function setForAllUsers(string $key, $value);

    /**
     * Set a global setting
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     */
    public function setGlobal(string $key, $value);

}
