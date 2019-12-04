<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Logic\Audience\AudienceMember;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory;
use BristolSU\Support\Logic\Facade\LogicTester;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use BristolSU\Support\ModuleInstance\Contracts\Evaluator\ModuleInstanceEvaluator as ModuleInstanceEvaluatorContract;

/**
 * Class ModuleInstanceEvaluator
 * @package BristolSU\Support\ModuleInstance\Evaluator
 */
class ModuleInstanceEvaluator implements ModuleInstanceEvaluatorContract
{

    /**
     * @var EvaluationContract
     */
    private $evaluation;

    /**
     * ModuleInstanceEvaluator constructor.
     * @param EvaluationContract $evaluation
     * @param Authentication $authentication
     */
    public function __construct(EvaluationContract $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    /**
     * @param ModuleInstance $moduleInstance
     * @return EvaluationContract|mixed
     */
    public function evaluateAdministrator(ActivityInstance $activityInstance, ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null): EvaluationContract
    {
        $this->evaluation->setVisible(true);
        $this->evaluation->setMandatory(false);
        $this->evaluation->setActive(true);
        $this->evaluation->setComplete(false);

        return $this->evaluation;
    }

    /**
     * @param ModuleInstance $moduleInstance
     * @return EvaluationContract|mixed
     */
    public function evaluateParticipant(ActivityInstance $activityInstance, ModuleInstance $moduleInstance, ?User $user = null, ?Group $group = null, ?Role $role = null): EvaluationContract
    {
        $this->evaluation->setVisible(LogicTester::evaluate($moduleInstance->visibleLogic, $user, $group, $role));
        $this->evaluation->setMandatory($activityInstance->activity->isCompletable()?LogicTester::evaluate($moduleInstance->mandatoryLogic, $user, $group, $role):false);
        $this->evaluation->setActive(LogicTester::evaluate($moduleInstance->activeLogic, $user, $group, $role));
        $this->evaluation->setComplete($this->isComplete($activityInstance, $moduleInstance));

        return $this->evaluation;
    }

    // TODO make active and visible meaningful

    private function isComplete(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool
    {
        return ($activityInstance->activity->isCompletable() ?
            app(CompletionConditionTester::class)->evaluate($activityInstance, $moduleInstance->completionConditionInstance) :
            false);
    }

    public function evaluateResource(ActivityInstance $activityInstance, ModuleInstance $moduleInstance): EvaluationContract
    {
        $this->evaluation->setVisible(true);
        $this->evaluation->setActive(true);
        $this->evaluation->setComplete($this->isComplete($activityInstance, $moduleInstance));

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
                $this->evaluation->setMandatory($mandatory);
            }
        } else {
            $this->evaluation->setMandatory(false);
        }
       

        return $this->evaluation;
    }


}
