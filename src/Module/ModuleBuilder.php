<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Completion\Contracts\CompletionEventRepository;
use BristolSU\Support\Contracts\Module\ModuleBuilder as ModuleBuilderContract;
use \BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use Exception;
use Illuminate\Contracts\Config\Repository;

class ModuleBuilder implements ModuleBuilderContract
{

    /**
     * @var ModuleContract
     */
    private $module;
    /**
     * @var CompletionEventRepository
     */
    private $completionEventRepository;
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var Repository
     */
    private $config;

    private $alias;

    // TODO Refactor out dependence on config
    public function __construct(ModuleContract $module,
                                CompletionEventRepository $completionEventRepository,
                                PermissionRepository $permissionRepository,
                                Repository $config)
    {
        $this->module = $module;
        $this->completionEventRepository = $completionEventRepository;
        $this->permissionRepository = $permissionRepository;
        $this->config = $config;
    }

    public function create(string $alias)
    {
        $this->alias = $alias;
    }

    public function setCompletionEvents()
    {
        $this->module->setCompletionEvents(
            $this->completionEventRepository->allForModule($this->getAlias())
        );
    }

    private function getAlias(): string
    {
        if ($this->alias === null) {
            throw new Exception('Set an alias before using the builder');
        }
        return $this->alias;
    }

    public function setAlias()
    {
        $this->module->setAlias($this->getAlias());
    }

    public function setPermissions()
    {
        $this->module->setPermissions(
            $this->permissionRepository->forModule($this->getAlias())
        );
    }

    public function setName()
    {
        $this->module->setName(
            $this->config->get($this->getAlias() . '.name')
        );
    }

    public function setDescription()
    {
        $this->module->setDescription(
            $this->config->get($this->getAlias() . '.description')
        );
    }

    public function setSettings()
    {
        $this->module->setSettings(
            $this->config->get($this->getAlias() . '.settings')
        );
    }

    public function getModule(): ModuleContract
    {
        return $this->module;
    }

}