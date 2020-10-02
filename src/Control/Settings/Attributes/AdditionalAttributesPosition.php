<?php

namespace BristolSU\Support\Control\Settings\Attributes;

use BristolSU\Support\Settings\Definition\Definition;

class AdditionalAttributesPosition extends Definition
{

    public static function key(): string
    {
        return 'Control.AdditionalAttribute.Position';
    }

    public static function defaultValue()
    {
        return [];
    }

}
