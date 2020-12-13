<?php

namespace BristolSU\Support\Settings\Definition;

abstract class Category
{

    abstract public function key(): string;

    abstract public function name(): string;

    abstract public function definition(): string;

    public function groups(): array
    {

    }

    public function groupsWithUserSettings(): array
    {
        return array_filter($this->groups(), fn (Group $group): bool => $group->hasUserSettings());
    }

    public function groupsWithGlobalSettings(): array
    {
        return array_filter($this->groups(), fn (Group $group): bool => $group->hasGlobalSettings());
    }

    public function icon(): ?string
    {
        return null;
    }

}
