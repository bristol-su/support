<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ActivityInstanceEvaluator as ActivityEvaluatorContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;

/**
 * Evaluates all module instances belonging to an activity
 */
class ActivityInstanceEvaluator implements ActivityEvaluatorContract
{

    /**
     * Holds the module instance evaluator
     * 
     * @var ModuleInstanceEvaluatorContract
     */
    private $moduleInstanceEvaluator;

    /**
     * @param ModuleInstanceEvaluatorContract $moduleInstanceEvaluator Object to evaluate module instances
     */
    public function __construct(ModuleInstanceEvaluatorContract $moduleInstanceEvaluator)
    {
        $this->moduleInstanceEvaluator = $moduleInstanceEvaluator;
    }

    /**
     * Evaluate an activity instance for a participant
     *
     * @param ActivityInstance $activityInstance Activity instance to evaluate
     * @param User|null $user User to evaluate for
     * @param Group|null $group Group to evaluate for
     * @param Role|null $role Role to evaluate for
     *
     * @return EvaluationContract[] Array of evaluations with the module instance id as the index
     */
    public function evaluateParticipant(ActivityInstance $activityInstance, ?User $user = null, ?Group $group = null, ?Role $role = null) {
        $evaluated = [];
        foreach ($activityInstance->activity->moduleInstances as $moduleInstance) {
            $evaluated[$moduleInstance->id] = clone $this->moduleInstanceEvaluator->evaluateParticipant($activityInstance, $moduleInstance, $user, $group, $role);
        }
        return $evaluated;
    }

    /**
     * Evaluate the activity instance as a whole
     *
     * As opposed to the evaluateParticipant and evaluateAdministrator methods, which evaluate an activity instance
     * for a specific user/group/role, evaluateResource will consider all users/groups/roles able to access the activity instance.
     *
     * @param ActivityInstance $activityInstance Activity to test
     * @return EvaluationContract[] Array of evaluations with the module instance id as the index
     */
    public function evaluateResource(ActivityInstance $activityInstance)
    {
        $evaluated = [];
        foreach ($activityInstance->activity->moduleInstances as $moduleInstance) {
            $evaluated[$moduleInstance->id] = clone $this->moduleInstanceEvaluator->evaluateResource($activityInstance, $moduleInstance);
        }
        return $evaluated;
    }

}
