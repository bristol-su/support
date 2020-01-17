<?php

namespace BristolSU\Support\Completion\CompletionConditions\EventFired;

use BristolSU\Support\Events\Contracts\EventRepository;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

/**
 * Has an event been fired
 */
class EventFired extends CompletionCondition
{

    /**
     * Holds the event repository
     * 
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @param string $moduleAlias Alias of the module the completion condition is for
     * @param EventRepository $eventRepository The event repository to get events from
     */
    public function __construct(string $moduleAlias, EventRepository $eventRepository)
    {
        parent::__construct($moduleAlias);
        $this->eventRepository = $eventRepository;
    }

    /**
     * Get all events for the module instance
     * 
     * @return array
     */
    public function options(): array
    {
        $options = ['event_type' => []];
        $events = $this->eventRepository->allForModule($this->moduleAlias());
        foreach($events as $event) {
            $options['event_type'][$event['event']] = $event['name'];
        }
        return $options;
    }

    /**
     * Name of the completion condition
     * 
     * @return string
     */
    public function name(): string
    {
        return 'Event Fired.';
    }

    /**
     * Description of the completion condition
     * 
     * @return string
     */
    public function description(): string
    {
        return 'Event Fired. Warning: this will not be dynamically calculated so reverting a change to undo the condition will not mark the module as incomplete.';
    }

    /**
     * Alias of the completion condition
     * 
     * @return string
     */
    public function alias(): string
    {
        return 'portalsystem_event_fired';
    }

    /**
     * Is the condition satisfied?
     * 
     * 
     * @param array $settings Settings 
     * @param ActivityInstance $activityInstance Activity instance to test
     * @param ModuleInstance $moduleInstance Module instance to test
     * 
     * @return bool
     */
    public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool
    {
        return true;
    }
}