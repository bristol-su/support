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
     * Create a module from its alias
     * 
     * @param string $alias Alias of the module
     * @return Module Fully constructed module
     */
    public function fromAlias(string $alias): Module
    {
        $moduleBuilder = app(ModuleBuilderContract::class);
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
