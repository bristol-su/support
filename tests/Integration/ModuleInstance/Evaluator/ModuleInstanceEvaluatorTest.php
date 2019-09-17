<?php


namespace BristolSU\Support\Tests\Integration\ModuleInstance\Evaluator;


use BristolSU\Support\Completion\Contracts\CompletionTester;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Prophecy\Argument;
use BristolSU\Support\Testing\TestCase;

class ModuleInstanceEvaluatorTest extends TestCase
{

    /** @test */
    public function admin_returns_an_evaluation_instance(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal());
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateAdministrator($moduleInstance));
    }


    /** @test */
    public function participant_returns_an_evaluation_instance(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal());
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateParticipant($moduleInstance));
    }


    /** @test */
    public function admin_passes_the_correct_data_to_an_evaluation(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setActive(true)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal());
        $moduleInstanceEvaluator->evaluateAdministrator($moduleInstance);
    }

    /** @test */
    public function participant_passes_the_correct_data_to_an_evaluation(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(true)->shouldBeCalled();
        $evaluation->setActive(false)->shouldBeCalled();
        $evaluation->setComplete(false)->shouldBeCalled();

        $this->createLogicTester([$moduleInstance->visibleLogic, $moduleInstance->mandatoryLogic], $moduleInstance->activeLogic);
        $completionTester = $this->prophesize(CompletionTester::class);
        $completionTester->evaluate(Argument::that(function($moduleInstanceArg) use ($moduleInstance) {
            return $moduleInstanceArg->id === $moduleInstance->id;
        }))->shouldBeCalled()->willReturn(false);
        $this->instance(CompletionTester::class, $completionTester->reveal());
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal());
        $moduleInstanceEvaluator->evaluateParticipant($moduleInstance);
    }
}
