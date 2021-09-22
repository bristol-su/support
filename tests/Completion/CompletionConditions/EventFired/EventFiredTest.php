<?php

namespace BristolSU\Support\Tests\Completion\CompletionConditions\EventFired;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\CompletionConditions\EventFired\EventFired;
use BristolSU\Support\Events\Contracts\EventRepository;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Fields\SelectField;

class EventFiredTest extends TestCase
{
    /** @test */
    public function is_complete_returns_true()
    {
        $activityInstance = $this->prophesize(ActivityInstance::class)->reveal();
        $moduleInstance = $this->prophesize(\BristolSU\Support\ModuleInstance\Contracts\ModuleInstance::class)->reveal();
        $condition = new EventFired('module1', $this->prophesize(EventRepository::class)->reveal());
        $this->assertTrue($condition->isComplete([], $activityInstance, $moduleInstance));
    }

    /** @test */
    public function options_returns_a_list_of_events()
    {
        $eventRepository = $this->prophesize(EventRepository::class);
        $eventRepository->allForModule('module1')->willReturn([
            ['event' => 'event1', 'name' => 'name1'],
            ['event' => 'event2', 'name' => 'name2']
        ]);

        $condition = new EventFired('module1', $eventRepository->reveal());

        $groups = $condition->options()->groups();
        $this->assertCount(1, $groups);

        $fields = $groups[0]->fields();
        $this->assertCount(1, $fields);

        $field = $fields[0];

        $this->assertInstanceOf(SelectField::class, $field);
        $this->assertEquals('event_type', $field->getId());
        $this->assertEquals([
            ['id' => 'event1', 'value' => 'name1'],
            ['id' => 'event2', 'value' => 'name2'],
        ], $field->getSelectOptions());
    }

    /** @test */
    public function name_returns_a_string()
    {
        $condition = new EventFired('module1', $this->prophesize(EventRepository::class)->reveal());
        $this->assertIsString($condition->name());
    }

    /** @test */
    public function description_returns_a_string()
    {
        $condition = new EventFired('module1', $this->prophesize(EventRepository::class)->reveal());
        $this->assertIsString($condition->description());
    }

    /** @test */
    public function alias_returns_a_string()
    {
        $condition = new EventFired('module1', $this->prophesize(EventRepository::class)->reveal());
        $this->assertIsString($condition->alias());
    }
}
