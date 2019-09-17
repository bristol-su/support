<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 12/02/19
 * Time: 18:32
 */

namespace BristolSU\Support\Authentication\AuthenticationProvider;

use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class GroupProvider implements UserProvider
{

    /**
     * @var GroupRepositoryContract
     */
    private $groups;

    public function __construct(GroupRepositoryContract $groups)
    {
        $this->groups = $groups;
    }

    public function retrieveById($identifier)
    {
        return $this->groups->getById($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['group_id'])) {
            return $this->retrieveById($credentials['group_id']);
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
        if (isset($credentials['group_id'])) {
            try {
                $group = $this->retrieveById($credentials['group_id']);
                return true;
            } catch (\Exception $e) {
            }
        }
        return false;
    }
}
