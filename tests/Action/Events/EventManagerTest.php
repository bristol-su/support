<?php

namespace BristolSU\Support\Tests\Action\Events;

use BristolSU\Support\Action\Events\EventManager;
use BristolSU\Support\Tests\TestCase;

class EventManagerTest extends TestCase
{

    /** @test */
    public function an_event_can_be_registered(){
        $eventManager = new EventManager();
        
        $eventManager->registerEvent('alias1', 'Name1', 'Class1', 'Description1');
        
        $this->assertEquals([
            'alias1' => [
                ['name' => 'Name1', 'description' => 'Description1', 'event' => 'Class1']
            ]
        ], $eventManager->all());
    }
    
    /** @test */
    public function all_returns_all_registered_events(){
        $eventManager = new EventManager();

        $eventManager->registerEvent('alias1', 'Name1', 'Class1', 'Description1');
        $eventManager->registerEvent('alias1', 'Name2', 'Class2', 'Description2');
        $eventManager->registerEvent('alias2', 'Name3', 'Class3', 'Description3');

        $this->assertEquals([
            'alias1' => [
                ['name' => 'Name1', 'description' => 'Description1', 'event' => 'Class1'],
                ['name' => 'Name2', 'description' => 'Description2', 'event' => 'Class2']
            ],
            'alias2' => [
                ['name' => 'Name3', 'description' => 'Description3', 'event' => 'Class3']
            ]
        ], $eventManager->all());
    }
    
    /** @test */
    public function allForModule_returns_all_events_for_a_module(){
        $eventManager = new EventManager();

        $eventManager->registerEvent('alias1', 'Name1', 'Class1', 'Description1');
        $eventManager->registerEvent('alias1', 'Name2', 'Class2', 'Description2');
        $eventManager->registerEvent('alias2', 'Name3', 'Class3', 'Description3');

        $this->assertEquals([
            ['name' => 'Name1', 'description' => 'Description1', 'event' => 'Class1'],
            ['name' => 'Name2', 'description' => 'Description2', 'event' => 'Class2']
        ], $eventManager->allForModule('alias1'));
    }
    
    /** @test */
    public function allForModule_returns_an_empty_array_if_no_events_registered_for_the_module(){
        $eventManager = new EventManager();

        $eventManager->registerEvent('alias1', 'Name1', 'Class1', 'Description1');
        $eventManager->registerEvent('alias1', 'Name2', 'Class2', 'Description2');
        $eventManager->registerEvent('alias2', 'Name3', 'Class3', 'Description3');

        $this->assertEquals([], $eventManager->allForModule('alias5'));
    }
    
}