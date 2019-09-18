<?php

namespace BristolSU\Support\Module\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface Module extends Arrayable, Jsonable
{
    public function setAlias(string $alias): void;

    public function getAlias(): string;

    public function setName(string $name): void;

    public function getName(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setPermissions(array $permissions): void;

    public function getPermissions(): array;

    public function setCompletionEvents(array $completionEvents): void;

    public function getCompletionEvents(): array;

    public function setSettings(array $settings): void;

    public function getSettings(): array;
}