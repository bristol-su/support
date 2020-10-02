<?php

namespace BristolSU\Support\Control\Settings\Attributes;

use BristolSU\Support\Settings\Definition\Definition;

class AdditionalAttributesRole extends Definition
{

    public static function key(): string
    {
        return 'Control.AdditionalAttribute.Role';
    }

    public static function defaultValue()
    {
        return [];
    }

}
