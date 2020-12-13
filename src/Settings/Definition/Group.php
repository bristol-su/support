<?php

namespace BristolSU\Support\Settings\Definition;

abstract class Group
{

    abstract public function key(): string;

    abstract public function name(): string;

    public function icon(): ?string
    {
        return null;
    }

    abstract public function description(): string;
}
