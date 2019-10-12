<?php

namespace BristolSU\Support\Tests\Action\Facade;

use BristolSU\Support\Action\Contracts\ActionManager;
use BristolSU\Support\Action\Facade\ActionManager as ActionManagerFacade;
use BristolSU\Support\Tests\TestCase;

class ActionManagerTest extends TestCase
{

    /** @test */
    public function actions_can_be_registered_through_the_facade(){
        ActionManagerFacade::registerAction('Class\One', 'Name1', 'Description1');
        
        $actionManager = app()->make(ActionManager::class);
        $action = $actionManager->fromClass('Class\One');
        $this->assertEquals('Class\One', $action['class']);
        $this->assertEquals('Name1', $action['name']);
        $this->assertEquals('Description1', $action['description']);
    }
    
}