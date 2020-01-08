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
    // TODO Use the module builder in the construct as opposed to resolving
    public function fromAlias(string $alias): Module
    {
        $moduleBuilder = app(ModuleBuilder::class);
        $moduleBuilder->create($alias);
        $moduleBuilder->setAlias();
        $moduleBuilder->setName();
        $moduleBuilder->setDescription();
        $moduleBuilder->setPermissions();
        $moduleBuilder->setSettings();
        $moduleBuilder->setTriggers();
        $moduleBuilder->setCompletionConditions();
        $moduleBuilder->setServices();
        return $moduleBuilder->getModule();
    }
}
