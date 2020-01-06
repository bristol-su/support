<?php


namespace BristolSU\Support\Filters\Contracts\Filters;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents a filter
 */
abstract class Filter implements Arrayable
{

    /**
     * Get the model for the filter
     * 
     * @return User|Group|Role
     */
    abstract public function model();

    /**
     * Get possible options as an array
     * 
     * Array should be a key => value of the option key and the default value
     * e.g. ['group_name' => 'Group Name Default']
     *
     * @return array Options
     */
    // TODO Turn to FormSchema
    abstract public function options(): array;

    /**
     * Does the filter have a model to test?
     * 
     * @return bool Does the filter have a model?
     */
    abstract public function hasModel(): bool;

    /**
     * Set a model to use for the filter
     * 
     * @param User|Group|Role $model Model to be used for the filter evaluation
     * @return void
     */
    abstract public function setModel($model);

    /**
     * Test if the filter passes
     *
     * @param string $settings Filled in values in the form of options()
     *
     * @return bool Does the filter pass?
     */
    abstract public function evaluate($settings): bool;

    /**
     * Name of the filter
     * 
     * @return string Name of the filter
     */
    abstract public function name();

    /**
     * Description of the filter
     *
     * @return string Description of the filter
     */
    abstract public function description();

    /**
     * Alias of the filter
     *
     * @return string Alias of the filter
     */
    abstract public function alias();

    /**
     * Cast the filter to an array
     * 
     * @return array Filter as an array
     */
    public function toArray()
    {
        return [
            'alias' => $this->alias(),
            'name' => $this->name(),
            'description' => $this->description(),
            'options' => $this->options()
        ];
    }


}
