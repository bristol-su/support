<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Models\Group;

abstract class GroupFilter extends Filter
{

    private $group;

    public function setModel($model)
    {
        if(!($model instanceof Group)) {
            throw new \Exception(
                sprintf('Cannot pass a class of type [%s] to a group filter', get_class($model))
            );
        }
        $this->group = $model;
    }
    
    public function hasModel(): bool
    {
        return $this->group !== null;
    }

    public function model()
    {
        return $this->group;
    }

    public function for()
    {
        return 'group';
    }

}
