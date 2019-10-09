<?php


namespace BristolSU\Support\Tests\Completion\Facade;


use BristolSU\Support\Completion\Contracts\CompletionTester;
use BristolSU\Support\Completion\Facade\CompletionTester as CompletionTesterFacade;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class CompletionTesterTest extends TestCase
{

    /** @test */
    public function evaluate_calls_the_evaluate_method(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $completionTester = $this->prophesize(CompletionTester::class);
        $completionTester->evaluate(Argument::that(function($moduleInstanceArg) use ($moduleInstance) {
            return $moduleInstanceArg->id === $moduleInstance->id;
        }))->shouldBeCalled();

        $this->instance(CompletionTester::class, $completionTester->reveal());

        CompletionTesterFacade::evaluate($moduleInstance);
    }

}
