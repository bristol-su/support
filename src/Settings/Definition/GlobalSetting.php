<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Settings\SettingRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

abstract class GlobalSetting implements Setting
{

    /**
     * Get the value of the setting
     *
     * @return mixed
     */
    public function value()
    {
        return app(SettingRepository::class)
            ->getGlobalValue($this->key());
    }

    /**
     * Get the value of the setting
     *
     * @return mixed
     */
    public static function getValue()
    {
        $instance = resolve(static::class);
        return $instance->value();
    }

    /**
     * Get the key of the setting
     *
     * @return string The key
     */
    public static function getKey(): string
    {
        $instance = resolve(static::class);
        return $instance->key();
    }

    /**
     * A validator to validate any new values
     *
     * @param mixed $value The new value
     * @return Validator
     */
    public function validator($value): Validator
    {
        return \Illuminate\Support\Facades\Validator::make([
            $this->inputName() => $value
        ], $this->rules());
    }

    /**
     * Return the validation rules for the setting.
     *
     * You may also override the validator method to customise the validator further
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * The key to use for the field options
     *
     * @return string
     */
    final public function inputName(): string
    {
        return sha1($this->key());
    }

}
