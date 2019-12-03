<?php


namespace BristolSU\Support\Tests\ModuleInstance\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\Evaluator\ActivityInstanceEvaluator;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Support\Collection;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class ActivityInstanceEvaluatorTest extends TestCase
{

    /** @test */
    public function participant_evaluates_each_module_instance()
    {
        $moduleInstances = factory(ModuleInstance::class, 3)->make();
        $activity = factory(Activity::class)->create();
        $activity->moduleInstances()->saveMany($moduleInstances);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);
        
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
    public
    function administrator_evaluates_each_module_instance()
    {
        $moduleInstances = factory(ModuleInstance::class, 3)->make();
        $activity = factory(Activity::class)->create();
        $activity->moduleInstances()->saveMany($moduleInstances);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);
        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $evaluation = $this->prophesize(Evaluation::class);
        foreach ($moduleInstances as $moduleInstance) {
            $moduleInstanceEvaluator->evaluateAdministrator(Argument::that(function ($givenActInst) use ($activityInstance) {
                return $activityInstance->is($givenActInst);
            }), Argument::that(function ($givenModuleInstance) use ($moduleInstance) {
                return $givenModuleInstance->id == $moduleInstance->id;
            }), $user, $group, $role)->shouldBeCalled()->willReturn($evaluation);
        }

        $activityEvaluator = new ActivityInstanceEvaluator($moduleInstanceEvaluator->reveal());
        $evaluations = $activityEvaluator->evaluateAdministrator($activityInstance, $user, $group, $role);

        $this->assertCount($moduleInstances->count(), $evaluations);
        foreach ($evaluations as $id => $evaluation) {
            $this->assertInstanceOf(Evaluation::class, $evaluation);
            $this->assertEquals($id, $moduleInstances->shift()->id);
        }
    }


}
