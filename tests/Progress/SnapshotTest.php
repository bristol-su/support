<?php

namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Progress\ProgressUpdateRepository;
use BristolSU\Support\Progress\Snapshot;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Prophecy\Argument;

class SnapshotTest extends TestCase
{
    /** @test */
    public function of_activity_instance_returns_no_modules_if_no_modules_created()
    {
        $activity = factory(Activity::class)->create();
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $this->assertCount(0, $progress->getModules());
    }
    
    /** @test */
    public function of_activity_instance_builds_a_module_instance_progress_for_each_module()
    {
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 5)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        
        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $this->assertCount(5, $progress->getModules());
        $this->assertContainsOnlyInstancesOf(ModuleInstanceProgress::class, $progress->getModules());
    }

    /** @test */
    public function ofActivityInstance_builds_the_progress_correctly_excluding_completion_and_percentage()
    {
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 5)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $this->assertEquals($activityInstance->id, $progress->getActivityInstanceId());
        $this->assertEquals($activity->id, $progress->getActivityId());
        $this->assertEquals(Carbon::now(), $progress->getTimestamp());
    }

    /** @test */
    public function of_activity_instance_builds_the_module_instance_progresses_correctly()
    {
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 2)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $module1Evaluation = new Evaluation();
        $module2Evaluation = new Evaluation();
        $module1Evaluation->setActive(true);
        $module2Evaluation->setActive(false);
        $module1Evaluation->setVisible(true);
        $module2Evaluation->setVisible(false);
        $module1Evaluation->setComplete(true);
        $module2Evaluation->setComplete(false);
        $module1Evaluation->setMandatory(false);
        $module2Evaluation->setMandatory(true);
        $module1Evaluation->setPercentage(100);
        $module2Evaluation->setPercentage(10);
        
        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[0]);
        }))->shouldBeCalled()->willReturn($module1Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[1]);
        }))->shouldBeCalled()->willReturn($module2Evaluation);

        \BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator::swap($moduleInstanceEvaluator->reveal());
        
        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $module1Progress = $progress->getModules()[0];
        $this->assertTrue($module1Progress->isActive());
        $this->assertTrue($module1Progress->isVisible());
        $this->assertTrue($module1Progress->isComplete());
        $this->assertFalse($module1Progress->isMandatory());
        $this->assertEquals(100, $module1Progress->getPercentage());
        $module2Progress = $progress->getModules()[1];
        $this->assertFalse($module2Progress->isActive());
        $this->assertFalse($module2Progress->isVisible());
        $this->assertFalse($module2Progress->isComplete());
        $this->assertTrue($module2Progress->isMandatory());
        $this->assertEquals(10, $module2Progress->getPercentage());
    }

    /** @test */
    public function of_activity_instance_sets_complete_to_true_if_all_modules_are_mandatory_and_complete()
    {
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 3)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $module1Evaluation = new Evaluation();
        $module2Evaluation = new Evaluation();
        $module3Evaluation = new Evaluation();
        $module1Evaluation->setActive(true);
        $module2Evaluation->setActive(false);
        $module3Evaluation->setActive(false);
        $module1Evaluation->setVisible(true);
        $module2Evaluation->setVisible(false);
        $module3Evaluation->setVisible(false);
        $module1Evaluation->setComplete(true);
        $module2Evaluation->setComplete(true);
        $module3Evaluation->setComplete(true);
        $module1Evaluation->setMandatory(true);
        $module2Evaluation->setMandatory(true);
        $module3Evaluation->setMandatory(true);
        $module1Evaluation->setPercentage(100);
        $module2Evaluation->setPercentage(10);
        $module3Evaluation->setPercentage(10);

        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[0]);
        }))->shouldBeCalled()->willReturn($module1Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[1]);
        }))->shouldBeCalled()->willReturn($module2Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[2]);
        }))->shouldBeCalled()->willReturn($module3Evaluation);

        \BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator::swap($moduleInstanceEvaluator->reveal());

        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $this->assertTrue($progress->isComplete());
    }

    /** @test */
    public function of_activity_instance_sets_complete_to_false_if_all_modules_are_mandatory_but_one_is_not_complete()
    {
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 3)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $module1Evaluation = new Evaluation();
        $module2Evaluation = new Evaluation();
        $module3Evaluation = new Evaluation();
        $module1Evaluation->setActive(true);
        $module2Evaluation->setActive(false);
        $module3Evaluation->setActive(false);
        $module1Evaluation->setVisible(true);
        $module2Evaluation->setVisible(false);
        $module3Evaluation->setVisible(false);
        $module1Evaluation->setComplete(true);
        $module2Evaluation->setComplete(true);
        $module3Evaluation->setComplete(false);
        $module1Evaluation->setMandatory(true);
        $module2Evaluation->setMandatory(true);
        $module3Evaluation->setMandatory(true);
        $module1Evaluation->setPercentage(100);
        $module2Evaluation->setPercentage(10);
        $module3Evaluation->setPercentage(10);

        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[0]);
        }))->shouldBeCalled()->willReturn($module1Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[1]);
        }))->shouldBeCalled()->willReturn($module2Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[2]);
        }))->shouldBeCalled()->willReturn($module3Evaluation);

        \BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator::swap($moduleInstanceEvaluator->reveal());

        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $this->assertFalse($progress->isComplete());
    }

    /** @test */
    public function of_activity_instance_sets_complete_to_true_if_all_mandatory_modules_are_complete()
    {
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 3)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $module1Evaluation = new Evaluation();
        $module2Evaluation = new Evaluation();
        $module3Evaluation = new Evaluation();
        $module1Evaluation->setActive(true);
        $module2Evaluation->setActive(false);
        $module3Evaluation->setActive(false);
        $module1Evaluation->setVisible(true);
        $module2Evaluation->setVisible(false);
        $module3Evaluation->setVisible(false);
        $module1Evaluation->setComplete(true);
        $module2Evaluation->setComplete(false);
        $module3Evaluation->setComplete(true);
        $module1Evaluation->setMandatory(true);
        $module2Evaluation->setMandatory(false);
        $module3Evaluation->setMandatory(true);
        $module1Evaluation->setPercentage(100);
        $module2Evaluation->setPercentage(10);
        $module3Evaluation->setPercentage(10);

        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[0]);
        }))->shouldBeCalled()->willReturn($module1Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[1]);
        }))->shouldBeCalled()->willReturn($module2Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[2]);
        }))->shouldBeCalled()->willReturn($module3Evaluation);

        \BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator::swap($moduleInstanceEvaluator->reveal());

        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $this->assertTrue($progress->isComplete());
    }

    /** @test */
    public function of_activity_instance_sets_the_percentage_to_the_completed_percentage_of_all_mandatory_modules()
    {
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 3)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $module1Evaluation = new Evaluation();
        $module2Evaluation = new Evaluation();
        $module3Evaluation = new Evaluation();
        $module1Evaluation->setActive(true);
        $module2Evaluation->setActive(false);
        $module3Evaluation->setActive(false);
        $module1Evaluation->setVisible(true);
        $module2Evaluation->setVisible(false);
        $module3Evaluation->setVisible(false);
        $module1Evaluation->setComplete(true);
        $module2Evaluation->setComplete(false);
        $module3Evaluation->setComplete(false);
        $module1Evaluation->setMandatory(true);
        $module2Evaluation->setMandatory(true);
        $module3Evaluation->setMandatory(true);
        $module1Evaluation->setPercentage(100);
        $module2Evaluation->setPercentage(10);
        $module3Evaluation->setPercentage(50);

        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[0]);
        }))->shouldBeCalled()->willReturn($module1Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[1]);
        }))->shouldBeCalled()->willReturn($module2Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[2]);
        }))->shouldBeCalled()->willReturn($module3Evaluation);

        \BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator::swap($moduleInstanceEvaluator->reveal());

        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $this->assertEqualsWithDelta(53.3333, $progress->getPercentage(), 0.0001);
    }

    /** @test */
    public function of_activity_instance_sets_the_percentage_to_the_completed_percentage_of_all_mandatory_modules_and_ignores_non_mandatory_modules()
    {
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $modules = factory(ModuleInstance::class, 3)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $module1Evaluation = new Evaluation();
        $module2Evaluation = new Evaluation();
        $module3Evaluation = new Evaluation();
        $module1Evaluation->setActive(true);
        $module2Evaluation->setActive(false);
        $module3Evaluation->setActive(false);
        $module1Evaluation->setVisible(true);
        $module2Evaluation->setVisible(false);
        $module3Evaluation->setVisible(false);
        $module1Evaluation->setComplete(true);
        $module2Evaluation->setComplete(false);
        $module3Evaluation->setComplete(false);
        $module1Evaluation->setMandatory(true);
        $module2Evaluation->setMandatory(true);
        $module3Evaluation->setMandatory(false);
        $module1Evaluation->setPercentage(100);
        $module2Evaluation->setPercentage(10);
        $module3Evaluation->setPercentage(50);

        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[0]);
        }))->shouldBeCalled()->willReturn($module1Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[1]);
        }))->shouldBeCalled()->willReturn($module2Evaluation);
        $moduleInstanceEvaluator->evaluateResource(Argument::that(function ($arg) use ($activityInstance) {
            return $arg instanceof ActivityInstance && $arg->is($activityInstance);
        }), Argument::that(function ($arg) use ($modules) {
            return $arg instanceof ModuleInstance && $arg->is($modules[2]);
        }))->shouldBeCalled()->willReturn($module3Evaluation);

        \BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator::swap($moduleInstanceEvaluator->reveal());

        $progress = (new Snapshot())->ofActivityInstance($activityInstance);
        $this->assertEquals(55, $progress->getPercentage());
    }

    /** @test */
    public function of_activity_runs_the_above_for_all_activity_instances()
    {
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $activityInstance3 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $progresses = (new Snapshot())->ofActivity($activity);
        $this->assertEquals($activityInstance1->id, $progresses[0]->getActivityInstanceId());
        $this->assertEquals($activityInstance2->id, $progresses[1]->getActivityInstanceId());

        $this->assertEquals($activityInstance3->id, $progresses[2]->getActivityInstanceId());
        $this->assertEquals($activity->id, $progresses[0]->getActivityId());
        $this->assertEquals($activity->id, $progresses[1]->getActivityId());
        $this->assertEquals($activity->id, $progresses[2]->getActivityId());
    }

    public function createModuleInstanceEvaluation(bool $active, bool $visible, bool $complete, bool $mandatory, int $percentage): Evaluation
    {
        $moduleEvaluation = new Evaluation();
        $moduleEvaluation->setActive($active);
        $moduleEvaluation->setVisible($visible);
        $moduleEvaluation->setComplete($complete);
        $moduleEvaluation->setMandatory($mandatory);
        $moduleEvaluation->setPercentage($percentage);
        return $moduleEvaluation;
    }


    /** @test */
    public function ofUpdatesToActivityInstance_returns_null_if_the_progress_has_not_changed(){
        Carbon::setTestNow(Carbon::now());
        $activity = factory(Activity::class)->create();
        $moduleInstance = factory(ModuleInstance::class)->create(['activity_id' => $activity->id]);
        $activityInstance = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        // These are the way we calculate progress. We create an evaluation for the module instance
        $module1Evaluation = $this->createModuleInstanceEvaluation(true, true, true, true, 100);

        // We then mock the evaluator (which usually calculates the evaluation) and tell it to return our created evaluations.
        // This way, we have lots of control over what the evaluation contains.
        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $this->useEvaluationForModuleInstance($moduleInstanceEvaluator, $activityInstance, $moduleInstance, $module1Evaluation);
        \BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator::swap($moduleInstanceEvaluator->reveal());

        // Here, we mock the progress update repository. It's obvious we need to because Snapshot needs it as a dependency, therefore we mock
        $progressUpdateRepository = $this->prophesize(ProgressUpdateRepository::class);
        // The progress update repository should return false for hasChanged for this test. We don't care how it's calculated, just that it's false, so we mock it
        $progressUpdateRepository->hasChanged(
            $activityInstance->id,
            'my-caller',
            Argument::that(function($progress) use ($module1Evaluation) {
                return $progress->getModules[0] === $module1Evaluation;
        }))->willReturn(false);

        $snapshot = new Snapshot($progressUpdateRepository->reveal());
        $this->assertNull(
            $snapshot->ofUpdateToActivityInstance($activityInstance, 'my-caller')
        );

    }

    /** @test */
    public function ofUpdatesToActivityInstance_returns_progress_if_the_progress_has_changed(){
        $this->markTestIncomplete('Like above but make two module instance evaluations, and change the prophecy to use saveChanges too');
    }

    /** @test */
    public function ofUpdatesToActivityInstance_saves_an_updated_progress_and_returns_it(){
        $this->markTestIncomplete('use shouldBeCalled to assert it should be saved');
    }

    /** @test */
    public function ofUpdatesToActivity_returns_an_empty_array_if_no_progress_has_changed(){
        $this->markTestIncomplete();
    }

    /** @test */
    public function ofUpdatesToActivity_returns_only_changed_progresses(){
        $this->markTestIncomplete();
    }

    private function useEvaluationForModuleInstance(\Prophecy\Prophecy\ObjectProphecy $moduleInstanceEvaluator, ActivityInstance $activityInstance, ModuleInstance $moduleInstance, Evaluation $moduleEvaluation)
    {
        $moduleInstanceEvaluator->evaluateResource(
            Argument::that(fn ($arg) => $arg instanceof ActivityInstance && $arg->is($activityInstance)),
            Argument::that(fn ($arg) => $arg instanceof ModuleInstance && $arg->is($moduleInstance)),
        )->shouldBeCalled()->willReturn($moduleEvaluation);
    }
}
