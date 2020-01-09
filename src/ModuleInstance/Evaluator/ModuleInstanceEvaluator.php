<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;

/**
 * Evaluates a given module instance and module
 */
class ModuleInstanceEvaluator implements ModuleInstanceEvaluatorContract
{

    /**
     * Evaluate a module instance for a given administrator
     *
     * @param ActivityInstance $activityInstance Activity instance to evaluate
     * @param ModuleInstance $moduleInstance Module instance to evaluate
     * @param User|null $user User to evaluate for
     * @param Group|null $group Group to evaluate for
     * @param Role|null $role Role to evaluate for
     * @return EvaluationContract
     */
    public function evaluateAdministrator(ActivityInstance $activityInstance, ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null): EvaluationContract
    {
        $evaluation = app(EvaluationContract::class);
        $evaluation->setVisible(true);
        $evaluation->setMandatory(false);
        $evaluation->setActive(true);
        $evaluation->setComplete(false);

        return $evaluation;
    }

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
    public function evaluateParticipant(ActivityInstance $activityInstance, ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null): EvaluationContract
    {
        $evaluation = app(EvaluationContract::class);
        $evaluation->setVisible(LogicTester::evaluate($moduleInstance->visibleLogic, $user, $group, $role));
        $evaluation->setMandatory($activityInstance->activity->isCompletable()?LogicTester::evaluate($moduleInstance->mandatoryLogic, $user, $group, $role):false);
        $evaluation->setActive(LogicTester::evaluate($moduleInstance->activeLogic, $user, $group, $role));
        $evaluation->setComplete($this->isComplete($activityInstance, $moduleInstance));

        return $evaluation;
    }

    /**
     * Test if the given module instance is complete for the given module instance
     * 
     * @param ActivityInstance $activityInstance Activity instance to test against
     * @param ModuleInstance $moduleInstance Module instance to test
     * @return bool If the module is complete
     */
    private function isComplete(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool
    {
        return ($activityInstance->activity->isCompletable() ?
            app(CompletionConditionTester::class)->evaluate($activityInstance, $moduleInstance->completionConditionInstance) :
            false);
    }

    /**
     * Evaluate the module instance for all participants of the activity instance.
     *
     * This method, unlike the admin or participant methods, does not accept a user/group/role. Instead, it evaluates the
     * module instance as a whole for a general overview of how the resource (i.e. the model associated to the activity instance)
     * is doing.
     *
     * // TODO Tidy this function!
     * 
     * @param ActivityInstance $activityInstance
     * @param ModuleInstance $moduleInstance
     * @return Evaluation
     */
    public function evaluateResource(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): EvaluationContract
    {
        $evaluation = app(EvaluationContract::class);
        // TODO Visible and active only if true for anyone
        $evaluation->setVisible(true);
        $evaluation->setActive(true);
        $evaluation->setComplete($this->isComplete($activityInstance, $moduleInstance));

        // It is mandatory if mandatory for anyone
        if($activityInstance->activity->isCompletable()) {
            $resource = $activityInstance->participant();

            if ($resource instanceof User) {
                $audienceMember = app(AudienceMemberFactory::class)->fromUser($resource);
                $audienceMember->filterForLogic($moduleInstance->mandatoryLogic);
                $this->setMandatory($audienceMember->hasAudience());
            } else {
                $users = collect();
                $mandatory = false;
                $userRepository = app(UserRepository::class);
                if ($resource instanceof Group) {
                    $users = $userRepository->allThroughGroup($resource)->merge(
                        app(RoleRepository::class)->allThroughGroup($resource)->map(function(Role $role) {
                            return $role->users();
                        })->values()->flatten(1)
                    )->unique(function($user) {
                        return $user->id();
                    });
                } elseif ($resource instanceof Role) {
                    $users = $userRepository->allThroughRole($resource);
                }
                $audienceMemberFactory = app(AudienceMemberFactory::class);
                foreach ($users as $user) {
                    $audienceMember = $audienceMemberFactory->fromUser($user);
                    $audienceMember->filterForLogic($moduleInstance->mandatoryLogic);
                    if (
                        $audienceMember->hasAudience()
                        && ((
                                $resource instanceof Group
                                && ($audienceMember->groups()->filter(function ($group) use ($resource) {
                                        return $group->id() === $resource->id();
                                    })->count() > 0
                                    || $audienceMember->roles()->filter(function ($role) use ($resource) {
                                        return $role->groupId() === $resource->id();
                                    })->count() > 0)
                            ) || (
                                $resource instanceof Role
                                && $audienceMember->roles()->filter(function ($role) use ($resource) {
                                    return $role->id() === $resource->id();
                                })->count() > 0)
                        )
                    ) {
                        $mandatory = true;
                        break;
                    }
                }
                $evaluation->setMandatory($mandatory);
            }
        } else {
            $evaluation->setMandatory(false);
        }
       

        return $evaluation;
    }


}
