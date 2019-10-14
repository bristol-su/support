<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;

class ModuleInstanceEvaluator implements ModuleInstanceEvaluatorContract
{

    /**
     * @var EvaluationContract
     */
    private $evaluation;
    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(EvaluationContract $evaluation, Authentication $authentication)
    {
        $this->evaluation = $evaluation;
        $this->authentication = $authentication;
    }

    public function evaluateAdministrator(ModuleInstance $moduleInstance)
    {
        $this->evaluation->setVisible(true);
        $this->evaluation->setMandatory(false);
        $this->evaluation->setActive(true);

        return $this->evaluation;
    }

    public function evaluateParticipant(ModuleInstance $moduleInstance)
    {
        $this->evaluation->setVisible(LogicTester::evaluate($moduleInstance->visibleLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()));
        $this->evaluation->setMandatory(LogicTester::evaluate($moduleInstance->mandatoryLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()));
        $this->evaluation->setActive(LogicTester::evaluate($moduleInstance->activeLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()));

        return $this->evaluation;
    }
}
