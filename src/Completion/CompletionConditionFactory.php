<?php


namespace BristolSU\Support\Completion;

use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Completion\Contracts\CompletionConditionFactory as CompletionConditionFactoryContract;
use Illuminate\Contracts\Container\Container;

/**
 * Resolve a completion condition from the container.
 */
class CompletionConditionFactory implements CompletionConditionFactoryContract
{
    /**
     * Holds a reference to the container.
     *
     * @var Container
     */
    private $container;

    /**
     * @param Container $container Container to resolve the condition from
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve a condition from its class name.
     *
     * @param string $className Name of the class to resolve
     * @param string $moduleAlias Module alias requesting the condition
     * @throws \Illuminate\Contracts\Container\BindingResolutionException If the resolving failed
     * @return CompletionCondition
     *
     */
    public function createCompletionConditionFromClassName($className, $moduleAlias): CompletionCondition
    {
        return $this->container->make($className, ['moduleAlias' => $moduleAlias]);
    }
}
