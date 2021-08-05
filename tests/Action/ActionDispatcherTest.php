<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionDispatcher;
use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Action\Contracts\ActionBuilder;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Testing\Fakes\BusFake;
use PHPUnit\Framework\Assert as PHPUnit;
use Prophecy\Argument;

class ActionDispatcherTest extends TestCase
{
    /** @test */
    public function handle_builds_each_relevant_action()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $this->instance(ModuleInstance::class, $moduleInstance);
        $actionInstances = ActionInstance::factory()->count(4)->create([
            'module_instance_id' => $moduleInstance->id,
            'event' => DispatcherDummyEvent::class
        ]);

        $builder = $this->prophesize(ActionBuilder::class);
        foreach ($actionInstances as $actionInstance) {
            $builder->build(Argument::that(function ($arg) use ($actionInstance) {
                return $arg->id === $actionInstance->id;
            }), ['field1' => 'field1value'])->shouldBeCalled()->willReturn(new DispatcherDummyAction([]));
        }

        $actionDispatcher = new ActionDispatcher($builder->reveal());
        $actionDispatcher->handle(new DispatcherDummyEvent([]));
    }

    /** @test */
    public function handle_dispatches_a_job_for_each_relevant_action()
    {
        Bus::swap($fake = new BusFakeWithDispatchNow(Bus::getFacadeRoot()));

        $moduleInstance = ModuleInstance::factory()->create();
        $this->instance(ModuleInstance::class, $moduleInstance);
        $actionInstances = ActionInstance::factory()->count(4)->create([
            'module_instance_id' => $moduleInstance->id,
            'event' => DispatcherDummyEvent::class
        ]);

        $builder = $this->prophesize(ActionBuilder::class);
        foreach ($actionInstances as $actionInstance) {
            $builder->build(Argument::any(), Argument::any())->shouldBeCalled()->willReturn(new DispatcherDummyAction([]));
        }

        $actionDispatcher = new ActionDispatcher($builder->reveal());
        $actionDispatcher->handle(new DispatcherDummyEvent([]));

        Bus::assertDispatched(DispatcherDummyAction::class, $actionInstances->count());
    }

    /** @test */
    public function it_dispatches_now_if_should_queue_is_false()
    {
        Bus::swap($fake = new BusFakeWithDispatchNow(Bus::getFacadeRoot()));
        $moduleInstance = ModuleInstance::factory()->create();
        $this->instance(ModuleInstance::class, $moduleInstance);
        $actionInstances = ActionInstance::factory()->count(4)->create([
            'module_instance_id' => $moduleInstance->id,
            'event' => DispatcherDummyEvent::class,
            'should_queue' => false
        ]);

        $builder = $this->prophesize(ActionBuilder::class);
        foreach ($actionInstances as $actionInstance) {
            $builder->build(Argument::any(), Argument::any())->shouldBeCalled()->willReturn(new DispatcherDummyAction([]));
        }

        $actionDispatcher = new ActionDispatcher($builder->reveal());
        $actionDispatcher->handle(new DispatcherDummyEvent([]));

        Bus::assertDispatchedNow(DispatcherDummyAction::class, $actionInstances->count());
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

class DispatcherDummyAction extends Action
{
    public function run(): ActionResponse
    {
        return ActionResponse::success();
    }

    /**
     * @inheritDoc
     */
    public static function options(): Form
    {
        return new Form();
    }

    public function __construct(array $data)
    {
        $this->setActionInstanceId(1);
    }
}

class BusFakeWithDispatchNow extends BusFake
{
    protected $nowCommands;

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param  mixed  $command
     * @param  mixed  $handler
     * @return mixed
     */
    public function dispatchNow($command, $handler = null)
    {
        if ($this->shouldFakeJob($command)) {
            $this->nowCommands[get_class($command)][] = $command;
        } else {
            return $this->dispatcher->dispatchNow($command, $handler);
        }
    }

    /**
     * Assert if a job was dispatched based on a truth-test callback.
     *
     * @param  string  $command
     * @param  callable|int|null  $callback
     */
    public function assertDispatchedNow($command, $callback = null)
    {
        if (is_numeric($callback)) {
            return $this->assertDispatchedTimesNow($command, $callback);
        }

        PHPUnit::assertTrue(
            $this->dispatchedNow($command, $callback)->count() > 0,
            "The expected [{$command}] job was not dispatched."
        );
    }

    /**
     * Assert if a job was pushed a number of times.
     *
     * @param  string  $command
     * @param  int  $times
     */
    public function assertDispatchedTimesNow($command, $times = 1)
    {
        $count = $this->dispatchedNow($command)->count();

        PHPUnit::assertTrue(
            $count === $times,
            "The expected [{$command}] job was pushed {$count} times instead of {$times} times."
        );
    }

    /**
     * Get all of the jobs matching a truth-test callback.
     *
     * @param  string  $command
     * @param  callable|null  $callback
     * @return \Illuminate\Support\Collection
     */
    public function dispatchedNow($command, $callback = null)
    {
        if (! $this->hasDispatchedNow($command)) {
            return collect();
        }

        $callback = $callback ?: function () {
            return true;
        };

        return collect($this->nowCommands[$command])->filter(function ($command) use ($callback) {
            return $callback($command);
        });
    }

    /**
     * Determine if there are any stored commands for a given class.
     *
     * @param  string  $command
     * @return bool
     */
    public function hasDispatchedNow($command)
    {
        return isset($this->nowCommands[$command]) && ! empty($this->nowCommands[$command]);
    }
}
