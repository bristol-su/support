<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserRepository as UserRepositoryContract;
use Illuminate\Support\Collection;

/**
 * Handles retrieving and setting users.
 */
class UserRepository implements UserRepositoryContract
{

    /**
     * Get a user where their identity matches the argument. An identity can be qualified as a student ID or email
     *
     * @param string $identity Student ID or email address of the user
     * @return User|null
     */
    public function getWhereIdentity($identity)
    {
        return User::where('email', $identity)
            ->orWhere('student_id', $identity)
            ->first();
    }

    /**
     * Get a user matching the given email address
     *
     * @param string $email Email address of the user
     * @return User|null
     */
    public function getWhereEmail($email)
    {
        return User::where('email', $email)->get();
    }

    /**
     * Create a user.
     *
     * Attributes should be those in the database
     * [
     *      'forename' => 'Forename',
     *      'surname' => 'Surname',
     *      'email' => 'email@example.com',
     *      'student_id' => 'student id',
     *      'control_id' => 1 // ID of the control user model representing the user
     * ]
     *
     * @param array $attributes Attributes to create the user with
     * @return User
     */
    public function create(array $attributes)
    {
        return User::create($attributes);
    }

    /**
     * Get all users registered in the database
     *
     * @return User[]|Collection
     */
    public function all()
    {
        return User::all();
    }
}
