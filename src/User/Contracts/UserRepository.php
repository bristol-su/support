<?php


namespace BristolSU\Support\User\Contracts;


use BristolSU\Support\User\User;
use Illuminate\Support\Collection;

/**
 * Handle creating and retrieving users from the database
 */
interface UserRepository
{

    /**
     * Get a user matching the given email address
     * 
     * @param string $email Email address of the user
     * @return User
     */
    public function getWhereEmail($email): User;

    /**
     * Get a user matching the given control ID
     *
     * @param int $controlId Control ID of the user
     * @return User
     */
    public function getFromControlId(int $controlId): User;

    /**
     * Create a user.
     *
     * Attributes should be those in the database
     * [
     *      'control_id' => 1, // ID of the control user model representing the user
     *      'auth_provider' => 'facebook',
     *      'auth_provider_id' => fjsdfs
     * ];
     *
     * @param array $attributes Attributes to create the user with
     * @return User
     */
    public function create(array $attributes): User;

    /**
     * Get all users registered in the database
     * 
     * @return User[]|Collection
     */
    public function all();

}
