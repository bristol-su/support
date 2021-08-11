<?php

namespace BristolSU\Support\Settings\Saved;

use BristolSU\Support\Settings\Saved\ValueManipulator\Manipulator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DatabaseSavedSettingRepository implements SavedSettingRepository
{
    public function __construct(protected Manipulator $manipulator)
    {
    }

    /**
     * Check if a global setting has been saved with the given key.
     *
     * @param $key
     * @return bool
     */
    public function hasGlobal(string $key): bool
    {
        return SavedSettingModel::global()->key($key)->count() > 0;
    }

    /**
     * Get the specified setting value.
     *
     * @param array|string $key Key of the setting
     * @throws ModelNotFoundException
     * @return mixed Value of the setting, or the default value
     */
    public function getGlobalValue(string $key)
    {
        $value = SavedSettingModel::global()->key($key)->firstOrFail()->getSettingValue();

        return $this->manipulator->decode($key, $value);
    }

    /**
     * Check if a user setting has been saved with the given key.
     *
     * @param string $key
     * @param int $userId
     * @return bool
     */
    public function hasUser(string $key, int $userId): bool
    {
        return SavedSettingModel::user($userId)->key($key)->count() > 0
            || SavedSettingModel::user(null)->key($key)->count() > 0;
    }

    /**
     * Get the specified setting value.
     *
     * @param array|string $key Key of the setting
     * @param int $userId
     * @return mixed Value of the setting, or the default value
     */
    public function getUserValue(string $key, int $userId)
    {
        if (SavedSettingModel::user($userId)->key($key)->count() > 0) {
            $value = SavedSettingModel::user($userId)->key($key)->firstOrFail()->getSettingValue();
        } else {
            $value = SavedSettingModel::user()->key($key)->firstOrFail()->getSettingValue();
        }

        return $this->manipulator->decode($key, $value);
    }

    /**
     * Set a setting value for a user.
     *
     * @param string $key The key of the setting to set
     * @param mixed $value The value of the setting
     * @param int $userId The user ID of the user to set the setting for
     */
    public function setForUser(string $key, $value, int $userId)
    {
        $value = $this->manipulator->encode($key, $value);
        SavedSettingModel::updateOrCreate(
            ['key' => $key, 'visibility' => 'user', 'user_id' => $userId],
            ['value' => $value]
        );
    }

    /**
     * Set a setting value for all users. This will be used if a user hasn't specified a value themselves.
     *
     * @param string $key The key of the setting to set
     * @param mixed $value The value of the setting
     */
    public function setForAllUsers(string $key, $value)
    {
        $value = $this->manipulator->encode($key, $value);
        SavedSettingModel::updateOrCreate(
            ['key' => $key, 'visibility' => 'user', 'user_id' => null],
            ['value' => $value]
        );
    }

    /**
     * Set a global setting value.
     *
     * @param string $key The key of the setting to set
     * @param mixed $value The value of the setting
     */
    public function setGlobal(string $key, $value)
    {
        $value = $this->manipulator->encode($key, $value);
        SavedSettingModel::updateOrCreate(
            ['key' => $key, 'visibility' => 'global'],
            ['value' => $value]
        );
    }

    /**
     * See if a user setting has a value for all users.
     *
     * These are for user settings that don't belong to any particular user, and can be thought of as the default value
     * for that setting.
     *
     * @param string $key
     * @return bool
     */
    public function hasUserDefault(string $key): bool
    {
        return SavedSettingModel::user(null)->key($key)->count() > 0;
    }

    /**
     * Get the value of a setting set for all users.
     *
     * These are for user settings that don't belong to any particular user, and can be thought of as the default value
     * for that setting.
     *
     * @param string $key
     * @return mixed
     */
    public function getUserDefault(string $key)
    {
        $value = SavedSettingModel::user(null)->key($key)->firstOrFail()->getSettingValue();

        return $this->manipulator->decode($key, $value);
    }

    /**
     * Get the value of a setting set for all users.
     *
     * These are for user settings that don't belong to any particular user, and can be thought of as the default value
     * for that setting.
     *
     * @return array Index as the setting key, value as the value
     */
    public function getAllUserDefaults(): array
    {
        $defaults = [];
        foreach (SavedSettingModel::user(null)->get() as $savedSettingModel) {
            $defaults[$savedSettingModel->getSettingKey()] = $this->manipulator->decode(
                $savedSettingModel->getSettingKey(),
                $savedSettingModel->getSettingValue()
            );
        }

        return $defaults;
    }
}
