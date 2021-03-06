<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use BristolSU\ControlDB\Contracts\Models\Role;

/**
 * A filter for a role.
 */
abstract class RoleFilter extends Filter
{
    /**
     * Holds the role if set.
     *
     * @var Role|null
     */
    private $role;

    /**
     * Set the role.
     *
     * @param Role $model Role to set
     *
     * @throws \Exception If model is not a role
     */
    public function setModel($model)
    {
        if (!($model instanceof Role)) {
            throw new \Exception(
                sprintf('Cannot pass a class of type [%s] to a role filter', get_class($model))
            );
        }
        $this->role = $model;
    }

    /**
     * Does the filter have a role?
     *
     * @return bool If the filter has a role
     */
    public function hasModel(): bool
    {
        return $this->role !== null;
    }

    /**
     * Get the role.
     *
     * @return Role|null
     */
    public function model()
    {
        return $this->role;
    }

    /**
     * Get the role.
     *
     * @return Role|null
     */
    public function role()
    {
        return $this->model();
    }
}
