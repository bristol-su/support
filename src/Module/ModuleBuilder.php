<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Action\Contracts\Events\EventRepository;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Completion\Contracts\CompletionConditionRepository;
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
     * @var CompletionConditionRepository
     */
    private $completionConditionRepository;

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
                                EventRepository $eventRepository,
                                CompletionConditionRepository $completionConditionRepository)
    {
        $this->module = $module;
        $this->permissionRepository = $permissionRepository;
        $this->config = $config;
        $this->eventRepository = $eventRepository;
        $this->completionConditionRepository = $completionConditionRepository;
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
    protected function getAlias(): string
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

    public function setCompletionConditions()
    {
        $this->module->setCompletionConditions(
            collect($this->completionConditionRepository->getAllForModule($this->getAlias()))->map(function(CompletionCondition $condition) {
                return [
                    'name' => $condition->name(),
                    'description' => $condition->description(),
                    'options' => $condition->options(),
                    'alias' => $condition->alias()
                ];
            })->toArray()
        );
    }
}