<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionBuilder;
use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceField;
use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Prophecy\Argument;

class ActionBuilderTest extends TestCase
{

    /** @test */
    public function build_resolves_the_class_from_the_container()
    {
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyAction::class, ['data' => []])->shouldBeCalled()->willReturn(new ActionBuilderDummyAction([]));

        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyAction::class,
            'event' => ActionBuilderDummyEvent::class
        ]);
        $builder = new ActionBuilder($app->reveal());
        $builder->build($actionInstance, []);
    }

    /** @test */
    public function build_maps_action_fields_to_action_values()
    {
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyAction::class, ['data' => ['action1' => 'field1value']])->shouldBeCalled()->willReturn(new ActionBuilderDummyAction([]));

        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyAction::class,
            'event' => ActionBuilderDummyEvent::class
        ]);

        $actionInstanceField = factory(ActionInstanceField::class)->create([
            'action_value' => 'field1value',
            'action_field' => 'action1',
            'action_instance_id' => $actionInstance->id
        ]);

        $builder = new ActionBuilder($app->reveal());
        $builder->build($actionInstance, (new ActionBuilderDummyEvent())->getFields());
    }

    /** @test */
    public function build_replaces_event_fields_with_the_correct_value()
    {
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyAction::class, ['data' => ['action1' => 'field1value with an event field of field1valueFromEvent']])->shouldBeCalled()->willReturn(new ActionBuilderDummyAction([]));

        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyAction::class,
            'event' => ActionBuilderDummyEvent::class
        ]);

        $actionInstanceField = factory(ActionInstanceField::class)->create([
            'action_value' => 'field1value with an event field of {{event:field1}}',
            'action_field' => 'action1',
            'action_instance_id' => $actionInstance->id
        ]);

        $builder = new ActionBuilder($app->reveal());
        $builder->build($actionInstance, (new ActionBuilderDummyEvent())->getFields());
    }


    /** @test */
    public function build_replaces_all_event_fields_with_the_correct_value()
    {
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyAction::class, ['data' => ['action1' => 'field1value with field1valueFromEvent an event field of field1valueFromEvent']])->shouldBeCalled()->willReturn(new ActionBuilderDummyAction([]));

        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyAction::class,
            'event' => ActionBuilderDummyEvent::class
        ]);

        $actionInstanceField = factory(ActionInstanceField::class)->create([
            'action_value' => 'field1value with {{event:field1}} an event field of {{event:field1}}',
            'action_field' => 'action1',
            'action_instance_id' => $actionInstance->id
        ]);

        $builder = new ActionBuilder($app->reveal());
        $builder->build($actionInstance, (new ActionBuilderDummyEvent())->getFields());
    }

    /** @test */
    public function build_can_replace_multiple_event_fields()
    {
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyAction::class, ['data' => ['action1' => 'field1value with field2valueFromEvent an event field of field1valueFromEvent field1valueFromEvent']])->shouldBeCalled()->willReturn(new ActionBuilderDummyAction([]));

        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyAction::class,
            'event' => ActionBuilderDummyEvent::class
        ]);

        $actionInstanceField = factory(ActionInstanceField::class)->create([
            'action_value' => 'field1value with {{event:field2}} an event field of {{event:field1}} {{event:field1}}',
            'action_field' => 'action1',
            'action_instance_id' => $actionInstance->id
        ]);

        $builder = new ActionBuilder($app->reveal());
        $builder->build($actionInstance, (new ActionBuilderDummyEvent())->getFields());
    }

    /** @test */
    public function build_can_replace_multiple_event_fields_and_multiple_actions()
    {
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyActionMultiple::class, ['data' => [
            'action1' => 'field1value with field2valueFromEvent an event field of field1valueFromEvent field1valueFromEvent',
            'action2' => 'Val of field2valueFromEvent field1valueFromEvent'
        ]])->shouldBeCalled()->willReturn(new ActionBuilderDummyActionMultiple([]));

        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyActionMultiple::class,
            'event' => ActionBuilderDummyEvent::class
        ]);

        $actionInstanceField = factory(ActionInstanceField::class)->create([
            'action_value' => 'field1value with {{event:field2}} an event field of {{event:field1}} {{event:field1}}',
            'action_field' => 'action1',
            'action_instance_id' => $actionInstance->id
        ]);
        $actionInstanceField2 = factory(ActionInstanceField::class)->create([
            'action_value' => 'Val of {{event:field2}} {{event:field1}}',
            'action_field' => 'action2',
            'action_instance_id' => $actionInstance->id
        ]);

        $builder = new ActionBuilder($app->reveal());
        $builder->build($actionInstance, (new ActionBuilderDummyEvent())->getFields());
    }

    /** @test */
    public function it_sets_the_correct_information_for_history()
    {
        $actionInstance = factory(ActionInstance::class)->create([
            'action' => ActionBuilderDummyAction::class,
            'event' => ActionBuilderDummyEvent::class
        ]);
        
        $action = $this->prophesize(Action::class);
        $action->setActionInstanceId($actionInstance->id)->shouldBeCalled();
        $action->setEventFields(['key' => 'val1'])->shouldBeCalled();
        $action->setSettings([])->shouldBeCalled();
        
        $app = $this->prophesize(Application::class);
        $app->make(ActionBuilderDummyAction::class, ['data' => []])->shouldBeCalled()->willReturn($action->reveal());
        
        $builder = new ActionBuilder($app->reveal());
        $action = $builder->build($actionInstance, ['key' => 'val1']);

    }

}

class ActionBuilderDummyAction extends Action
{

    public function __construct(array $data)
    {
    }

    /**
     * @inheritDoc
     */
    public static function options(): Form
    {
        return new Form();
    }

    public function run(): ActionResponse
    {
        return ActionResponse::success();
    }
}

class ActionBuilderDummyActionMultiple extends Action
{

    public function __construct(array $data)
    {
    }

    /**
     * @inheritDoc
     */
    public static function options(): Form
    {
        return new Form();
    }

    public function run(): ActionResponse
    {
        return ActionResponse::success();
    }
}

class ActionBuilderDummyEvent implements TriggerableEvent
{

    public static function getFieldMetaData(): array
    {
        return [
            'field1' => [
                'label' => 'Field 1'
            ],
            'field2' => [
                'label' => 'Field 2'
            ]
        ];
    }

    public function getFields(): array
    {
        return ['field1' => 'field1valueFromEvent', 'field2' => 'field2valueFromEvent'];
    }
}