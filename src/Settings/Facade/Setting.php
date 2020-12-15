<?php


namespace BristolSU\Support\Settings\Facade;


use Illuminate\Support\Facades\Facade;

class Setting extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return \BristolSU\Support\Settings\Setting::class;
    }

}
