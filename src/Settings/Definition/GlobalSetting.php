<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Settings\SettingRepository;

abstract class GlobalSetting implements Setting
{

    /**
     * Get the value of the setting
     *
     * @return mixed
     */
    public function getValue()
    {
        return app(SettingRepository::class)
            ->getGlobalValue($this->key());
    }

    public static function getKey(): string
    {
        $instance = resolve(static::class);
        return $instance->key();
    }

}
