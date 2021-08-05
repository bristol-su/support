<?php


namespace BristolSU\Support\Tests\ModuleInstance\Facade;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\Facade\ModuleInstanceEvaluator as ModuleInstanceEvaluatorFacade;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Prophecy\Argument;

class ModuleInstanceEvaluatorTest extends TestCase
{
    /** @test */
    public function the_facade_calls_the_underlying_instance()
    {
        $activityInstance = ActivityInstance::factory()->create();
        $moduleInstance = ModuleInstance::factory()->create();
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = $this->prophesize(ModuleInstanceEvaluator::class);
        $moduleInstanceEvaluator->evaluateParticipant(Argument::that(function ($arg) use ($activityInstance) {
            return $activityInstance->is($arg);
        }), Argument::that(function ($arg) use ($moduleInstance) {
            return $moduleInstance->is($arg);
        }))->shouldBeCalled()->willReturn($evaluation->reveal());
        $this->app->instance(ModuleInstanceEvaluator::class, $moduleInstanceEvaluator->reveal());

        $this->assertEquals(
            $evaluation->reveal(),
            ModuleInstanceEvaluatorFacade::evaluateParticipant($activityInstance, $moduleInstance)
        );
    }
}
