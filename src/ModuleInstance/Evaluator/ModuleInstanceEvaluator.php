<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester;
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
    public function evaluateAdministrator(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): EvaluationContract
    {
        $this->evaluation->setVisible(true);
        $this->evaluation->setMandatory(false);
        $this->evaluation->setActive(true);
        $this->evaluation->setComplete(false);

        return $this->evaluation;
    }

    /**
     * @param ModuleInstance $moduleInstance
     * @return EvaluationContract|mixed
     */
    public function evaluateParticipant(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): EvaluationContract
    {
        $this->evaluation->setVisible(LogicTester::evaluate($moduleInstance->visibleLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()));
        $this->evaluation->setMandatory(
            ($activityInstance->activity->isCompletable() ?
                LogicTester::evaluate($moduleInstance->mandatoryLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()) : false)
        );
        $this->evaluation->setActive(LogicTester::evaluate($moduleInstance->activeLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole()));
        $this->evaluation->setComplete(
            ($activityInstance->activity->isCompletable() ?
                app(CompletionConditionTester::class)->evaluate($activityInstance, $moduleInstance->completionConditionInstance) : false)
        );
        return $this->evaluation;
    }
}
