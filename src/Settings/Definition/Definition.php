<?php

namespace BristolSU\Support\Settings\Definition;

abstract class Definition
{

    abstract public static function key(): string;

    abstract public static function defaultValue();

}
