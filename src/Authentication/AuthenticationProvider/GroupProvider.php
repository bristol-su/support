<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 12/02/19
 * Time: 18:32
 */

namespace BristolSU\Support\Authentication\AuthenticationProvider;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

/**
 * Group authentication provider
 */
class GroupProvider implements UserProvider
{

    /**
     * Holds the group repository 
     * 
     * @var GroupRepositoryContract
     */
    private $groups;

    /**
     * Initialise the group provider
     * 
     * @param GroupRepositoryContract $groups
     */
    public function __construct(GroupRepositoryContract $groups)
    {
        $this->groups = $groups;
    }

    /**
     * Get the group by ID
     * 
     * @param mixed $identifier ID of the group
     * @return Group|null Group with the id given
     */
    public function retrieveById($identifier)
    {
        return $this->groups->getById($identifier);
    }

    /**
     * Get the group by a remember token
     * 
     * @param mixed $identifier ID of the group
     * @param string $token Remember token
     * @return Group|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    /**
     * Update the group remember token
     *
     * @param Authenticatable $group Group to set the remember token on
     * @param string $token Remember token to set
     * @return void
     */
    public function updateRememberToken(Authenticatable $group, $token)
    {
        //TODO Implement method
    }

    /**
     * Retrieve a group by credentials
     * 
     * Credentials should be an array such as 
     * [
     *      'group_id' => 1
     * ]
     * 
     * @param array $credentials Credentials to retrieve the group with
     * @return Group|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['group_id'])) {
            return $this->retrieveById($credentials['group_id']);
        }
        return null;
    }

    /**
     * Ensure the group is accessible by the user
     *
     * Credentials should be an array:
     * [
     *      'group_id' => 1
     * ]
     * 
     * @param Authenticatable $group Group to validate against
     * @param array $credentials Credentials to validate
     * 
     * @return bool Are the credentials valid
     */
    public function validateCredentials(Authenticatable $group, array $credentials)
    {
        // TODO Validate credentials using actual credentials
        if (isset($credentials['group_id'])) {
            try {
                $group = $this->retrieveById($credentials['group_id']);
                if ($group !== null) {
                    return true;
                }
            } catch (\Exception $e) {
            }
        }
        return false;
    }
}
