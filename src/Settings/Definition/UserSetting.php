<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Settings\SettingRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

abstract class UserSetting implements Setting
{

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
