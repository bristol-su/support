<?php

namespace BristolSU\Support\Settings\Definition;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Settings\Saved\SavedSettingRepository;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;

abstract class UserSetting
{

    abstract public function key(): string;

    abstract public function defaultValue();

    abstract public function fieldOptions(): Field;

    abstract public function validator($value): Validator;

    /**
     * Get the value for the given user, or the current user if none given
     *
     * @param int|null $userId The ID of the user to check, or null to use the current user
     * @return mixed
     */
    public function getValue(int $userId = null)
    {
        if($userId === null && app(Authentication::class)->hasUser()) {
            $userId = app(Authentication::class)->getUser()->id();
        }

        $store = app(SavedSettingRepository::class);
        if($store->has(static::key())) {
            return $store->get(static::key());
        }
        return static::defaultValue();
    }

}
