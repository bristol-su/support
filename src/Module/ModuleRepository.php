<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Completion\ConfigCompletionEventRepository;
use BristolSU\Support\Completion\Contracts\CompletionEventRepository;
use BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryContract;
use BristolSU\Support\Module\Contracts\ModuleManager as ModuleManagerContract;
use BristolSU\Support\Module\Contracts\ModuleRepository as ModuleRepositoryContract;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;

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

    public function __construct(ModuleManagerContract $manager, ModuleFactoryContract $factory)
    {
        $this->manager = $manager;
        $this->factory = $factory;
    }

    public function all()
    {
        $modules = [];
        foreach($this->manager->aliases() as $alias) {
            $modules[$alias] = $this->factory->fromAlias($alias);
        }
        return $modules;
    }

    public function findByAlias($alias)
    {
        if($this->manager->exists($alias)) {
            return $this->factory->fromAlias($alias);
        }
        return null;
    }

}
