<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 12/02/19
 * Time: 18:32
 */

namespace BristolSU\Support\Authentication\AuthenticationProvider;

use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

/**
 * Role authentication provider
 */
class RoleProvider implements UserProvider
{

    /**
     * Holds the role repository
     *
     * @var RoleContract
     */
    private $role;

    /**
     * Initialise the role provider
     * .
     * @param RoleContract $role Role repository contract to retrieve the role from
     */
    public function __construct(RoleContract $role)
    {
        $this->role = $role;
    }

    /**
     * Get a role by ID
     * 
     * @param mixed $identifier ID of the role
     * @return Role|null
     */
    public function retrieveById($identifier)
    {
        return $this->role->getById($identifier);

    }

    /**
     * Get a role by the remember token
     * 
     * @param mixed $identifier ID of the role
     * @param string $token Remember token of the role
     * @return Role|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    /**
     * Update the remember token for the given role
     * 
     * @param Authenticatable $role Role to update the token on
     * @param string $token Token to update
     */
    public function updateRememberToken(Authenticatable $role, $token)
    {
    }

    /**
     * Retrieve a role by ID
     * 
     * Credentials: 
     * [
     *      'role_id' => 1
     * ]
     * 
     * @param array $credentials Credentials containing the role id
     * @return Role|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['role_id'])) {
            return $this->retrieveById($credentials['role_id']);
        }
        return null;
    }

    /**
     * Ensure the role credentials are valid for the given role
     *
     * $credentials = [
     *      'role_id' => 1
     * ]
     * @param Authenticatable $role Role to validate against
     * @param array $credentials Credentials containing the role id
     * @return bool
     */
    public function validateCredentials(Authenticatable $role, array $credentials)
    {
        if (isset($credentials['role_id'])) {
            try {
                $role = $this->retrieveById($credentials['role_id']);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }
}
