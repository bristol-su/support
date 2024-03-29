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
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class ModuleInstanceEvaluatorTest extends TestCase
{
    /** @test */
    public function admin_returns_an_evaluation_instance()
    {
        $moduleInstance = ModuleInstance::factory()->make();
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateAdministrator($moduleInstance));
    }

    /** @test */
    public function participant_returns_an_evaluation_instance()
    {
        $activityInstance = ActivityInstance::factory()->create();
        $moduleInstance = ModuleInstance::factory()->make();
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal(), $this->prophesize(Authentication::class)->reveal());
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $this->newUser()));
    }

    /** @test */
    public function admin_passes_the_correct_data_to_an_evaluation()
    {
        $moduleInstance = ModuleInstance::factory()->make();
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setActive(true)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();
        $evaluation->setPercentage(0)->shouldBeCalled();
        $this->app->instance(Evaluation::class, $evaluation->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $moduleInstanceEvaluator->evaluateAdministrator($moduleInstance);
    }

    /** @test */
    public function participant_passes_the_correct_data_to_an_evaluation_for_a_completable_activity()
    {
        $activity = Activity::factory()->create(['type' => 'completable']);
        $completionConditionInstance = CompletionConditionInstance::factory()->create();
        $activityInstance = ActivityInstance::factory()->create(['activity_id' => $activity->id]);
        $moduleInstance = ModuleInstance::factory()->make(['completion_condition_instance_id' => $completionConditionInstance->id]);
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(true)->shouldBeCalled();
        $evaluation->setActive(false)->shouldBeCalled();
        $evaluation->setComplete(true)->shouldBeCalled();
        $evaluation->setPercentage(100)->shouldBeCalled();
        $this->app->instance(Evaluation::class, $evaluation->reveal());
        $user = $this->newUser();
        $group = $this->newGroup();
        $role = $this->newRole();

        $this->logicTester()->forLogic($moduleInstance->visibleLogic)->pass($user, $group, $role);
        $this->logicTester()->forLogic($moduleInstance->mandatoryLogic)->pass($user, $group, $role);
        $this->logicTester()->forLogic($moduleInstance->activeLogic)->fail($user, $group, $role);
        $this->logicTester()->bind();

        $completionTester = $this->prophesize(CompletionConditionTester::class);
        $completionTester->evaluate(Argument::that(function ($actInst) use ($activityInstance) {
            return $activityInstance->is($actInst);
        }), Argument::that(function ($arg) use ($completionConditionInstance) {
            return $completionConditionInstance->is($arg);
        }))->shouldBeCalled()->willReturn(true);
        $completionTester->evaluatePercentage(Argument::that(function ($actInst) use ($activityInstance) {
            return $activityInstance->is($actInst);
        }), Argument::that(function ($arg) use ($completionConditionInstance) {
            return $completionConditionInstance->is($arg);
        }))->shouldBeCalled()->willReturn(100);
        $this->app->instance(CompletionConditionTester::class, $completionTester->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $user, $group, $role);
    }

    /** @test */
    public function participant_passes_the_correct_data_to_an_evaluation_for_an_open_activity()
    {
        $activity = Activity::factory()->create(['type' => 'open']);
        $activityInstance = ActivityInstance::factory()->create(['activity_id' => $activity->id]);
        $moduleInstance = ModuleInstance::factory()->make();
        $activityInstance->activity->moduleInstances()->save($moduleInstance);
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setActive(false)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();
        $evaluation->setPercentage(0)->shouldBeCalled();
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
    public function participant_passes_the_user_group_and_role_to_the_tester()
    {
        $activity = Activity::factory()->create(['type' => 'open']);
        $activityInstance = ActivityInstance::factory()->create(['activity_id' => $activity->id]);
        $moduleInstance = ModuleInstance::factory()->make();
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
    public function evaluate_resource_returns_an_evaluation_contract()
    {
        $user = $this->newUser();
        $moduleInstance = ModuleInstance::factory()->create();
        $activityInstance = ActivityInstance::factory()->create([
            'activity_id' => $moduleInstance->activity_id,
            'resource_id' => $user->id(),
            'resource_type' => 'user'
        ]);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->audience(Argument::type(Logic::class), Argument::type(User::class), null, null)->willReturn(collect());
        $this->instance(AudienceMemberFactory::class, $audienceMemberFactory->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $evaluation = $moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance);
        $this->assertFalse($evaluation->visible());
        $this->assertFalse($evaluation->active());
        $this->assertFalse($evaluation->mandatory());
    }

    /** @test */
    public function evaluate_resource_sets_visible_mandatory_active_complete_percentage_attributes_to_true_if_audience_members_returned()
    {
        $user = $this->newUser();
        $completionCondition = CompletionConditionInstance::factory()->create();
        $activity = Activity::factory()->create(['type' => 'completable']);
        $moduleInstance = ModuleInstance::factory()->create(['activity_id' => $activity->id, 'completion_condition_instance_id' => $completionCondition->id]);
        $activityInstance = ActivityInstance::factory()->create([
            'activity_id' => $moduleInstance->activity_id,
            'resource_id' => $user->id(),
            'resource_type' => 'user'
        ]);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->audience(Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($moduleInstance->visibleLogic)), Argument::type(User::class), null, null)->willReturn(collect());
        $audienceMemberFactory->audience(Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($moduleInstance->activeLogic)), Argument::type(User::class), null, null)->willReturn(collect(new AudienceMember($user)));
        $audienceMemberFactory->audience(Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($moduleInstance->mandatoryLogic)), Argument::type(User::class), null, null)->willReturn(collect(new AudienceMember($user)));
        $this->instance(AudienceMemberFactory::class, $audienceMemberFactory->reveal());

        $completionTester = $this->prophesize(CompletionConditionTester::class);
        $completionTester->evaluate(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $activityInstance->id === $arg->id;
        }), Argument::that(function ($arg) use ($completionCondition) {
            return $arg instanceof CompletionConditionInstance && $completionCondition->id === $arg->id;
        }))->shouldBeCalled()->willReturn(true);
        $completionTester->evaluatePercentage(
            Argument::that(fn ($arg) => $arg instanceof ActivityInstance && $activityInstance->id === $arg->id),
            Argument::that(fn ($arg) =>  $arg instanceof CompletionConditionInstance && $completionCondition->id === $arg->id)
        )->shouldBeCalled()->willReturn(100);
        $this->instance(CompletionConditionTester::class, $completionTester->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $evaluation = $moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance);
        $this->assertFalse($evaluation->visible());
        $this->assertTrue($evaluation->active());
        $this->assertTrue($evaluation->mandatory());
        $this->assertTrue($evaluation->complete());
        $this->assertEquals(100, $evaluation->percentage());

    }

    /** @test */
    public function evaluate_resource_sets_mandatory_and_complete_to_false_if_a_non_completable_activity()
    {
        $user = $this->newUser();
        $completionCondition = CompletionConditionInstance::factory()->create();
        $activity = Activity::factory()->create(['type' => 'open']);
        $moduleInstance = ModuleInstance::factory()->create(['activity_id' => $activity->id, 'completion_condition_instance_id' => $completionCondition->id]);
        $activityInstance = ActivityInstance::factory()->create([
            'activity_id' => $moduleInstance->activity_id,
            'resource_id' => $user->id(),
            'resource_type' => 'user'
        ]);

        $audienceMemberFactory = $this->prophesize(AudienceMemberFactory::class);
        $audienceMemberFactory->audience(Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($moduleInstance->visibleLogic)), Argument::type(User::class), null, null)->willReturn(collect());
        $audienceMemberFactory->audience(Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($moduleInstance->activeLogic)), Argument::type(User::class), null, null)->willReturn(collect(new AudienceMember($user)));
        $audienceMemberFactory->audience(Argument::that(fn($arg) => $arg instanceof Logic && $arg->is($moduleInstance->mandatoryLogic)), Argument::type(User::class), null, null)->shouldNotBeCalled();
        $this->instance(AudienceMemberFactory::class, $audienceMemberFactory->reveal());

        $completionTester = $this->prophesize(CompletionConditionTester::class);
        $completionTester->evaluate(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $activityInstance->id === $arg->id;
        }), Argument::that(function ($arg) use ($completionCondition) {
            return $arg instanceof CompletionConditionInstance && $completionCondition->id === $arg->id;
        }))->shouldNotBeCalled();
        $completionTester->evaluatePercentage(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $activityInstance->id === $arg->id;
        }), Argument::that(function ($arg) use ($completionCondition) {
            return $arg instanceof CompletionConditionInstance && $completionCondition->id === $arg->id;
        }))->shouldNotBeCalled();
        $this->instance(CompletionConditionTester::class, $completionTester->reveal());

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator();
        $evaluation = $moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance);
        $this->assertFalse($evaluation->visible());
        $this->assertTrue($evaluation->active());
        $this->assertFalse($evaluation->mandatory());
        $this->assertFalse($evaluation->complete());
        $this->assertEquals(0, $evaluation->percentage());
    }
}
