<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Events\Contracts\EventRepository;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Completion\Contracts\CompletionConditionRepository;
use BristolSU\Support\Connection\Contracts\ServiceRequest;
use BristolSU\Support\Module\Contracts\ModuleBuilder as ModuleBuilderContract;
use \BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\ModuleInstance\Contracts\Settings\ModuleSettingsStore;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use Exception;
use FormSchema\Transformers\VFGTransformer;
use Illuminate\Contracts\Config\Repository;

/**
 * Handles building a module using the framework tools
 */
class ModuleBuilder implements ModuleBuilderContract
{

    /**
     * Holds the module
     * 
     * @var ModuleContract
     */
    private $module;
    
    /**
     * Repository for resolving permissions
     * 
     * @var PermissionRepository
     */
    private $permissionRepository;
    
    /**
     * Configuration for resolving name/description
     * 
     * @var Repository
     */
    private $config;

    /**
     * Alias for the module to use
     * 
     * @var string
     */
    private $alias;

    /**
     * Repository for resolving events from
     * 
     * @var EventRepository
     */
    private $eventRepository;
    
    /**
     * Repository for resolving completion conditions used by the module
     * 
     * @var CompletionConditionRepository
     */
    private $completionConditionRepository;
    
    /**
     * Store for resolving module settings out of
     * 
     * @var ModuleSettingsStore
     */
    private $moduleSettingsStore;
    
    /**
     * Service request for resolving services needed by the module
     * 
     * @var ServiceRequest
     */
    private $serviceRequest;

    /**
     * @param ModuleContract $module Module object to build
     * @param PermissionRepository $permissionRepository Repository for resolving permissions
     * @param Repository $config Configuration for resolving name/description
     * @param EventRepository $eventRepository Repository for resolving events from
     * @param CompletionConditionRepository $completionConditionRepository Repository for resolving completion conditions used by the module
     * @param ModuleSettingsStore $moduleSettingsStore Store for resolving module settings out of
     * @param ServiceRequest $serviceRequest Service request for resolving services needed by the module
     */
    public function __construct(ModuleContract $module,
                                PermissionRepository $permissionRepository,
                                Repository $config,
                                EventRepository $eventRepository,
                                CompletionConditionRepository $completionConditionRepository,
                                ModuleSettingsStore $moduleSettingsStore,
                                ServiceRequest $serviceRequest)
    {
        $this->module = $module;
        $this->permissionRepository = $permissionRepository;
        $this->config = $config;
        $this->eventRepository = $eventRepository;
        $this->completionConditionRepository = $completionConditionRepository;
        $this->moduleSettingsStore = $moduleSettingsStore;
        $this->serviceRequest = $serviceRequest;
    }

    /**
     * Initialise the module builder
     * 
     * @param string $alias Alias to use for the module
     */
    public function create(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * Get the alias to use for the module
     * 
     * @return string Alias
     * @throws Exception If the create() function has not been called, so no alias is known
     */
    protected function getAlias(): string
    {
        if ($this->alias === null) {
            throw new Exception('Set an alias before using the builder');
        }
        return $this->alias;
    }

    /**
     * Set the alias on the module
     * 
     * @throws Exception If no alias is known by the builder
     */
    public function setAlias()
    {
        $this->module->setAlias($this->getAlias());
    }

    /**
     * Set the permissions on the module
     *
     * @throws Exception If no alias is known by the builder
     */
    public function setPermissions()
    {
        $this->module->setPermissions(
            $this->permissionRepository->forModule($this->getAlias())
        );
    }

    /**
     * Set the name of the module
     * 
     * @throws Exception If no alias is known by the builder
     */
    public function setName()
    {
        $this->module->setName(
            $this->config->get($this->getAlias().'.name')
        );
    }

    /**
     * Set the description of the module
     * 
     * @throws Exception If no alias is known by the builder
     */
    public function setDescription()
    {
        $this->module->setDescription(
            $this->config->get($this->getAlias().'.description')
        );
    }

    /**
     * Set the settings of the module
     * 
     * @throws Exception If no alias is known by the builder
     */
    public function setSettings()
    {
        $this->module->setSettings(
            (new VFGTransformer)->transformToArray(
                $this->moduleSettingsStore->get($this->getAlias())
            )
        );
    }

    /**
     * Set the triggers of the module
     * 
     * @throws Exception If no alias is known by the builder
     */
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
     * Set the services of the module
     * 
     * @throws Exception If no alias is known by the builder
     */
    public function setServices()
    {
        $this->module->setServices([
            'required' => $this->serviceRequest->getRequired($this->getAlias()),
            'optional' => $this->serviceRequest->getOptional($this->getAlias())
        ]);
    }

    /**
     * Get the built module
     * 
     * @return ModuleContract Initialised module
     */
    public function getModule(): ModuleContract
    {
        return $this->module;
    }

    /**
     * Set the completion conditions for the module
     * 
     * @throws Exception If no alias is known by the builder
     */
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