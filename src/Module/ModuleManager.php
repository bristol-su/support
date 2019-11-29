<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;

/**
 * Class ModuleManager
 * @package BristolSU\Support\Module
 */
class ModuleManager implements ModuleManagerContract
{

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @param $alias
     */
    public function register($alias)
    {
        if (!$this->exists($alias)) {
            $this->aliases[] = $alias;
        }
    }

    /**
     * @return array
     */
    public function aliases()
    {
        return $this->aliases;
    }

    /**
     * @param string $alias
     * @return bool
     */
    public function exists(string $alias): bool
    {
        return in_array($alias, $this->aliases());
    }
    
}