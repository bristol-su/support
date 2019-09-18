<?php

namespace BristolSU\Support\Contracts\Module;

use BristolSU\Support\Module\Contracts\Module;

interface ModuleBuilder
{
    public function setCompletionEvents();

    public function create(string $alias);
    
    public function setAlias();

    public function setPermissions();

    public function setName();

    public function setDescription();

    public function setSettings();

    public function getModule(): Module;
}