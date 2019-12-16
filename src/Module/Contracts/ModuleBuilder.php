<?php

namespace BristolSU\Support\Module\Contracts;

/**
 * Interface ModuleBuilder
 * @package BristolSU\Support\Module\Contracts
 */
interface ModuleBuilder
{
    /**
     * @param string $alias
     * @return mixed
     */
    public function create(string $alias);
    
    public function setAlias();

    public function setPermissions();

    public function setName();

    public function setDescription();

    public function setSettings();

    public function setTriggers();

    public function setCompletionConditions();

    public function setServices();
        
    /**
     * @return Module
     */
    public function getModule(): Module;
}