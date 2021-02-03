<?php

namespace BristolSU\Support\Tests\Events;

use BristolSU\Support\Events\Contracts\EventManager;
use BristolSU\Support\Events\EventRepository;
use BristolSU\Support\Tests\TestCase;

class EventRepositoryTest extends TestCase
{
    /** @test */
    public function all_for_module_returns_all_events_for_a_given_module()
    {
        $manager = $this->prophesize(EventManager::class);
        $manager->allForModule('alias1')->shouldBeCalled()->willReturn([
            ['name' => 'Name1', 'description' => 'Description1', 'event' => 'Class1'],
            ['name' => 'Name2', 'description' => 'Description2', 'event' => 'Class2']
        ]);
        
        $repository = new EventRepository($manager->reveal());
        $this->assertEquals([
            ['name' => 'Name1', 'description' => 'Description1', 'event' => 'Class1'],
            ['name' => 'Name2', 'description' => 'Description2', 'event' => 'Class2']
        ], $repository->allForModule('alias1'));
    }

    /** @test */
    public function all_for_module_returns_all_events_for_a_given_module_when_events_registered()
    {
        $eventManager = new \BristolSU\Support\Events\EventManager();
        $eventManager->registerEvent('alias1', 'Name1', 'Class1', 'Description1');
        $eventManager->registerEvent('alias1', 'Name2', 'Class2', 'Description2');
        
        $repository = new EventRepository($eventManager);
        $this->assertEquals([
            ['name' => 'Name1', 'description' => 'Description1', 'event' => 'Class1'],
            ['name' => 'Name2', 'description' => 'Description2', 'event' => 'Class2']
        ], $repository->allForModule('alias1'));
    }
}
