<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use BristolSU\Support\Control\Contracts\Models\Role;

abstract class RoleFilter extends Filter
{

    private $role;

    public function setModel($model)
    {
        if(!($model instanceof Role)) {
            throw new \Exception(
                sprintf('Cannot pass a class of type [%s] to a role filter', get_class($model))
            );
        }
        $this->role = $model;
    }
    
    public function hasModel(): bool
    {
        return $this->role !== null;
    }

    public function model()
    {
        return $this->role;
    }

    public function for()
    {
        return 'role';
    }


}
