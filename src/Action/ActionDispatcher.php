<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\ActionBuilder as ActionBuilderContract;
use BristolSU\Support\Action\Contracts\ActionRepository as ActionRepositoryContract;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\ModuleInstance\ModuleInstance;

class ActionDispatcher 
{

    /**
     * @var ActionBuilderContract
     */
    private $builder;

    public function __construct(ActionBuilderContract $builder)
    {
        $this->builder = $builder;
    }

    public function handle(TriggerableEvent $event)
    {
        // TODO replace with repository
        $actionInstances = ActionInstance::where('module_instance_id', app(ModuleInstance::class)->id)
            ->where('event', get_class($event))
            ->get();
        
        foreach($actionInstances as $actionInstance) {
            $action = $this->builder->build($actionInstance, $event->getFields());
            dispatch($action);
        }
    }
    
}