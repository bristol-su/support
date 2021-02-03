<?php

namespace BristolSU\Support\Completion\CompletionConditions\EventFired;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Events\Contracts\EventRepository;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use FormSchema\Generator\Field;
use FormSchema\Schema\Form;

/**
 * Has an event been fired.
 */
class EventFired extends CompletionCondition
{
    /**
     * Holds the event repository.
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
     * Get all events for the module instance.
     *
     * @throws \Exception
     * @return Form
     */
    public function options(): Form
    {
        return \FormSchema\Generator\Form::make()->withField(
            Field::select('event_type')->label('Event')->required(true)->hint('What event should be fired to mark this as complete?')
                ->help('Select an event which occurs within the module. When this event is fired, the module will be marked as complete. This cannot be undone.')
                ->values(collect($this->eventRepository->allForModule($this->moduleAlias()))->map(function ($event) {
                    return ['id' => $event['event'], 'name' => $event['name']];
                })->toArray())->selectOptions(['noneSelectedText' => 'Please Select an Event', 'hideNoneSelectedText' => false])
        )->getSchema();
    }

    /**
     * Name of the completion condition.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Event Fired.';
    }

    /**
     * Description of the completion condition.
     *
     * @return string
     */
    public function description(): string
    {
        return 'Event Fired. Warning: this will not be dynamically calculated so reverting a change to undo the condition will not mark the module as incomplete.';
    }

    /**
     * Alias of the completion condition.
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
