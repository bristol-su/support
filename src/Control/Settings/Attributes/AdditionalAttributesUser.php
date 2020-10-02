<?php

namespace BristolSU\Support\Control\Settings\Attributes;

use BristolSU\Support\Settings\Definition\Definition;

class AdditionalAttributesUser extends Definition
{

    public static function key(): string
    {
        return 'Control.AdditionalAttribute.User';
    }

    public static function defaultValue()
    {
        return [];
    }

}
