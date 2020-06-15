<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\Logic\Logic;
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
     * @param ModuleInstance $moduleInstance Module instance to evaluate
     * @param User|null $user User to evaluate for
     * @param Group|null $group Group to evaluate for
     * @param Role|null $role Role to evaluate for
     * @return EvaluationContract
     */
    public function evaluateAdministrator(ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null): EvaluationContract
    {
        $evaluation = app(EvaluationContract::class);
        $evaluation->setVisible(true);
        $evaluation->setMandatory(false);
        $evaluation->setActive(true);
        $evaluation->setComplete(false);
        $evaluation->setPercentage(0);

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
        $evaluation->setMandatory($activityInstance->activity->isCompletable() ? LogicTester::evaluate($moduleInstance->mandatoryLogic, $user, $group, $role) : false);
        $evaluation->setActive(LogicTester::evaluate($moduleInstance->activeLogic, $user, $group, $role));
        $evaluation->setComplete($this->isComplete($activityInstance, $moduleInstance));
        $evaluation->setPercentage($this->getPercentage($activityInstance, $moduleInstance));

        return $evaluation;
    }

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
    public function evaluateResource(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): EvaluationContract
    {
        $evaluation = app(EvaluationContract::class);
        $resource = $activityInstance->participant();

        $audienceMemberFactory = app(AudienceMemberFactory::class);
        
        $evaluation->setVisible($audienceMemberFactory->withAccessToLogicGroupWithResource($resource, $moduleInstance->visibleLogic)->count() > 0);
        $evaluation->setActive($audienceMemberFactory->withAccessToLogicGroupWithResource($resource, $moduleInstance->activeLogic)->count() > 0);
        if ($activityInstance->activity->isCompletable()) {
            $evaluation->setMandatory($audienceMemberFactory->withAccessToLogicGroupWithResource($resource, $moduleInstance->mandatoryLogic)->count() > 0);
        } else {
            $evaluation->setMandatory(false);
        }
        $evaluation->setComplete($this->isComplete($activityInstance, $moduleInstance));
        $evaluation->setPercentage($this->getPercentage($activityInstance, $moduleInstance));
        
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
            app(CompletionConditionTester::class)->evaluate($activityInstance, $moduleInstance->completionConditionInstance) : false);
    }

    /**
     * Get the percentage of how complete the module is for the given activity instance
     * 
     * @param ActivityInstance $activityInstance
     * @param ModuleInstance $moduleInstance
     * 
     * @return float
     */
    private function getPercentage(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): float
    {
        return (float) ($activityInstance->activity->isCompletable() ?
            app(CompletionConditionTester::class)->evaluatePercentage($activityInstance, $moduleInstance->completionConditionInstance) : 0);
    }

}
