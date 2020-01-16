<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\ModuleBuilder as ModuleBuilderContract;
use BristolSU\Support\Module\Contracts\Module;
use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryAlias;

/**
 * Create a module object
 */
class ModuleFactory implements ModuleFactoryAlias
{

    /**
     * Holds a module builder to build the module
     * 
     * @var ModuleBuilderContract
     */
    private $moduleBuilder;

    /**
     * @param ModuleBuilderContract $moduleBuilder Builder to create the module object
     */
    public function __construct(ModuleBuilderContract $moduleBuilder)
    {
        $this->moduleBuilder = $moduleBuilder;
    }

    /**
     * Create a module from its alias
     * 
     * @param string $alias Alias of the module
     * @return Module Fully constructed module
     */
    public function fromAlias(string $alias): Module
    {
        $this->moduleBuilder->create($alias);
        $this->moduleBuilder->setAlias();
        $this->moduleBuilder->setName();
        $this->moduleBuilder->setDescription();
        $this->moduleBuilder->setPermissions();
        $this->moduleBuilder->setSettings();
        $this->moduleBuilder->setTriggers();
        $this->moduleBuilder->setCompletionConditions();
        $this->moduleBuilder->setServices();
        return $this->moduleBuilder->getModule();
    }
}
