<?php

namespace BristolSU\Support\Tests\Completion;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\CompletionConditionInstance;
use BristolSU\Support\Completion\CompletionConditionRepository;
use BristolSU\Support\Completion\CompletionConditionTester;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class CompletionConditionTesterTest extends TestCase
{

    /** @test */
    public function it_tests_the_completion_condition()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $completionConditionInstance = factory(CompletionConditionInstance::class)->create([
            'settings' => ['setting1' => 'val1'],
            'alias' => 'ccalias1'
        ]);
        $moduleInstance = factory(ModuleInstance::class)->create([
            'activity_id' => $activityInstance->activity_id,
            'completion_condition_instance_id' => $completionConditionInstance->id
        ]);
        $completionCondition = $this->prophesize(CompletionCondition::class);
        $completionCondition->isComplete(['setting1' => 'val1'], Argument::that(function ($arg) use ($activityInstance) {
            return $activityInstance->is($arg);
        }), Argument::that(function ($arg) use ($moduleInstance) {
            return $moduleInstance->is($arg);
        }))->shouldBeCalled()->willReturn(true);

        $repository = $this->prophesize(CompletionConditionRepository::class);
        $repository->getByAlias($moduleInstance->alias, 'ccalias1')->willReturn($completionCondition->reveal());


        $tester = new CompletionConditionTester($repository->reveal());
        $tester->evaluate($activityInstance, $completionConditionInstance);
    }

    /** @test */
    public function it_returns_true_if_the_completion_condition_is_true()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $completionConditionInstance = factory(CompletionConditionInstance::class)->create([
            'settings' => ['setting1' => 'val1'],
            'alias' => 'ccalias1'
        ]);
        
        $moduleInstance = factory(ModuleInstance::class)->create([
            'activity_id' => $activityInstance->activity_id,
            'completion_condition_instance_id' => $completionConditionInstance->id
        ]);

        $completionCondition = $this->prophesize(CompletionCondition::class);
        $completionCondition->isComplete(['setting1' => 'val1'], Argument::that(function ($arg) use ($activityInstance) {
            return $activityInstance->is($arg);
        }), Argument::that(function ($arg) use ($moduleInstance) {
            return $moduleInstance->is($arg);
        }))->shouldBeCalled()->willReturn(true);

        $repository = $this->prophesize(CompletionConditionRepository::class);
        $repository->getByAlias($moduleInstance->alias, 'ccalias1')->willReturn($completionCondition->reveal());

        $tester = new CompletionConditionTester($repository->reveal());
        $this->assertTrue(
            $tester->evaluate($activityInstance, $completionConditionInstance)
        );
    }

    /** @test */
    public function it_returns_false_if_the_completion_condition_is_false()
    {
        $activityInstance = factory(ActivityInstance::class)->create();
        $completionConditionInstance = factory(CompletionConditionInstance::class)->create([
            'settings' => ['setting1' => 'val1'],
            'alias' => 'ccalias1'
        ]);
        $moduleInstance = factory(ModuleInstance::class)->create([
            'activity_id' => $activityInstance->activity_id,
            'completion_condition_instance_id' => $completionConditionInstance->id
        ]);

        $completionCondition = $this->prophesize(CompletionCondition::class);
        $completionCondition->isComplete(['setting1' => 'val1'], Argument::that(function ($arg) use ($activityInstance) {
            return $activityInstance->is($arg);
        }), Argument::that(function ($arg) use ($moduleInstance) {
            return $moduleInstance->is($arg);
        }))->shouldBeCalled()->willReturn(false);

        $repository = $this->prophesize(CompletionConditionRepository::class);
        $repository->getByAlias($moduleInstance->alias, 'ccalias1')->willReturn($completionCondition->reveal());

        $tester = new CompletionConditionTester($repository->reveal());
        $this->assertFalse(
            $tester->evaluate($activityInstance, $completionConditionInstance)
        );
    }

}