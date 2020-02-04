<?php

namespace BristolSU\Support\User;

use BristolSU\ControlDB\Contracts\Repositories\DataUser;
use BristolSU\Support\User\Contracts\UserRepository as UserRepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Handles retrieving and setting users.
 */
class UserRepository implements UserRepositoryContract
{

    /**
     * Get all users registered in the database
     *
     * @return User[]|Collection
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Get a user matching the given control ID
     *
     * @param int $controlId Control ID of the user
     * @return User
     * @throws ModelNotFoundException
     */
    public function getFromControlId(int $controlId): User
    {
        return User::where('control_id', $controlId)->firstOrFail();
    }

    /**
     * Create a user.
     *
     * Attributes should be those in the database
     * [
     *      'control_id' => 1, // ID of the control user model representing the user
     * ];
     *
     * @param array $attributes Attributes to create the user with
     * @return User
     */
    public function create(array $attributes): User
    {
        return User::create($attributes);
    }

    /**
     * Get a user matching the given email address
     *
     * @param string $email Email address of the user
     * @return User
     * @throws ModelNotFoundException
     */
    public function getWhereEmail($email): User
    {
        $dataUser = app(DataUser::class)->getWhere(['email' => $email]);
        $controlUser = app(\BristolSU\ControlDB\Contracts\Repositories\User::class)->getByDataProviderId($dataUser->id());
        return $this->getFromControlId($controlUser->id());
    }

    /**
     * Get a user by remember token
     *
     * @param string $token Remember token
     * @return User
     * @throws ModelNotFoundException
     */
    public function getFromRememberToken(string $token): User 
    {
        return User::where('remember_token', $token)->firstOrFail();
    }
}
