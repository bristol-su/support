<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;

class ModuleManager implements ModuleManagerContract
{

    protected $aliases = [];
    
    public function register($alias)
    {
        if(!$this->exists($alias)) {
            $this->aliases[] = $alias;
        }
    }

    public function aliases()
    {
        return $this->aliases;
    }

    public function exists(string $alias): bool
    {
        return in_array($alias, $this->aliases());
    }
    
}