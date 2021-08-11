<?php

namespace BristolSU\Support\Settings;

use BristolSU\Support\Settings\Definition\SettingStore;

/**
 * Acts as a validation trigger when setting settings.
 *
 * When a setting is set, we can validate the new value and throw an exception if an issue occurs
 */
class SettingValidation implements SettingRepository
{
    /**
     * @var SettingRepository
     */
    private SettingRepository $repository;

    /**
     * @var SettingStore
     */
    private SettingStore $settingStore;

    public function __construct(SettingRepository $repository, SettingStore $settingStore)
    {
        $this->repository = $repository;
        $this->settingStore = $settingStore;
    }

    /**
     * Get the value of a user setting for the given/authenticated user.
     *
     * @param string $key The key of the setting
     * @param int|null $userId The ID of the user, or null to use the authenticated user
     * @return mixed The value of the setting
     */
    public function getUserValue(string $key, int $userId = null)
    {
        return $this->repository->getUserValue($key, $userId);
    }

    /**
     * Get the value of a global setting.
     *
     * @param string $key The setting key
     * @return mixed The value of the setting
     */
    public function getGlobalValue(string $key)
    {
        return $this->repository->getGlobalValue($key);
    }

    /**
     * Set a setting for a user.
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     * @param int $userId The ID of the user to save the setting against
     */
    public function setForUser(string $key, $value, int $userId)
    {
        $this->settingStore
            ->getSetting($key)
            ->validator($value)
            ->validate();

        $this->repository->setForUser($key, $value, $userId);
    }

    /**
     * Set a setting for all user (this will be overridden by a user changing it, so acts as the default).
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     */
    public function setForAllUsers(string $key, $value)
    {
        $this->settingStore
            ->getSetting($key)
            ->validator($value)
            ->validate();

        $this->repository->setForAllUsers($key, $value);
    }

    /**
     * Set a global setting.
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     */
    public function setGlobal(string $key, $value)
    {
        $this->settingStore
            ->getSetting($key)
            ->validator($value)
            ->validate();

        $this->repository->setGlobal($key, $value);
    }
}
