<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionManager;
use BristolSU\Support\Tests\TestCase;

class ActionManagerTest extends TestCase
{

    /** @test */
    public function an_action_can_be_registered(){
        $manager = new ActionManager();
        $manager->registerAction('ClassName\One', 'Some Name', 'Description');
        
        $this->assertEquals([
            'ClassName\One' => [
                'name' => 'Some Name',
                'class' => 'ClassName\One',
                'description' => 'Description'
            ]
        ], $manager->all());
    }
    
    /** @test */
    public function multiple_actions_can_be_registered(){
        $manager = new ActionManager();
        $manager->registerAction('ClassName\One', 'Some Name', 'Description');
        $manager->registerAction('ClassName\Two', 'Some Name2', 'Description2');

        $this->assertEquals([
            'ClassName\One' => [
                'name' => 'Some Name',
                'class' => 'ClassName\One',
                'description' => 'Description'
            ],
            'ClassName\Two' => [
                'name' => 'Some Name2',
                'class' => 'ClassName\Two',
                'description' => 'Description2'
            ]
        ], $manager->all());
    }
    
    /** @test */
    public function a_single_action_may_be_retrieved_by_class_name(){
        $manager = new ActionManager();
        $manager->registerAction('ClassName\One', 'Some Name', 'Description');

        $this->assertEquals([
            'name' => 'Some Name',
            'class' => 'ClassName\One',
            'description' => 'Description'
        ], $manager->fromClass('ClassName\One'));
    }
    
    /** @test */
    public function an_exception_is_thrown_if_the_class_name_is_not_registered(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Action [ClassName\One] not found');
        $manager = new ActionManager();

        $manager->fromClass('ClassName\One');
    }
    
}