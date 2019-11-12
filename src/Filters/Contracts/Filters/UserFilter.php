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

    // TODO Refactor this out, since filters now extend the relevant implementation
    public function for()
    {
        return 'user';
    }

    public function audience($settings)
    {
        $audience = [];
        $users = app()->make(UserRepository::class)->all();
        foreach($users as $user) {
            $this->setModel($user);
            if($this->evaluate($settings)) {
                $audience[] = $user;
            }
        }
        return $audience;
    }

}
