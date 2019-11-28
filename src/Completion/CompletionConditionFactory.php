<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionConditionFactory as CompletionConditionFactoryContract;
use Illuminate\Contracts\Container\Container;

/**
 * Class CompletionConditionFactory
 * @package BristolSU\Support\Completion
 */
class CompletionConditionFactory implements CompletionConditionFactoryContract
{
    /**
     * @var Container
     */
    private $container;

    /**
     * CompletionConditionFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $className
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createCompletionConditionFromClassName($className)
    {
        return $this->container->make($className);
    }
}
