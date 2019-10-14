<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\User\User;

abstract class UserFilter extends Filter
{

    private $user;
    
    public function setModel($model)
    {
        if(!($model instanceof User)) {
            throw new \Exception(
                sprintf('Cannot pass a class of type [%s] to a user filter', get_class($model))
            );
        }
        $this->user = $model;
    }
    
    public function hasModel(): bool
    {
        return $this->user !== null;
    }

    public function model()
    {
        return $this->user;
    }

    // TODO Refactor this out, since filters now extend the relevant implementation
    public function for()
    {
        return 'user';
    }



}
