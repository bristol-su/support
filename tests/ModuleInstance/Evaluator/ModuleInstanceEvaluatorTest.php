<?php


namespace BristolSU\Support\Tests\ModuleInstance\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Completion\CompletionConditionInstance;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\Tests\Logic\LogicTest;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceEvaluatorTest extends TestCase
{

    /** @test */
    public function admin_returns_an_evaluation_instance(){
        $activityInstance = factory(ActivityInstance::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->make();
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal());
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateAdministrator($activityInstance, $moduleInstance));
    }


    /** @test */
    public function participant_returns_an_evaluation_instance(){
        $activityInstance = factory(ActivityInstance::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->make();
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal(), $this->prophesize(Authentication::class)->reveal());
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance));
    }


    /** @test */
    public function admin_passes_the_correct_data_to_an_evaluation(){
        $activityInstance = factory(ActivityInstance::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->make();
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setActive(true)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal(), $this->prophesize(Authentication::class)->reveal());
        $moduleInstanceEvaluator->evaluateAdministrator($activityInstance, $moduleInstance);
    }

    /** @test */
    public function participant_passes_the_correct_data_to_an_evaluation_for_a_completable_activity(){
        $activity = factory(Activity::class)->create(['type' => 'completable']);
        $completionConditionInstance = factory(CompletionConditionInstance::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $moduleInstance = factory(ModuleInstance::class)->make(['completion_condition_instance_id' => $completionConditionInstance->id]);
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(true)->shouldBeCalled();
        $evaluation->setActive(false)->shouldBeCalled();
        $evaluation->setComplete(true)->shouldBeCalled();
        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);
        
        $this->createLogicTester([$moduleInstance->visibleLogic, $moduleInstance->mandatoryLogic], $moduleInstance->activeLogic, $user, $group, $role);
        $completionTester = $this->prophesize(CompletionConditionTester::class);
        $completionTester->evaluate(Argument::that(function($actInst) use ($activityInstance) {
            return $activityInstance->is($actInst);
        }), Argument::that(function($arg) use ($completionConditionInstance) {
            return $completionConditionInstance->is($arg);
        }))->shouldBeCalled()->willReturn(true);
        $this->app->instance(CompletionConditionTester::class, $completionTester->reveal());
        
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal());
        $moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $user, $group, $role);
    }

    /** @test */
    public function participant_passes_the_correct_data_to_an_evaluation_for_an_open_activity(){
        $activity = factory(Activity::class)->create(['type' => 'open']);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $moduleInstance = factory(ModuleInstance::class)->make();
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setActive(false)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();

        $user = new User(['id' => 1]);
        $group = new Group(['id' => 2]);
        $role = new Role(['id' => 3]);

        
        $this->createLogicTester([$moduleInstance->visibleLogic], $moduleInstance->activeLogic, $user, $group, $role);
        
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal());
        $moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $user, $group, $role);
    }

    /** @test */
    public function participant_passes_the_user_group_and_role_to_the_tester(){
        $activity = factory(Activity::class)->create(['type' => 'open']);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $moduleInstance = factory(ModuleInstance::class)->make();
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);

        $user = new User(['id' => 1]);
        $group = new Group(['id' => 1]);
        $role = new Role(['id' => 2]);
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function ($arg) use ($moduleInstance) {
            return $moduleInstance->visibleLogic->is($arg);
        }), $user, $group, $role)->shouldBeCalled()->willReturn(true);
        $logicTester->evaluate(Argument::that(function ($arg) use ($moduleInstance) {
            return $moduleInstance->activeLogic->is($arg);
        }), $user, $group, $role)->shouldBeCalled()->willReturn(true);
        $this->app->instance(LogicTester::class, $logicTester->reveal());
        
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal());
        $moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $user, $group, $role);
    }
}
