<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Settings\SettingRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

abstract class UserSetting implements Setting
{

    /**
     * Should the setting value be encrypted?
     *
     * @var bool
     */
    protected bool $encrypt = false;

    /**
     * Get the value for the given user, or the current user if no user given
     *
     * @param int|null $userId The ID of the user to check, or null to use the current user
     * @return mixed
     */
    public function value(int $userId = null)
    {
        return app(SettingRepository::class)
            ->getUserValue($this->key(), $userId);
    }

    /**
     * Get the value of the setting
     *
     * @param int|null $userId The ID of the user to check, or null to use the current user
     * @return mixed
     */
    public static function getValue($userId = null)
    {
        $instance = resolve(static::class);
        return $instance->value($userId);
    }

    /**
     * Set the setting value.
     *
     * @param mixed $value The value to set the setting to
     * @param int|null $userId The ID of the user to set the setting for, or null to use the current user
     * @return void
     */
    public static function setValue($value, int $userId = null): void
    {
        $instance = resolve(static::class);
        $instance->setSettingValue($value, $userId);
    }

    /**
     * Set the setting value.
     *
     * @param mixed $value The value to set the setting to
     * @param int|null $userId The ID of the user to set the setting for, or null to use the current user
     * @return void
     */
    public function setSettingValue($value, int $userId = null): void
    {
        app(SettingRepository::class)
            ->setForUser($this->key(), $value, $userId);
    }

    /**
     * Set the setting default.
     *
     * @param mixed $value The value to set the setting to
     * @return void
     */
    public static function setDefault($value): void
    {
        $instance = resolve(static::class);
        $instance->setSettingDefault($value);
    }

    /**
     * Set the setting default
     *
     * @param mixed $value The value to set the setting default to
     * @return void
     */
    public function setSettingDefault($value): void
    {
        app(SettingRepository::class)
            ->setForAllUsers($this->key(), $value);
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
