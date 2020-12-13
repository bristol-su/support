<?php

namespace BristolSU\Support\Settings\Definition;

abstract class Category
{

    abstract public function key(): string;

    abstract public function name(): string;

    abstract public function definition(): string;

    public function icon(): ?string
    {
        return null;
    }

}
