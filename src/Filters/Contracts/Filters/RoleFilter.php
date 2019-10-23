<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use BristolSU\Support\Control\Contracts\Models\Role;

/**
 * Class RoleFilter
 * @package BristolSU\Support\Filters\Contracts\Filters
 */
abstract class RoleFilter extends Filter
{

    /**
     * @var
     */
    private $role;

    /**
     * @param $model
     * @return mixed|void
     * @throws \Exception
     */
    public function setModel($model)
    {
        if(!($model instanceof Role)) {
            throw new \Exception(
                sprintf('Cannot pass a class of type [%s] to a role filter', get_class($model))
            );
        }
        $this->role = $model;
    }

    /**
     * @return bool
     */
    public function hasModel(): bool
    {
        return $this->role !== null;
    }

    /**
     * @return mixed
     */
    public function model()
    {
        return $this->role;
    }

    public function for()
    {
        return 'role';
    }


}
