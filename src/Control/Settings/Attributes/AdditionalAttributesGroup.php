<?php

namespace BristolSU\Support\Control\Settings\Attributes;

use BristolSU\Support\Settings\Definition\Definition;

class AdditionalAttributesGroup extends Definition
{

    public static function key(): string
    {
        return 'Control.AdditionalAttribute.Group';
    }

    public static function defaultValue()
    {
        return [];
    }

}
