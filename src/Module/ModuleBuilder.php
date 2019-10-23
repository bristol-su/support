<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Action\Contracts\Events\EventRepository;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Module\Contracts\ModuleBuilder as ModuleBuilderContract;
use \BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use Exception;
use Illuminate\Contracts\Config\Repository;

/**
 * Class ModuleBuilder
 * @package BristolSU\Support\Module
 */
class ModuleBuilder implements ModuleBuilderContract
{

    /**
     * @var ModuleContract
     */
    private $module;
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var Repository
     */
    private $config;

    /**
     * @var
     */
    private $alias;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * ModuleBuilder constructor.
     * @param ModuleContract $module
     * @param PermissionRepository $permissionRepository
     * @param Repository $config
     * @param EventRepository $eventRepository
     */
    public function __construct(ModuleContract $module,
                                PermissionRepository $permissionRepository,
                                Repository $config,
                                EventRepository $eventRepository)
    {
        $this->module = $module;
        $this->permissionRepository = $permissionRepository;
        $this->config = $config;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param string $alias
     */
    public function create(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     * @throws Exception
     */
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

    public function setTriggers()
    {
        $this->module->setTriggers(
            array_filter($this->eventRepository
                ->allForModule($this->getAlias()),
                function($event) {
                    return in_array(TriggerableEvent::class, class_implements($event['event']));
                })
        );
    }

    /**
     * @return ModuleContract
     */
    public function getModule(): ModuleContract
    {
        return $this->module;
    }

}