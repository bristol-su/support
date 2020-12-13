<?php

namespace BristolSU\Support\Settings\Definition;

abstract class Group
{

    abstract public function name(): string;

    abstract public function icon(): string;

    abstract public function description(): string;
}
