<?php


namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

/**
 * Evaluates a given module instance and module
 */
interface ModuleInstanceEvaluator
{

    /**
     * Evaluate a module instance for a given participant
     *
     * @param ActivityInstance $activityInstance Activity instance to evaluate
     * @param ModuleInstance $moduleInstance Module instance to evaluate
     * @param User|null $user User to evaluate for
     * @param Group|null $group Group to evaluate for
     * @param Role|null $role Role to evaluate for
     * @return EvaluationContract
     */
    public function evaluateParticipant(ActivityInstance $activityInstance, ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null): EvaluationContract;

    /**
     * Evaluate a module instance for a given administrator
     *
     * @param ModuleInstance $moduleInstance Module instance to evaluate
     * @param User|null $user User to evaluate for
     * @param Group|null $group Group to evaluate for
     * @param Role|null $role Role to evaluate for
     * @return EvaluationContract
     */
    public function evaluateAdministrator(ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null): EvaluationContract;

    /**
     * Evaluate the module instance for all participants of the activity instance.
     * 
     * This method, unlike the admin or participant methods, does not accept a user/group/role. Instead, it evaluates the 
     * module instance as a whole for a general overview of how the resource (i.e. the model associated to the activity instance)
     * is doing.
     * 
     * @param ActivityInstance $activityInstance
     * @param ModuleInstance $moduleInstance
     * @return Evaluation
     */
    public function evaluateResource(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): EvaluationContract;
    
}
