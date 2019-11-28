<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionConditionInstance;
use BristolSU\Support\Completion\Contracts\CompletionConditionRepository;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester as CompletionConditionTesterContract;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Support\Facades\Log;

/**
 * Class CompletionConditionTester
 * @package BristolSU\Support\Completion
 */
class CompletionConditionTester implements CompletionConditionTesterContract
{
    /**
     * @var CompletionConditionRepository
     */
    private $repository;

    /**
     * CompletionConditionTester constructor.
     * @param CompletionConditionRepository $repository
     */
    public function __construct(CompletionConditionRepository $repository)
    {
        $this->repository = $repository;
    }


    public function evaluate(ActivityInstance $activityInstance, CompletionConditionInstance $completionConditionInstance): bool
    {
        $completionCondition = $this->repository->getByAlias($completionConditionInstance->moduleInstance->alias(), $completionConditionInstance->alias());
        return $completionCondition->isComplete(
            $completionConditionInstance->settings(),
            $activityInstance,
            $completionConditionInstance->moduleInstance
        );
    }
}
