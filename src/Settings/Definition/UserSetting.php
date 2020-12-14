<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Settings\SettingRepository;

abstract class UserSetting implements Setting
{

    /**
     * Get the value for the given user, or the current user if no user given
     *
     * @param int|null $userId The ID of the user to check, or null to use the current user
     * @return mixed
     */
    public function getValue(int $userId = null)
    {
        return app(SettingRepository::class)
            ->getUserValue($this->key(), $userId);
    }

    public static function getKey(): string
    {
        $instance = resolve(static::class);
        return $instance->key();
    }

}
