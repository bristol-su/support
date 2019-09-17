<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserRepository as UserRepositoryContract;

class UserRepository implements UserRepositoryContract
{

    public function getWhereIdentity($identity)
    {
        return User::where('email', $identity)
            ->orWhere('student_id', $identity)
            ->first();
    }

    public function getWhereEmail($email)
    {
        return User::where('email', $email)->get();
    }

    public function create(array $attributes)
    {
        return User::create($attributes);
    }

    public function all()
    {
        return User::all();
    }
}
