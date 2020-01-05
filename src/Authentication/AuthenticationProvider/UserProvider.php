<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 12/02/19
 * Time: 18:32
 */

namespace BristolSU\Support\Authentication\AuthenticationProvider;

use BristolSU\ControlDB\Contracts\Repositories\User as UserRepositoryContract;
use BristolSU\ControlDB\Contracts\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as BaseUserProvider;

/**
 * User authentication provider
 */
class UserProvider implements BaseUserProvider
{

    /**
     * Holds the user repository
     *
     * @var UserRepositoryContract
     */
    private $users;

    /**
     * Initialise the user provider
     * .
     * @param UserRepositoryContract $user User repository contract to retrieve the user from
     */
    public function __construct(UserRepositoryContract $users)
    {
        $this->users = $users;
    }

    /**
     * Get a user by ID
     *
     * @param mixed $identifier ID of the user
     * @return User|null
     */
    public function retrieveById($identifier)
    {
        return $this->users->getById($identifier);
    }

    /**
     * Get a user by the remember token
     *
     * @param mixed $identifier ID of the user
     * @param string $token Remember token of the user
     * @return User|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // TODO Implement method
    }

    /**
     * Update the remember token for the given user
     *
     * @param Authenticatable $user User to update the token on
     * @param string $token Token to update
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO Implement method
    }

    /**
     * Retrieve a user by ID
     *
     * Credentials:
     * [
     *      'user_id' => 1
     * ]
     *
     * @param array $credentials Credentials containing the user id
     * @return User|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['user_id'])) {
            return $this->retrieveById($credentials['user_id']);
        }
        return null;
    }

    /**
     * Ensure the user credentials are valid for the given user
     *
     * $credentials = [
     *      'user_id' => 1
     * ]
     * @param Authenticatable $user User to validate against
     * @param array $credentials Credentials containing the user id
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO Validate credentials using actual credentials
        if (isset($credentials['user_id'])) {
            try {
                $user = $this->retrieveById($credentials['user_id']);
                if($user !== null) {
                    return true;
                }
            } catch (\Exception $e) {
            }
        }
        return false;
    }
}
