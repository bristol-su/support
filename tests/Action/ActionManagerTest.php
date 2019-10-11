<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionManager;
use BristolSU\Support\Tests\TestCase;

class ActionManagerTest extends TestCase
{

    /** @test */
    public function registerEvent_registers_an_event(){
        $manager = new ActionManager;

        $manager->registerEvent('alias1', 'name1', 'Class1', 'Description1');
        $manager->registerEvent('alias2', 'name2', 'Class2', 'Description2');
        
        $this->assertEquals([
            'alias1' => [[
                'name' => 'name1',
                'event' => 'Class1',
                'description' => 'Description1'
            ]],
            'alias2' => [[
                'name' => 'name2',
                'event' => 'Class2',
                'description' => 'Description2'
            ]]
        ], $manager->all());
    }
    
    /** @test */
    public function allForModule_returns_only_events_for_a_module(){
        $manager = new ActionManager;

        $manager->registerEvent('alias1', 'name1', 'Class1', 'Description1');
        $manager->registerEvent('alias2', 'name2', 'Class2', 'Description2');

        $this->assertEquals([[
                'name' => 'name1',
                'event' => 'Class1',
                'description' => 'Description1'
        ]], $manager->allForModule('alias1'));
    }
    
}