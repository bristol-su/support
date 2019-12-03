<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;

/**
 * Interface ActivityEvaluator
 * @package BristolSU\Support\ModuleInstance\Contracts\Evaluator
 */
interface ActivityInstanceEvaluator
{

    /**
     * @param Activity $activity
     * @return mixed
     */
    public function evaluateAdministrator(ActivityInstance $activityInstance);

    /**
     * @param Activity $activity
     * @return mixed
     */
    public function evaluateParticipant(ActivityInstance $activityInstance);

    public function evaluateResource(ActivityInstance $activityInstance);

}
