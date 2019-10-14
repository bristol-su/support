<?php


namespace BristolSU\Support\Tests\ModuleInstance\Evaluator;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Completion\Contracts\CompletionTester;
use BristolSU\Support\Control\Models\Group;
use BristolSU\Support\Control\Models\Role;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Evaluator\ModuleInstanceEvaluator;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\User\User;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceEvaluatorTest extends TestCase
{

    /** @test */
    public function admin_returns_an_evaluation_instance(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal(), $this->prophesize(Authentication::class)->reveal());
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateAdministrator($moduleInstance));
    }


    /** @test */
    public function participant_returns_an_evaluation_instance(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal(), $this->prophesize(Authentication::class)->reveal());
        $this->assertInstanceOf(Evaluation::class, $moduleInstanceEvaluator->evaluateParticipant($moduleInstance));
    }


    /** @test */
    public function admin_passes_the_correct_data_to_an_evaluation(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(false)->shouldBeCalled();
        $evaluation->setActive(true)->shouldBeCalled();

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal(), $this->prophesize(Authentication::class)->reveal());
        $moduleInstanceEvaluator->evaluateAdministrator($moduleInstance);
    }

    /** @test */
    public function participant_passes_the_correct_data_to_an_evaluation(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);
        $evaluation->setVisible(true)->shouldBeCalled();
        $evaluation->setMandatory(true)->shouldBeCalled();
        $evaluation->setActive(false)->shouldBeCalled();

        $this->createLogicTester([$moduleInstance->visibleLogic, $moduleInstance->mandatoryLogic], $moduleInstance->activeLogic);
        
        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal(), $this->prophesize(Authentication::class)->reveal());
        $moduleInstanceEvaluator->evaluateParticipant($moduleInstance);
    }

    /** @test */
    public function participant_passes_the_user_group_and_role_to_the_tester(){
        $moduleInstance = factory(ModuleInstance::class)->create();
        $evaluation = $this->prophesize(Evaluation::class);

        $user = factory(User::class)->create();
        $group = new Group(['id' => 1]);
        $role = new Role(['id' => 2]);

        $authentication = $this->prophesize(Authentication::class);
        $authentication->getUser()->shouldBeCalled()->willReturn($user);
        $authentication->getGroup()->shouldBeCalled()->willReturn($group);
        $authentication->getRole()->shouldBeCalled()->willReturn($role);

        $this->createLogicTester([$moduleInstance->visibleLogic, $moduleInstance->mandatoryLogic], $moduleInstance->activeLogic, $user, $group, $role);

        $moduleInstanceEvaluator = new ModuleInstanceEvaluator($evaluation->reveal(), $authentication->reveal());
        $moduleInstanceEvaluator->evaluateParticipant($moduleInstance);
    }
}
