<?php

namespace BristolSU\Support\ModuleInstance\Facade;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use Illuminate\Support\Facades\Facade;

/**
 * Provide a facade for accessing the module instance evaluator.
 *
 * @method static Evaluation evaluateParticipant(ActivityInstance $activityInstance, ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null) Evaluate a module instance for a participant
 * @method static Evaluation evaluateAdministrator(ActivityInstance $activityInstance, ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null) Evaluate a module instance for an administrator
 * @method static Evaluation evaluateResource(ActivityInstance $activityInstance, ModuleInstance $moduleInstance) Evaluate a module instance for an activity instance as a whole
 */
class ModuleInstanceEvaluator extends Facade
{
    /**
     * Get the binding string of the contract.
     *
     * @return string ModuleInstanceEvaluator class name
     */
    protected static function getFacadeAccessor()
    {
        return \BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator::class;
    }
}
