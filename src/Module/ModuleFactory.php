<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Contracts\Module\ModuleBuilder as ModuleBuilderContract;
use BristolSU\Support\Module\Contracts\Module;
use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryAlias;
use Illuminate\Contracts\Container\Container;

class ModuleFactory implements ModuleFactoryAlias
{

    /**
     * @var ModuleBuilderContract
     */
    private $moduleBuilder;

    public function __construct(ModuleBuilderContract $moduleBuilder)
    {
        $this->moduleBuilder = $moduleBuilder;
    }
    
    public function fromAlias(string $alias): Module
    {
        $this->moduleBuilder->create($alias);
        $this->moduleBuilder->setAlias();
        $this->moduleBuilder->setName();
        $this->moduleBuilder->setDescription();
        $this->moduleBuilder->setCompletionEvents();
        $this->moduleBuilder->setPermissions();
        $this->moduleBuilder->setSettings();
        return $this->moduleBuilder->getModule();
    }
}