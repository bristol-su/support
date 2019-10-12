<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionBuilder;
use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceField;
use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;

class ActionBuilderTest extends TestCase
{

    /** @test */
    public function build_resolves_the_class_from_the_container(){
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyAction::class, ['data' => []])->shouldBeCalled()->willReturn(new ActionBuilderDummyAction);
        
        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyAction::class,
            'event' => ActionBuilderDummyEvent::class
        ]);
        $builder = new ActionBuilder($app->reveal());
        $builder->build($actionInstance, []);
    }
    
    /** @test */
    public function build_maps_action_fields_to_event_fields(){
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyAction::class, ['data' => ['action1' => 'field1value']])->shouldBeCalled()->willReturn(new ActionBuilderDummyAction);

        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyAction::class,
            'event' => ActionBuilderDummyEvent::class
        ]);
        $actionInstanceField = factory(ActionInstanceField::class)->create([
            'event_field' => 'field1',
            'action_field' => 'action1',
            'action_instance_id' => $actionInstance->id
        ]);
        
        $builder = new ActionBuilder($app->reveal());
        $builder->build($actionInstance, (new ActionBuilderDummyEvent())->getFields());
    }
    
}

class ActionBuilderDummyAction implements Action
{

    public function handle()
    {
        // TODO: Implement handle() method.
    }

    public function getFields(): array
    {
        return ['action1' => 'actionvalue1'];
    }

    public static function getFieldMetaData(): array
    {
        return [
            'action1' => [
                'label' => 'Action 1'
            ]
        ];
    }

}

class ActionBuilderDummyEvent implements TriggerableEvent
{

    public function getFields(): array
    {
        return ['field1' => 'field1value'];
    }

    public static function getFieldMetaData(): array
    {
        return [
            'field1' => [
                'label' => 'Field 1'
            ]
        ];
    }
}