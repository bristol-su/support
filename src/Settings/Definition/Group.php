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

    public function settings(): array
    {

    }

    public function userSettings(): array
    {

    }

    public function globalSettings(): array
    {

    }

    public function hasUserSettings(): bool
    {

    }

    public function hasGlobalSettings(): bool
    {

    }
}
