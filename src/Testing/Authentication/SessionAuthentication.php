<?php

namespace BristolSU\Support\Testing\Authentication;

use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Authentication\Contracts\Authentication as AuthenticationContract;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use \BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use Illuminate\Contracts\Session\Session;

/**
 * Authentication for getting a user, group or role from the session
 */
class SessionAuthentication implements AuthenticationContract
{

    /**
     * Holds a reference to the session object
     *
     * @var Session
     */
    private $session;

    /**
     * Group repository
     *
     * @var GroupRepository
     */

    private $groupRepository;
    /**
     * User Repository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Role Repository
     *
     * @var RoleRepository
     */
    private $roleRepository;


    /**
     * Initialise the session
     *
     * @param Session $session Session
     * @param GroupRepository $groupRepository Group Repository
     * @param UserRepository $userRepository User Repository
     */
    public function __construct(Session $session, GroupRepository $groupRepository, UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->session = $session;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get the group
     *
     * Returns the group belonging to a role if logged into a role, otherwise the group logged in
     *
     * @return Group|null
     */
    public function getGroup()
    {
        if (($role = $this->getRole()) !== null) {
            return $role->group();
        }

        if ($this->session->has('group_id')) {
            return $this->groupRepository->getById(
                $this->session->get('group_id')
            );
        }

        return null;
    }

    /**
     * Get the role
     *
     * @return Role|null
     */
    public function getRole()
    {
        if ($this->session->has('role_id')) {
            return $this->roleRepository->getById(
                $this->session->get('role_id')
            );
        }

        return null;
    }

    /**
     * Get the user
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->session->has('user_id')) {
            return $this->userRepository->getById(
                $this->session->get('user_id')
            );
        }

        return null;
    }

    /**
     * Set the group
     *
     * @param Group $group
     * @return void
     */
    public function setGroup(Group $group)
    {
        $this->session->put('group_id', $group->id());
    }

    /**
     * Set the role
     *
     * @param Role $role
     * @return void
     */
    public function setRole(Role $role)
    {
        $this->session->put('role_id', $role->id());
    }

    /**
     * Set the user
     *
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->session->put('user_id', $user->id());
    }

    /**
     * Is a group logged in?
     *
     * @return bool
     */
    public function hasGroup(): bool
    {
        return $this->session->has('group_id');
    }

    /**
     * Is a role logged in?
     *
     * @return bool
     */
    public function hasRole(): bool
    {
        return $this->session->has('role_id');
    }

    /**
     * Is a user logged in?
     *
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->session->has('user_id');
    }
}
