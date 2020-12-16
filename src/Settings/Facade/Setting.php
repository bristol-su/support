<?php


namespace BristolSU\Support\Settings\Facade;


use BristolSU\Support\Settings\SettingRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed getUserValue(string $key, int $userId)
 * @method static mixed getGlobalValue(string $key)
 * @method static void setForUser(string $key, mixed $value, int $userId)
 * @method static void setForAllUsers(string $key, mixed $value)
 * @method static void setGlobal(string $key, mixed $value)
 */
class Setting extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return SettingRepository::class;
    }

}


