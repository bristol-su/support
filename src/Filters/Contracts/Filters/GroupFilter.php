<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use BristolSU\ControlDB\Contracts\Models\Group;

/**
 * A filter for a group
 */
abstract class GroupFilter extends Filter
{

    /**
     * Holds the group if set
     * 
     * @var Group|null
     */
    private $group;

    /**
     * Set the group
     * 
     * @param Group $model Group to set
     * @return void
     * 
     * @throws \Exception If model is not a group
     */
    public function setModel($model)
    {
        if (!($model instanceof Group)) {
            throw new \Exception(
                sprintf('Cannot pass a class of type [%s] to a group filter', get_class($model))
            );
        }
        $this->group = $model;
    }

    /**
     * Does the filter have a group?
     * 
     * @return bool If the filter has a group
     */
    public function hasModel(): bool
    {
        return $this->group !== null;
    }

    /**
     * Get the group
     * 
     * @return Group|null
     */
    public function model()
    {
        return $this->group;
    }

    /**
     * Get the group
     * 
     * @return Group|null
     */
    public function group()
    {
        return $this->model();
    }

}
