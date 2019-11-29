<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryContract;
use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;
use BristolSU\Support\Module\Contracts\ModuleRepository as ModuleRepositoryContract;

/**
 * Class ModuleRepository
 * @package BristolSU\Support\Module
 */
class ModuleRepository implements ModuleRepositoryContract
{

    /**
     * @var ModuleManagerContract
     */
    private $manager;
    /**
     * @var ModuleFactoryContract
     */
    private $factory;

    /**
     * ModuleRepository constructor.
     * @param ModuleManagerContract $manager
     * @param ModuleFactoryContract $factory
     */
    public function __construct(ModuleManagerContract $manager, ModuleFactoryContract $factory)
    {
        $this->manager = $manager;
        $this->factory = $factory;
    }

    /**
     * @return array
     */
    public function all()
    {
        $modules = [];
        foreach ($this->manager->aliases() as $alias) {
            $modules[$alias] = $this->factory->fromAlias($alias);
        }
        return $modules;
    }

    /**
     * @param $alias
     * @return Contracts\Module|null
     */
    public function findByAlias($alias)
    {
        if ($this->manager->exists($alias)) {
            return $this->factory->fromAlias($alias);
        }
        return null;
    }

}
