<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 12/02/19
 * Time: 18:32
 */

namespace BristolSU\Support\Authentication\AuthenticationProvider;

use BristolSU\Support\Control\Contracts\Repositories\User as UserRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as BaseUserProvider;

/**
 * Class UserProvider
 * @package BristolSU\Support\Authentication\AuthenticationProvider
 */
class UserProvider implements BaseUserProvider
{

    /**
     * @var UserRepositoryContract
     */
    private $users;

    /**
     * UserProvider constructor.
     * @param UserRepositoryContract $users
     */
    public function __construct(UserRepositoryContract $users)
    {
        $this->users = $users;
    }

    /**
     * @param mixed $identifier
     * @return \BristolSU\Support\Control\Contracts\Models\User|Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->users->getById($identifier);

    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return Authenticatable|void|null
     */
    public function retrieveByToken($identifier, $token)
    {

    }

    /**
     * @param Authenticatable $user
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {

    }

    /**
     * @param array $credentials
     * @return \BristolSU\Support\Control\Contracts\Models\User|Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['user_id'])) {
            return $this->retrieveById($credentials['user_id']);
        }
        return null;
    }

    /**
     * Ensure the user owns the committee user
     *
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (isset($credentials['user_id'])) {
            try {
                $user = $this->retrieveById($credentials['user_id']);
                return true;
            } catch (\Exception $e) {
            }
        }
        return false;
    }
}
