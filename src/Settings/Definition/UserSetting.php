<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Settings\Saved\SavedSettingRepository;

abstract class Definition
{

    abstract public static function key(): string;

    abstract public static function defaultValue();

    public static function getValue()
    {
        $store = app(SavedSettingRepository::class);
        if($store->has(static::key())) {
            return $store->get(static::key());
        }
        return static::defaultValue();
    }

}
