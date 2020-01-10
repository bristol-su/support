<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;


/**
 * Evaluates all module instances belonging to an activity
 */
interface ActivityInstanceEvaluator
{

    /**
     * Evaluate an activity instance for an administrator
     *
     * @param ActivityInstance $activityInstance Activity instance to evaluate
     * @param User|null $user User to evaluate for
     * @param Group|null $group Group to evaluate for
     * @param Role|null $role Role to evaluate for
     * 
     * @return EvaluationContract[] Array of evaluations with the module instance id as the index
     */
    public function evaluateAdministrator(ActivityInstance $activityInstance, ?User $user = null, ?Group $group = null, ?Role $role = null);

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
    public function evaluateParticipant(ActivityInstance $activityInstance, ?User $user = null, ?Group $group = null, ?Role $role = null);

    /**
     * Evaluate the activity instance as a whole
     * 
     * As opposed to the evaluateParticipant and evaluateAdministrator methods, which evaluate an activity instance
     * for a specific user/group/role, evaluateResource will consider all users/groups/roles able to access the activity instance.
     * 
     * @param ActivityInstance $activityInstance Activity to test
     * @return EvaluationContract[] Array of evaluations with the module instance id as the index
     */
    public function evaluateResource(ActivityInstance $activityInstance);

}
