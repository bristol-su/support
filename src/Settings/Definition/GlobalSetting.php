<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Settings\SettingRepository;
use Illuminate\Contracts\Validation\Validator;

abstract class GlobalSetting implements Setting
{

    /**
     * Should the setting value be encrypted?
     *
     * @var bool
     */
    protected bool $encrypt = false;

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
     * Set the setting value.
     *
     * @param mixed $value The value to set the setting to
     * @return void
     */
    public static function setValue($value): void
    {
        $instance = resolve(static::class);
        $instance->setSettingValue($value);
    }

    /**
     * Set the setting value.
     *
     * @param mixed $value The value to set the setting to
     * @return void
     */
    public function setSettingValue($value): void
    {
        app(SettingRepository::class)
            ->setGlobal($this->key(), $value);
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

    public function shouldEncrypt(): bool
    {
        return $this->encrypt;
    }
}
