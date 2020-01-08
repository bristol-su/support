<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryContract;
use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;
use BristolSU\Support\Module\Contracts\ModuleRepository as ModuleRepositoryContract;

/**
 * Module Repository for retrieving registered modules
 */
class ModuleRepository implements ModuleRepositoryContract
{

    /**
     * Holds the module manager to retrieve modules from
     * 
     * @var ModuleManagerContract
     */
    private $manager;
    
    /**
     * Module factory to create modules
     * 
     * @var ModuleFactoryContract
     */
    private $factory;

    /**
     * @param ModuleManagerContract $manager Manager to retrieve modules from
     * @param ModuleFactoryContract $factory Factory to create the modules
     */
    public function __construct(ModuleManagerContract $manager, ModuleFactoryContract $factory)
    {
        $this->manager = $manager;
        $this->factory = $factory;
    }

    /**
     * Get all modules registered
     * 
     * @return \BristolSU\Support\Module\Contracts\Module[]
     */
    public function all()
    {
        $modules = [];
        foreach($this->manager->aliases() as $alias) {
            $modules[$alias] = $this->factory->fromAlias($alias);
        }
        return $modules;
    }

    /**
     * Get a module by alias
     * 
     * @param string $alias Alias of the module
     * @return \BristolSU\Support\Module\Contracts\Module|null Module or null if not registered 
     */
    public function findByAlias($alias)
    {
        if($this->manager->exists($alias)) {
            return $this->factory->fromAlias($alias);
        }
        return null;
    }

}
