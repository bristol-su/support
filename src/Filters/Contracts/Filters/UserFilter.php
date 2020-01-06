<?php


namespace BristolSU\Support\Filters\Contracts\Filters;

use \BristolSU\ControlDB\Contracts\Models\User;

/**
 * A filter for a user
 */
abstract class UserFilter extends Filter
{

    /**
     * Holds the user if set
     *
     * @var User|null
     */
    private $user;

    /**
     * Set the user
     *
     * @param User $model User to set
     * @return void
     *
     * @throws \Exception If model is not a user
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
     * Does the filter have a user?
     *
     * @return bool If the filter has a user
     */
    public function hasModel(): bool
    {
        return $this->user !== null;
    }

    /**
     * Get the user
     *
     * @return User|null
     */
    public function model()
    {
        return $this->user;
    }

    /**
     * Get the user
     *
     * @return User|null
     */
    public function user()
    {
        return $this->model();
    }


}
