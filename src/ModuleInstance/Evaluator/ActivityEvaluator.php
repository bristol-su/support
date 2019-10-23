<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ActivityEvaluator as ActivityEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;

/**
 * Class ActivityEvaluator
 * @package BristolSU\Support\ModuleInstance\Evaluator
 */
class ActivityEvaluator implements ActivityEvaluatorContract
{

    /**
     * @var ModuleInstanceEvaluatorContract
     */
    private $moduleInstanceEvaluator;

    /**
     * ActivityEvaluator constructor.
     * @param ModuleInstanceEvaluatorContract $moduleInstanceEvaluator
     */
    public function __construct(ModuleInstanceEvaluatorContract $moduleInstanceEvaluator)
    {
        $this->moduleInstanceEvaluator = $moduleInstanceEvaluator;
    }

    /**
     * @param Activity $activity
     * @return array|mixed
     */
    public function evaluateAdministrator(Activity $activity){
        $evaluated = [];
        foreach($activity->moduleInstances as $moduleInstance) {
            $evaluated[$moduleInstance->id] = clone $this->moduleInstanceEvaluator->evaluateAdministrator($moduleInstance);
        }
        return $evaluated;
    }

    /**
     * @param Activity $activity
     * @return array|mixed
     */
    public function evaluateParticipant(Activity $activity) {
        $evaluated = [];
        foreach($activity->moduleInstances as $moduleInstance) {
            $evaluated[$moduleInstance->id] = clone $this->moduleInstanceEvaluator->evaluateParticipant($moduleInstance);
        }
        return $evaluated;
    }

}
