<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Completion\Contracts\CompletionConditionInstance;
use BristolSU\Support\Completion\Contracts\CompletionConditionRepository;
use BristolSU\Support\Completion\Contracts\CompletionConditionTester as CompletionConditionTesterContract;

/**
 * Test if a module instance is complete
 */
class CompletionConditionTester implements CompletionConditionTesterContract
{
    /**
     * Holds the completion condition repository
     *
     * @var CompletionConditionRepository
     */
    private $repository;

    /**
     * @param CompletionConditionRepository $repository
     */
    public function __construct(CompletionConditionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Check if the completion condition is complete for the given activity instance
     *
     * @param ActivityInstance $activityInstance Activity instance to test
     * @param CompletionConditionInstance $completionConditionInstance Completion condition instance to test
     *
     * @return bool If the completion condition is complete
     */
    public function evaluate(ActivityInstance $activityInstance, CompletionConditionInstance $completionConditionInstance): bool
    {
        $completionCondition = $this->repository->getByAlias($completionConditionInstance->moduleInstance->alias(), $completionConditionInstance->alias());
        return $completionCondition->isComplete(
            $completionConditionInstance->settings(),
            $activityInstance,
            $completionConditionInstance->moduleInstance
        );
    }

    /**
     * Check the completion condition completion percentage.
     *
     * @param ActivityInstance $activityInstance Activity instance to test
     * @param CompletionConditionInstance $completionConditionInstance Completion condition instance to test
     *
     * @return int Percentage completion
     */
    public function evaluatePercentage(ActivityInstance $activityInstance, CompletionConditionInstance $completionConditionInstance): int
    {
        $completionCondition = $this->repository->getByAlias($completionConditionInstance->moduleInstance->alias(), $completionConditionInstance->alias());
        return $completionCondition->percentage(
            $completionConditionInstance->settings(),
            $activityInstance,
            $completionConditionInstance->moduleInstance
        );
    }
}
