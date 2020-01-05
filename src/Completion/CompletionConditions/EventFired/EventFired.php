<?php

namespace BristolSU\Support\Completion\CompletionConditions\EventFired;

use BristolSU\Support\Events\Contracts\EventRepository;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

class EventFired extends CompletionCondition
{

    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(string $moduleAlias, EventRepository $eventRepository)
    {
        parent::__construct($moduleAlias);
        $this->eventRepository = $eventRepository;
    }

    public function options(): array
    {
        $options = ['event_type' => []];
        $events = $this->eventRepository->allForModule($this->moduleAlias());
        foreach($events as $event) {
            $options['event_type'][$event['event']] = $event['name'];
        }
        return $options;
    }

    public function name(): string
    {
        return 'Event Fired. Not built yet!';
    }

    public function description(): string
    {
        return 'Event Fired. Warning: this will not be dynamically calculated so reverting a change to undo the condition will not mark the module as incomplete.';
    }

    public function alias(): string
    {
        return 'portalsystem_event_fired';
    }

    public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool
    {
        // TODO
        return true;
    }
}