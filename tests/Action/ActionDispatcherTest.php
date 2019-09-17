<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionDispatcher;
use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Action\Contracts\ActionBuilder;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use Prophecy\Argument;

class ActionDispatcherTest extends TestCase
{

    /** @test */
    public function handle_builds_each_relevant_action(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->instance(ModuleInstance::class, $moduleInstance);
        $actionInstances = factory(ActionInstance::class, 4)->create([
            'module_instance_id' => $moduleInstance->id,
            'event' => DispatcherDummyEvent::class
        ]);
        
        $builder = $this->prophesize(ActionBuilder::class);
        foreach($actionInstances as $actionInstance) {
            $builder->build(Argument::that(function($arg) use ($actionInstance) {
                return $arg->id === $actionInstance->id;
            }), ['field1' => 'field1value'])->shouldBeCalled()->willReturn(new DispatcherDummyAction([]));
        }
        
        $actionDispatcher = new ActionDispatcher($builder->reveal());
        $actionDispatcher->handle(new DispatcherDummyEvent([]));
    }
    
    /** @test */
    public function handle_dispatches_a_job_for_each_relevant_action(){
        Bus::fake();
        $moduleInstance = factory(ModuleInstance::class)->create();
        $this->instance(ModuleInstance::class, $moduleInstance);
        $actionInstances = factory(ActionInstance::class, 4)->create([
            'module_instance_id' => $moduleInstance->id,
            'event' => DispatcherDummyEvent::class
        ]);

        $builder = $this->prophesize(ActionBuilder::class);
        foreach($actionInstances as $actionInstance) {
            $builder->build(Argument::any(), Argument::any())->shouldBeCalled()->willReturn(new DispatcherDummyAction([]));
        }

        $actionDispatcher = new ActionDispatcher($builder->reveal());
        $actionDispatcher->handle(new DispatcherDummyEvent([]));
        
        Bus::assertDispatched(DispatcherDummyAction::class, $actionInstances->count());
    }
    
    
}
class DispatcherDummyEvent implements TriggerableEvent
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

class DispatcherDummyAction implements Action
{

    public function handle()
    {
    }

    public function getFields(): array
    {
    }

    public static function getFieldMetaData(): array
    {
    }

    public function __construct(array $data)
    {
    }
}