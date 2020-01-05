<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\ActionBuilder as ActionBuilderContract;
use BristolSU\Support\Action\Contracts\ActionRepository as ActionRepositoryContract;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\ModuleInstance\ModuleInstance;

/**
 * Dispatches actions when TriggerableEvents are fired
 */
class ActionDispatcher 
{

    /**
     * Holds an Action Builder to build actions
     * 
     * @var ActionBuilderContract
     */
    private $builder;

    /**
     * Initialise the Action Dispatcher
     * 
     * @param ActionBuilderContract $builder
     */
    public function __construct(ActionBuilderContract $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Dispatch any actions.
     * 
     * Finds all action instances in the database, builds each one and dispatches them
     * 
     * @param TriggerableEvent $event Event that has been fired.
     */
    public function handle(TriggerableEvent $event)
    {
        // TODO Replace the database call with a repository class
        
        $actionInstances = ActionInstance::where('module_instance_id', app(ModuleInstance::class)->id)
            ->where('event', get_class($event))
            ->get();
        
        foreach($actionInstances as $actionInstance) {
            $action = $this->builder->build($actionInstance, $event->getFields());
            dispatch($action);
        }
    }
    
}