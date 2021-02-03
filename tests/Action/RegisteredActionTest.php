<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\RegisteredAction;
use BristolSU\Support\Tests\TestCase;

class RegisteredActionTest extends TestCase
{
    /** @test */
    public function name_can_be_set_and_got()
    {
        $registeredAction = new RegisteredAction();
        $registeredAction->setName('Some name');
        $this->assertEquals('Some name', $registeredAction->getName());
    }

    /** @test */
    public function description_can_be_set_and_got()
    {
        $registeredAction = new RegisteredAction();
        $registeredAction->setDescription('Some description');
        $this->assertEquals('Some description', $registeredAction->getDescription());
    }

    /** @test */
    public function class_name_can_be_set_and_got()
    {
        $registeredAction = new RegisteredAction();
        $registeredAction->setClassName('ClassName\One');
        $this->assertEquals('ClassName\One', $registeredAction->getClassName());
    }
    
    /** @test */
    public function instance_can_be_constructed_from_from_array()
    {
        $registeredAction = RegisteredAction::fromArray([
            'name' => 'Some Name',
            'description' => 'A description',
            'class' => 'ClassName\Two'
        ]);
        $this->assertEquals('Some Name', $registeredAction->getName());
        $this->assertEquals('A description', $registeredAction->getDescription());
        $this->assertEquals('ClassName\Two', $registeredAction->getClassName());
    }
    
    /** @test */
    public function to_array_returns_all_parameters_as_an_array()
    {
        $registeredAction = new RegisteredAction();
        $registeredAction->setName('Some Name');
        $registeredAction->setDescription('A description');
        $registeredAction->setClassName('ClassName\Two');
        
        $this->assertEquals([
            'name' => 'Some Name',
            'description' => 'A description',
            'class' => 'ClassName\Two'
        ], $registeredAction->toArray());
    }
    
    /** @test */
    public function to_json_returns_all_parameters_as_json()
    {
        $registeredAction = new RegisteredAction();
        $registeredAction->setName('Some Name');
        $registeredAction->setDescription('A description');
        $registeredAction->setClassName('ClassName\Two');

        $this->assertEquals(json_encode([
            'name' => 'Some Name',
            'description' => 'A description',
            'class' => 'ClassName\Two'
        ]), $registeredAction->toJson());
    }
    
    /** @test */
    public function __to_string_returns_all_parameters_as_json()
    {
        $registeredAction = new RegisteredAction();
        $registeredAction->setName('Some Name');
        $registeredAction->setDescription('A description');
        $registeredAction->setClassName('ClassName\Two');

        $this->assertEquals(json_encode([
            'name' => 'Some Name',
            'description' => 'A description',
            'class' => 'ClassName\Two'
        ]), (string) $registeredAction);
    }
}
