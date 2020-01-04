<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 12/02/19
 * Time: 18:32
 */

namespace BristolSU\Support\Authentication\AuthenticationProvider;

use BristolSU\ControlDB\Contracts\Repositories\Role as RoleContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

/**
 * Class RoleProvider
 * @package BristolSU\Support\Authentication\AuthenticationProvider
 */
class RoleProvider implements UserProvider
{

    /**
     * @var RoleContract
     */
    private $role;

    /**
     * RoleProvider constructor.
     * @param RoleContract $role
     */
    public function __construct(RoleContract $role)
    {
        $this->role = $role;
    }

    /**
     * @param mixed $identifier
     * @return Authenticatable|mixed|null
     */
    public function retrieveById($identifier)
    {
        return $this->role->getById($identifier);

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
     * @return Authenticatable|mixed|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['role_id'])) {
            return $this->retrieveById($credentials['role_id']);
        }
        return null;
    }

    /**
     * Ensure the user owns the committee role
     *
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (isset($credentials['student_control_id']) && isset($credentials['role_id'])) {
            try {
                $role = $this->retrieveById($credentials['role_id']);
            } catch (\Exception $e) {
                return false;
            }
            if ($role->student_id === (int) $credentials['student_control_id']) {
                return true;
            }
        }
        return false;
    }
}
