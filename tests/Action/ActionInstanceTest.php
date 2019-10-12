<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceField;
use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class ActionInstanceTest extends TestCase
{
    /** @test */
    public function actionInstance_has_many_actionInstanceFields()
    {
        $actionInstance = factory(ActionInstance::class)->create();
        $actionInstanceFieldsFactory = factory(ActionInstanceField::class, 10)->create([
            'action_instance_id' => $actionInstance->id
        ]);
        
        $actionInstanceFields = $actionInstance->actionInstanceFields;
        foreach($actionInstanceFieldsFactory as $field) {
            $this->assertModelEquals($field, $actionInstanceFields->shift());
        }
    }
    
    /** @test */
    public function actionInstance_has_an_event_fields_attribute(){
        $actionInstance = factory(ActionInstance::class)->create([
            'event' => ActionInstanceDummyEvent::class,
            'action' => ActionInstanceDummyAction::class
        ]);
        
        $this->assertEquals([
            'eventfield1' => [
                'label' => 'Event Field 1'
            ]
        ], $actionInstance->event_fields);

    }
    
    /** @test */
    public function actionInstance_has_an_action_fields_attribute(){
        $actionInstance = factory(ActionInstance::class)->create([
            'event' => ActionInstanceDummyEvent::class,
            'action' => ActionInstanceDummyAction::class
        ]);

        $this->assertEquals([
            'actionfield1' => [
                'label' => 'Action Field 1'
            ]
        ], $actionInstance->action_fields);
    }
    
    /** @test */
    public function actionInstance_has_a_module_instance(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $actionInstance = factory(ActionInstance::class)->create([
            'module_instance_id' => $moduleInstance->id
        ]);
        
        $this->assertInstanceOf(ModuleInstance::class, $actionInstance->moduleInstance);
        $this->assertModelEquals($moduleInstance, $actionInstance->moduleInstance);
        
    }
}

class ActionInstanceDummyAction implements Action
{

    public function handle()
    {
        // TODO: Implement handle() method.
    }

    public function getFields(): array
    {
        return [
            'actionfield1' => 'ActionFieldValue'
        ];
    }

    public static function getFieldMetaData(): array
    {
        return [
            'actionfield1' => [
                'label' => 'Action Field 1'
            ]
        ];
    }
}

class ActionInstanceDummyEvent implements TriggerableEvent
{

    public function getFields(): array
    {
        return [
            'eventfield1' => 'EventFieldValue'
        ];
    }

    public static function getFieldMetaData(): array
    {
        return [
            'eventfield1' => [
                'label' => 'Event Field 1'
            ] 
        ];
    }
}