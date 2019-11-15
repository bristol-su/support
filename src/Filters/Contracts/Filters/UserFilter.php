<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use \BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;

/**
 * Class UserFilter
 * @package BristolSU\Support\Filters\Contracts\Filters
 */
abstract class UserFilter extends Filter
{

    /**
     * @var
     */
    private $user;

    /**
     * @param $model
     * @throws \Exception
     */
    public function setModel($model)
    {
        if(!($model instanceof User)) {
            throw new \Exception(
                sprintf('Cannot pass a class of type [%s] to a user filter', get_class($model))
            );
        }
        $this->user = $model;
    }

    /**
     * @return bool
     */
    public function hasModel(): bool
    {
        return $this->user !== null;
    }

    /**
     * @return mixed
     */
    public function model()
    {
        return $this->user;
    }

    public function user()
    {
        return $this->model();
    }

}
