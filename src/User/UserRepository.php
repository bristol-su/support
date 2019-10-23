<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserRepository as UserRepositoryContract;

/**
 * Class UserRepository
 * @package BristolSU\Support\User
 */
class UserRepository implements UserRepositoryContract
{

    /**
     * @param $identity
     * @return mixed
     */
    public function getWhereIdentity($identity)
    {
        return User::where('email', $identity)
            ->orWhere('student_id', $identity)
            ->first();
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getWhereEmail($email)
    {
        return User::where('email', $email)->get();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return User::create($attributes);
    }

    /**
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return User::all();
    }
}
