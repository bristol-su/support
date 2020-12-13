<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Settings\Saved\SavedSettingRepository;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;

abstract class GlobalSetting
{

    abstract public function key(): string;

    abstract public function defaultValue();

    abstract public function fieldOptions(): Field;

    abstract public function validator($value): Validator;

    /**
     * Get the value of the setting
     *
     * @return mixed
     */
    public function getValue()
    {
        $store = app(SavedSettingRepository::class);
        if($store->has(static::key())) {
            return $store->get(static::key());
        }
        return static::defaultValue();
    }

}
