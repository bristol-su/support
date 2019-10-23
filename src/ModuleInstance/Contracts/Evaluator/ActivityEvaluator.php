<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;


use BristolSU\Support\Activity\Activity;

/**
 * Interface ActivityEvaluator
 * @package BristolSU\Support\ModuleInstance\Contracts\Evaluator
 */
interface ActivityEvaluator
{

    /**
     * @param Activity $activity
     * @return mixed
     */
    public function evaluateAdministrator(Activity $activity);

    /**
     * @param Activity $activity
     * @return mixed
     */
    public function evaluateParticipant(Activity $activity);

}
