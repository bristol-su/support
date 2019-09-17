<?php

namespace BristolSU\Support\Tests\Completion\CompletionConditions\EventFired;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Events\Contracts\EventRepository;
use BristolSU\Support\Completion\CompletionConditions\EventFired\EventFired;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class EventFiredTest extends TestCase
{

    /** @test */
    public function isComplete_returns_true(){
        $activityInstance = $this->prophesize(ActivityInstance::class)->reveal();
        $moduleInstance = $this->prophesize(\BristolSU\Support\ModuleInstance\Contracts\ModuleInstance::class)->reveal();
        $condition = new EventFired('module1', $this->prophesize(EventRepository::class)->reveal());
        $this->assertTrue($condition->isComplete([], $activityInstance, $moduleInstance));
    }
    
    /** @test */
    public function options_returns_a_list_of_events(){
        $eventRepository = $this->prophesize(EventRepository::class);
        $eventRepository->allForModule('module1')->willReturn([
            ['event' => 'event1', 'name' => 'name1'],
            ['event' => 'event2', 'name' => 'name2']
        ]);
        
        $condition = new EventFired('module1', $eventRepository->reveal());
        
        $this->assertEquals([
            'event_type' => [
                'event1' => 'name1',
                'event2' => 'name2'
            ]
        ], $condition->options());
    }
    
    /** @test */
    public function name_returns_a_string(){
        $condition = new EventFired('module1', $this->prophesize(EventRepository::class)->reveal());
        $this->assertIsString($condition->name());
    }

    /** @test */
    public function description_returns_a_string(){
        $condition = new EventFired('module1', $this->prophesize(EventRepository::class)->reveal());
        $this->assertIsString($condition->description());
    }

    /** @test */
    public function alias_returns_a_string(){
        $condition = new EventFired('module1', $this->prophesize(EventRepository::class)->reveal());
        $this->assertIsString($condition->alias());
    }
    
}