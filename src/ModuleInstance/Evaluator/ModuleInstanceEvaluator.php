<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


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

    public function __construct(EvaluationContract $evaluation)
    {
        $this->evaluation = $evaluation;
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
        $this->evaluation->setVisible(LogicTester::evaluate($moduleInstance->visibleLogic));
        $this->evaluation->setMandatory(LogicTester::evaluate($moduleInstance->mandatoryLogic));
        $this->evaluation->setActive(LogicTester::evaluate($moduleInstance->activeLogic));

        return $this->evaluation;
    }
}
