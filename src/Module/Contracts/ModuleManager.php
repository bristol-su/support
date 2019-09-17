<?php

namespace BristolSU\Support\Module\Contracts;

interface ModuleManager
{
    public function register($alias);

    public function aliases();

    public function exists(string $alias): bool;
}