<?php

namespace BristolSU\Support\Settings;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Settings\Definition\SettingStore;
use BristolSU\Support\Settings\Saved\SavedSettingRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Setting implements SettingRepository
{
    /**
     * The setting object store.
     *
     * @var SettingStore
     */
    private SettingStore $settingStore;

    /**
     * The repository for accessing saved setting values.
     *
     * @var SavedSettingRepository
     */
    private SavedSettingRepository $savedSettingRepository;

    /**
     * @param SettingStore $settingStore
     * @param SavedSettingRepository $savedSettingRepository
     */
    public function __construct(SettingStore $settingStore, SavedSettingRepository $savedSettingRepository)
    {
        $this->settingStore = $settingStore;
        $this->savedSettingRepository = $savedSettingRepository;
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
        if ($userId === null) {
            $userId = $this->currentUserId();
        }

        try {
            if ($userId !== null) {
                return $this->savedSettingRepository->getUserValue($key, $userId);
            }
        } catch (ModelNotFoundException $e) {
            // Handled by using the default value
        }

        return $this->getDefaultValue($key);
    }

    /**
     * Get the value of a global setting.
     *
     * @param string $key The setting key
     * @return mixed The value of the setting
     */
    public function getGlobalValue(string $key)
    {
        try {
            return $this->savedSettingRepository->getGlobalValue($key);
        } catch (ModelNotFoundException $e) {
            return $this->getDefaultValue($key);
        }
    }

    /**
     * Set a setting for a user.
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     * @param int|null $userId The ID of the user to save the setting against, or null to use the currently logged in user
     */
    public function setForUser(string $key, $value, ?int $userId = null)
    {
        if ($userId === null) {
            $userId = $this->currentUserId();
        }
        $this->savedSettingRepository->setForUser($key, $value, $userId);
    }

    /**
     * Set a setting for all user (this will be overridden by a user changing it, so acts as the default).
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     */
    public function setForAllUsers(string $key, $value)
    {
        $this->savedSettingRepository->setForAllUsers($key, $value);
    }

    /**
     * Set a global setting.
     *
     * @param string $key The key of the setting
     * @param mixed $value The new value of the setting
     */
    public function setGlobal(string $key, $value)
    {
        $this->savedSettingRepository->setGlobal($key, $value);
    }

    /**
     * Get the default value for a setting.
     *
     * @param string $key
     * @throws \Exception
     * @return mixed
     */
    private function getDefaultValue(string $key)
    {
        return $this->settingStore->getSetting($key)->defaultValue();
    }

    /**
     * Get the current user ID.
     * @return int|null The user id, or null if no user logged in
     */
    private function currentUserId()
    {
        if (app(Authentication::class)->hasUser()) {
            return app(Authentication::class)->getUser()->id();
        }

        return null;
    }
}
