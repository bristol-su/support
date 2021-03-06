<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\ActionBuilder as ActionBuilderContract;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\ModuleInstance\ModuleInstance;

/**
 * Dispatches actions when TriggerableEvents are fired.
 */
class ActionDispatcher
{
    /**
     * Holds an Action Builder to build actions.
     *
     * @var ActionBuilderContract
     */
    private $builder;

    /**
     * Initialise the Action Dispatcher.
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
        $actionInstances = app(\BristolSU\Support\Action\Contracts\ActionInstanceRepository::class)->forEvent(
            (int) app(ModuleInstance::class)->id,
            get_class($event)
        );
        
        foreach ($actionInstances as $actionInstance) {
            $action = $this->builder->build($actionInstance, $event->getFields());
            if ($actionInstance->should_queue) {
                dispatch($action);
            } else {
                dispatch_now($action);
            }
        }
    }
}
