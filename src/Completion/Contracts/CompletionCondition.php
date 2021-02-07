<?php

namespace BristolSU\Support\Completion\Contracts;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;
use FormSchema\Schema\Form;
use FormSchema\Transformers\VFGTransformer;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Completion Condition class.
 */
abstract class CompletionCondition implements Arrayable, Jsonable
{
    /**
     * The alias of the module the completion condition belongs to.
     *
     * @var string
     */
    private $moduleAlias;

    /**
     * Initialise the condition.
     *
     * @param string $moduleAlias The module alias of the condition. This is passed in so that a condition can be
     *  made exclusive to a module, or can be a global condition which changes its behaviour depending on the module.
     */
    public function __construct(string $moduleAlias)
    {
        $this->moduleAlias = $moduleAlias;
    }

    /**
     * Get the module alias.
     *
     * @return string
     */
    public function moduleAlias()
    {
        return $this->moduleAlias;
    }

    /**
     * Return the percentage completion of the module.
     *
     * By default, this function returns 0 if the condition is not complete, or 100 if the condition is complete.
     * You may override this method to provide more granular percentage settings
     *
     * @param array $settings Settings of the completion condition
     * @param ActivityInstance $activityInstance The activity instance being tested
     * @param ModuleInstance $moduleInstance The module instance being tested
     * @return int Percentage complete.
     */
    public function percentage($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): int
    {
        if ($this->isComplete($settings, $activityInstance, $moduleInstance)) {
            return 100;
        }

        return 0;
    }

    /**
     * Is the condition fully complete?
     *
     * @param array $settings Settings of the completion condition
     * @param ActivityInstance $activityInstance Activity instance to test
     * @param ModuleInstance $moduleInstance Module instance to test
     * @return bool If the condition is complete
     */
    abstract public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool;

    /**
     * Options required by the completion condition.
     *
     * This allows for you to get user input to modify the behaviour of this class. For example, you could give an
     * option of a 'number of files' to be approved before the condition is complete.
     *
     * Any settings requested in here will be passed into the percentage or isComplete methods.
     *
     * @return Form
     */
    abstract public function options(): Form;

    /**
     * A name for the completion condition.
     *
     * @return string
     */
    abstract public function name(): string;

    /**
     * A description of the completion condition.
     *
     * @return string
     */
    abstract public function description(): string;

    /**
     * The alias of the completion condition.
     *
     * @return string
     */
    abstract public function alias(): string;

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
    
    public function toArray()
    {
        return [
            'name' => $this->name(),
            'description' => $this->description(),
            'options' => (new VFGTransformer())->transformToArray($this->options()),
            'alias' => $this->alias()
        ];
    }
}
