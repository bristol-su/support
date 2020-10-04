<?php

namespace BristolSU\Support\Translation;

use BristolSU\Support\Translation\Translate\Handler;
use BristolSU\Support\Translation\Translate\TranslationManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string translate(string $line, string $lang) Translate a single line
 * @method static string translateMany(array $line, string $lang) Translate an array of lines
 * @method static Handler driver(string $driver)
 */
class Translate extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'portal-translation';
    }

}
