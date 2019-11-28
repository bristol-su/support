<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

/**
 * Interface ModuleInstanceEvaluator
 * @package BristolSU\Support\ModuleInstance\Contracts\Evaluator
 */
interface ModuleInstanceEvaluator
{

    /**
     * @param ModuleInstance $moduleInstance
     * @return mixed
     */
    public function evaluateParticipant(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): EvaluationContract;

    /**
     * @param ModuleInstance $moduleInstance
     * @return mixed
     */
    public function evaluateAdministrator(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): EvaluationContract;
}
