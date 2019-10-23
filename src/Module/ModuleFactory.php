<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\ModuleBuilder as ModuleBuilderContract;
use BristolSU\Support\Module\Contracts\Module;
use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryAlias;
use Illuminate\Contracts\Container\Container;

/**
 * Class ModuleFactory
 * @package BristolSU\Support\Module
 */
class ModuleFactory implements ModuleFactoryAlias
{

    /**
     * @var ModuleBuilderContract
     */
    private $moduleBuilder;

    /**
     * ModuleFactory constructor.
     * @param ModuleBuilderContract $moduleBuilder
     */
    public function __construct(ModuleBuilderContract $moduleBuilder)
    {
        $this->moduleBuilder = $moduleBuilder;
    }

    /**
     * @param string $alias
     * @return Module
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
        return $this->moduleBuilder->getModule();
    }
}