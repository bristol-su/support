<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;

/**
 * Class ModuleInstanceEvaluator
 * @package BristolSU\Support\ModuleInstance\Evaluator
 */
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

    /**
     * ModuleInstanceEvaluator constructor.
     * @param EvaluationContract $evaluation
     * @param Authentication $authentication
     */
    public function __construct(EvaluationContract $evaluation, Authentication $authentication)
    {
        $this->evaluation = $evaluation;
        $this->authentication = $authentication;
    }

    /**
     * @param ModuleInstance $moduleInstance
     * @return EvaluationContract|mixed
     */
    public function evaluateAdministrator(ModuleInstance $moduleInstance)
    {
        $this->evaluation->setVisible(true);
        $this->evaluation->setMandatory(false);
        $this->evaluation->setActive(true);

        return $this->evaluation;
    }

    /**
     * @param ModuleInstance $moduleInstance
     * @return EvaluationContract|mixed
     */
    public function evaluateParticipant(ModuleInstance $moduleInstance)
    {
        $this->evaluation->setVisible(LogicTester::evaluate($moduleInstance->visibleLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()));
        $this->evaluation->setMandatory(LogicTester::evaluate($moduleInstance->mandatoryLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()));
        $this->evaluation->setActive(LogicTester::evaluate($moduleInstance->activeLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()));

        return $this->evaluation;
    }
}
