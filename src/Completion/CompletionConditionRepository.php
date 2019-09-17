<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Completion\Contracts\CompletionConditionFactory as CompletionConditionFactoryContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionManager as CompletionConditionManagerContract;
use BristolSU\Support\Completion\Contracts\CompletionConditionRepository as CompletionConditionRepositoryContract;

/**
 * Access and build Completion Conditions
 */
class CompletionConditionRepository implements CompletionConditionRepositoryContract
{

    /**
     * Holds the completion condition manager reference
     * 
     * @var CompletionConditionManagerContract
     */
    private $manager;
    
    /**
     * Holds the completion condition factory contract
     * 
     * @var CompletionConditionFactoryContract
     */
    private $completionConditionFactory;

    /**
     * @param CompletionConditionManagerContract $manager Manager to get the registered completion conditions
     * @param CompletionConditionFactoryContract $completionConditionFactory Factory to create the completion conditions
     */
    public function __construct(CompletionConditionManagerContract $manager, CompletionConditionFactoryContract $completionConditionFactory)
    {
        $this->manager = $manager;
        $this->completionConditionFactory = $completionConditionFactory;
    }

    /**
     * Get a completion condition by its alias
     *
     * @param string $moduleAlias Alias of the module
     * @param string $alias Alias of the completion condition
     *
     * @return CompletionCondition
     * @throws \Exception If the class cannot be found or resolved
     */
    public function getByAlias($moduleAlias, $alias): CompletionCondition
    {
        $class = $this->manager->getClassFromAlias($moduleAlias, $alias);
        return $this->completionConditionFactory->createCompletionConditionFromClassName($class, $moduleAlias);
    }

    /**
     * Get all completion conditions for a given module alias
     *
     * @param string $moduleAlias Alias of the module
     * @return CompletionCondition[] [CompletionCondition1, CompletionCondition2]
     */
    public function getAllForModule($moduleAlias)
    {
        $classes = $this->manager->getForModule($moduleAlias);

        $completionConditions = [];
        foreach ($classes as $class) {
            $completionConditions[] = $this->completionConditionFactory->createCompletionConditionFromClassName($class, $moduleAlias);
        }
        return $completionConditions;
    }
}
