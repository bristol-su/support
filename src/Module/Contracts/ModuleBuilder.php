<?php

namespace BristolSU\Support\Module\Contracts;

interface ModuleBuilder
{
    public function create(string $alias);
    
    public function setAlias();

    public function setPermissions();

    public function setName();

    public function setDescription();

    public function setSettings();

    public function setTriggers();
    
    public function getModule(): Module;
}