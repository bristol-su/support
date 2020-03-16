<?php


namespace BristolSU\Support\Tests\ModuleInstance\Evaluator;


use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Completion\CompletionConditionInstance;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceEvaluatorTest extends TestCase
{

    /** @test */
    public function admin_returns_an_evaluation_instance(){
        $moduleInstance = factory(ModuleInstance::class)->make();
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateAdministrator($moduleInstance));
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
        $moduleInstance = factory(ModuleInstance::class)->make();
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setActive(true)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();
        $this->app->instance(Evaluation::class, $evaluation->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $moduleInstanceEvaluator->evaluateAdministrator($moduleInstance);
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
        $this->app->instance(Evaluation::class, $evaluation->reveal());
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $this->logicTester()->forLogic($moduleInstance->visibleLogic)->pass($user, $group, $role);
        $this->logicTester()->forLogic($moduleInstance->mandatoryLogic)->pass($user, $group, $role);
        $this->logicTester()->forLogic($moduleInstance->activeLogic)->fail($user, $group, $role);
        $this->logicTester()->bind();
        
        $completionTester = $this->prophesize(CompletionConditionTester::class);
        $completionTester->evaluate(Argument::that(function($actInst) use ($activityInstance) {
            return $activityInstance->is($actInst);
        }), Argument::that(function($arg) use ($completionConditionInstance) {
            return $completionConditionInstance->is($arg);
        }))->shouldBeCalled()->willReturn(true);
        $this->app->instance(CompletionConditionTester::class, $completionTester->reveal());
        
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
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
        $this->app->instance(Evaluation::class, $evaluation->reveal());

        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $this->logicTester()->forLogic($moduleInstance->visibleLogic)->pass($user, $group, $role);
        $this->logicTester()->forLogic($moduleInstance->activeLogic)->fail($user, $group, $role);
        $this->logicTester()->bind();
        
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $user, $group, $role);
    }

    /** @test */
    public function participant_passes_the_user_group_and_role_to_the_tester(){
        $activity = factory(Activity::class)->create(['type' => 'open']);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $moduleInstance = factory(ModuleInstance::class)->make();
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);

        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function ($arg) use ($moduleInstance) {
            return $moduleInstance->visibleLogic->is($arg);
        }), $user, $group, $role)->shouldBeCalled()->willReturn(true);
        $logicTester->evaluate(Argument::that(function ($arg) use ($moduleInstance) {
            return $moduleInstance->activeLogic->is($arg);
        }), $user, $group, $role)->shouldBeCalled()->willReturn(true);
        $this->app->instance(LogicTester::class, $logicTester->reveal());
        
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $user, $group, $role);
    }

    /** @test */
    public function evaluateResource_returns_an_evaluation_contract(){
        $user = $this->newUser();
        $moduleInstance = factory(ModuleInstance::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create([
            'activity_id' => $moduleInstance->activity_id,
            'resource_id' => $user->id(),
            'resource_type' => 'user'
        ]);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::any(), Argument::any())->willReturn(collect());
        $this->instance(AudienceMemberFactory::class, $audienceMemberFactory->reveal());

        $evaluation = $this->prophesize(Evaluation::class);
        $this->app->instance(Evaluation::class, $evaluation->reveal());
        
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $this->assertEquals($evaluation->reveal(), $moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance));

    }
    
    /** @test */
    public function evaluateResource_sets_visible_mandatory_active_complete_attributes_to_true_if_audience_members_returned(){
        $user = $this->newUser();
        $completionCondition = factory(CompletionConditionInstance::class)->create();
        $activity = factory(Activity::class)->create(['type' => 'completable']);
        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'completion_condition_instance_id' => $completionCondition->id]);
        $audienceMembers = collect([$this->prophesize(AudienceMember::class)->reveal()]);
        $activityInstance = factory(ActivityInstance::class)->create([
            'activity_id' => $moduleInstance->activity_id,
            'resource_id' => $user->id(),
            'resource_type' => 'user'
        ]);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->visibleLogic->id === $arg->id;
        }))->shouldBeCalled()->willReturn($audienceMembers);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->activeLogic->id === $arg->id;
        }))->shouldBeCalled()->willReturn($audienceMembers);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->mandatoryLogic->id === $arg->id;
        }))->shouldBeCalled()->willReturn($audienceMembers);
        $this->instance(AudienceMemberFactory::class, $audienceMemberFactory->reveal());

        $completionTester = $this->prophesize(CompletionConditionTester::class);
        $completionTester->evaluate(Argument::that(function($arg) use($activityInstance) {
            return $arg instanceof ActivityInstance && $activityInstance->id === $arg->id;
        }), Argument::that(function($arg) use($completionCondition) {
            return $arg instanceof CompletionConditionInstance && $completionCondition->id === $arg->id;
        }))->shouldBeCalled()->willReturn(true);
        $this->instance(CompletionConditionTester::class, $completionTester->reveal());
        
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setActive(true)->shouldBeCalled();
        $evaluation->setMandatory(true)->shouldBeCalled();
        $evaluation->setComplete(true)->shouldBeCalled();
        $this->app->instance(Evaluation::class, $evaluation->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance);

    }

    /** @test */
    public function evaluateResource_sets_visible_mandatory_active_complete_attributes_to_false_if_audience_members_not_returned(){
        $user = $this->newUser();
        $completionCondition = factory(CompletionConditionInstance::class)->create();
        $activity = factory(Activity::class)->create(['type' => 'completable']);
        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'completion_condition_instance_id' => $completionCondition->id]);
        $audienceMembers = collect();
        $activityInstance = factory(ActivityInstance::class)->create([
            'activity_id' => $moduleInstance->activity_id,
            'resource_id' => $user->id(),
            'resource_type' => 'user'
        ]);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->visibleLogic->id === $arg->id;
        }))->shouldBeCalled()->willReturn($audienceMembers);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->activeLogic->id === $arg->id;
        }))->shouldBeCalled()->willReturn($audienceMembers);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->mandatoryLogic->id === $arg->id;
        }))->shouldBeCalled()->willReturn($audienceMembers);
        $this->instance(AudienceMemberFactory::class, $audienceMemberFactory->reveal());

        $completionTester = $this->prophesize(CompletionConditionTester::class);
        $completionTester->evaluate(Argument::that(function($arg) use($activityInstance) {
            return $arg instanceof ActivityInstance && $activityInstance->id === $arg->id;
        }), Argument::that(function($arg) use($completionCondition) {
            return $arg instanceof CompletionConditionInstance && $completionCondition->id === $arg->id;
        }))->shouldBeCalled()->willReturn(false);
        $this->instance(CompletionConditionTester::class, $completionTester->reveal());

        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(false)->shouldBeCalled();
        $evaluation->setActive(false)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();
        $this->app->instance(Evaluation::class, $evaluation->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance);

    }
    
    /** @test */
    public function evaluateResource_sets_mandatory_and_complete_to_false_if_a_non_completable_activity(){
        $user = $this->newUser();
        $completionCondition = factory(CompletionConditionInstance::class)->create();
        $activity = factory(Activity::class)->create(['type' => 'open']);
        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity->id, 'completion_condition_instance_id' => $completionCondition->id]);
        $audienceMembers = collect([$this->prophesize(AudienceMember::class)->reveal()]);
        $activityInstance = factory(ActivityInstance::class)->create([
            'activity_id' => $moduleInstance->activity_id,
            'resource_id' => $user->id(),
            'resource_type' => 'user'
        ]);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->visibleLogic->id === $arg->id;
        }))->shouldBeCalled()->willReturn(collect());
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->activeLogic->id === $arg->id;
        }))->shouldBeCalled()->willReturn(collect());
        $audienceMemberFactory->withAccessToLogicGroupWithResource(Argument::that(function($arg) use($user) {
            return $arg instanceof User && $user->id() === $arg->id();
        }), Argument::that(function($arg) use($moduleInstance) {
            return $arg instanceof Logic && $moduleInstance->mandatoryLogic->id === $arg->id;
        }))->shouldNotBeCalled();
        $this->instance(AudienceMemberFactory::class, $audienceMemberFactory->reveal());

        $completionTester = $this->prophesize(CompletionConditionTester::class);
        $completionTester->evaluate(Argument::that(function($arg) use($activityInstance) {
            return $arg instanceof ActivityInstance && $activityInstance->id === $arg->id;
        }), Argument::that(function($arg) use($completionCondition) {
            return $arg instanceof CompletionConditionInstance && $completionCondition->id === $arg->id;
        }))->shouldNotBeCalled();
        $this->instance(CompletionConditionTester::class, $completionTester->reveal());

        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(false)->shouldBeCalled();
        $evaluation->setActive(false)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();
        $this->app->instance(Evaluation::class, $evaluation->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance);

    }

}
