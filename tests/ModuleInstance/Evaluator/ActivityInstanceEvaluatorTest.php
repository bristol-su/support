<?php


namespace BristolSU\Support\Tests\ModuleInstance\Evaluator;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\Evaluator\ActivityInstanceEvaluator;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class ActivityInstanceEvaluatorTest extends TestCase
{
    /** @test */
    public function participant_evaluates_each_module_instance()
    {
        $moduleInstances = ModuleInstance::factory()->count(3)->make();
        $activity = Activity::factory()->create();
        $activity->moduleInstances()->saveMany($moduleInstances);
        $activityInstance = ActivityInstance::factory()->create(['activity_id' => $activity->id]);

        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $evaluation = $this->prophesize(Evaluation::class);
        foreach ($moduleInstances as $moduleInstance) {
            $moduleInstanceEvaluator->evaluateParticipant(Argument::that(function ($givenActInst) use ($activityInstance) {
                return $activityInstance->is($givenActInst);
            }), Argument::that(function ($givenModuleInstance) use ($moduleInstance) {
                return $givenModuleInstance->id == $moduleInstance->id;
            }), $user, $group, $role)->shouldBeCalled()->willReturn($evaluation);
        }

        $activityEvaluator = new ActivityInstanceEvaluator($moduleInstanceEvaluator->reveal());
        $evaluations = $activityEvaluator->evaluateParticipant($activityInstance, $user, $group, $role);

        $this->assertCount($moduleInstances->count(), $evaluations);
        foreach ($evaluations as $id => $evaluation) {
            $this->assertInstanceOf(Evaluation::class, $evaluation);
            $this->assertEquals($id, $moduleInstances->shift()->id);
        }
    }

    /** @test */
    public function resource_evaluates_each_module_instance()
    {
        $moduleInstances = ModuleInstance::factory()->count(3)->make();
        $activity = Activity::factory()->create();
        $activity->moduleInstances()->saveMany($moduleInstances);
        $activityInstance = ActivityInstance::factory()->create(['activity_id' => $activity->id]);
        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $evaluation = $this->prophesize(Evaluation::class);
        foreach ($moduleInstances as $moduleInstance) {
            $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($givenActInst) use ($activityInstance) {
                return $activityInstance->is($givenActInst);
            }), Argument::that(function ($givenModuleInstance) use ($moduleInstance) {
                return $givenModuleInstance->id == $moduleInstance->id;
            }))->shouldBeCalled()->willReturn($evaluation);
        }

        $activityEvaluator = new ActivityInstanceEvaluator($moduleInstanceEvaluator->reveal());
        $evaluations = $activityEvaluator->evaluateResource($activityInstance);

        $this->assertCount($moduleInstances->count(), $evaluations);
        foreach ($evaluations as $id => $evaluation) {
            $this->assertInstanceOf(Evaluation::class, $evaluation);
            $this->assertEquals($id, $moduleInstances->shift()->id);
        }
    }
}
