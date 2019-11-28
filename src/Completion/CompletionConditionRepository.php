<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Completion\Contracts\CompletionConditionFactory as CompletionConditionFactoryContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionManager as CompletionConditionManagerContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionRepository as CompletionConditionRepositoryContract;

/**
 * Class CompletionConditionRepository
 * @package BristolSU\Support\Completion
 */
class CompletionConditionRepository implements CompletionConditionRepositoryContract
{

    /**
     * @var CompletionConditionManagerContract
     */
    private $manager;
    /**
     * @var CompletionConditionFactoryContract
     */
    private $completionConditionFactory;

    /**
     * CompletionConditionRepository constructor.
     * @param CompletionConditionManagerContract $manager
     * @param CompletionConditionFactoryContract $CompletionConditionFactory
     */
    public function __construct(CompletionConditionManagerContract $manager, CompletionConditionFactoryContract $completionConditionFactory)
    {
        $this->manager = $manager;
        $this->completionConditionFactory = $completionConditionFactory;
    }

    /**
     * @param string $alias
     * @return CompletionCondition
     */
    public function getByAlias($moduleAlias, $alias): CompletionCondition
    {
        $class = $this->manager->getClassFromAlias($moduleAlias, $alias);
        return $this->completionConditionFactory->createCompletionConditionFromClassName($class);
    }

    /**
     * @return array
     */
    public function getAllForModule($moduleAlias)
    {
        $classes = $this->manager->getForModule($moduleAlias);

        $completionConditions = [];
        foreach($classes as $class) {
            $completionConditions[] = $this->completionConditionFactory->createCompletionConditionFromClassName($class);
        }
        return $completionConditions;
    }
}
