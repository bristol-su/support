<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;

/**
 * Stores module registration information in an array
 */
class ModuleManager implements ModuleManagerContract
{

    /**
     * A list of registered aliases
     * 
     * @var array
     */
    protected $aliases = [];

    /**
     * Register a new module by alias
     * 
     * @param string $alias Alias of the module
     * @return void
     */
    public function register($alias)
    {
        if(!$this->exists($alias)) {
            $this->aliases[] = $alias;
        }
    }

    /**
     * Get all registered aliases
     * 
     * @return array
     */
    public function aliases()
    {
        return $this->aliases;
    }

    /**
     * Check if a module has been registered
     * 
     * @param string $alias Alias of the module
     * @return bool If the module has been registered
     */
    public function exists(string $alias): bool
    {
        return in_array($alias, $this->aliases());
    }
    
}