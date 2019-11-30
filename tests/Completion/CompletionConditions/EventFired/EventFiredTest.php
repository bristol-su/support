<?php

namespace BristolSU\Support\Tests\Completion\CompletionConditions\EventFired;

use BristolSU\Support\Action\Contracts\Events\EventRepository;
use BristolSU\Support\Completion\CompletionConditions\EventFired\EventFired;
use BristolSU\Support\Tests\TestCase;

class EventFiredTest extends TestCase
{

    /** @test */
    public function isComplete_returns_true(){
        $this->markTestIncomplete('Functionality not yet implemented');
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
    
}