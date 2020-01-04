<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ActivityInstanceEvaluator as ActivityEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

/**
 * Class ActivityEvaluator
 * @package BristolSU\Support\ModuleInstance\Evaluator
 */
class ActivityInstanceEvaluator implements ActivityEvaluatorContract
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
    public function evaluateAdministrator(ActivityInstance $activityInstance, ?User $user = null, ?Group $group = null, ?Role $role = null){
        $evaluated = [];
        foreach($activityInstance->activity->moduleInstances as $moduleInstance) {
            $evaluated[$moduleInstance->id] = clone $this->moduleInstanceEvaluator->evaluateAdministrator($activityInstance, $moduleInstance, $user, $group, $role);
        }
        return $evaluated;
    }

    /**
     * @param Activity $activity
     * @return array|mixed
     */
    public function evaluateParticipant(ActivityInstance $activityInstance, ?User $user = null, ?Group $group = null, ?Role $role = null) {
        $evaluated = [];
        foreach($activityInstance->activity->moduleInstances as $moduleInstance) {
            $evaluated[$moduleInstance->id] = clone $this->moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $user, $group, $role);
        }
        return $evaluated;
    }

    public function evaluateResource(ActivityInstance $activityInstance)
    {
        $evaluated = [];
        foreach($activityInstance->activity->moduleInstances as $moduleInstance) {
            $evaluated[$moduleInstance->id] = clone $this->moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance);
        }
        return $evaluated;
    }

}
