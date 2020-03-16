<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use Exception;
use Illuminate\Http\Request;

/**
 * Api Authentication for getting authentication models from the query string
 */
class WebRequestAuthentication implements Authentication
{

    /**
     * Holds the request object
     *
     * @var Request
     */
    private $request;

    /**
     * Holds the role repository
     *
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * Holds the group repository
     *
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * Holds the user repository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Initialise the API authentication
     *
     * @param Request $request Request object to get parameters from
     * @param RoleRepository $roleRepository Role repository for retrieving roles
     * @param GroupRepository $groupRepository Group repository for retrieving groups
     * @param UserRepository $userRepository User repository for retrieving users
     */
    public function __construct(Request $request,
                                RoleRepository $roleRepository,
                                GroupRepository $groupRepository,
                                UserRepository $userRepository)
    {
        $this->request = $request;
        $this->roleRepository = $roleRepository;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get a group from the g parameter
     *
     * @return Group|null
     */
    public function getGroup()
    {
        if ($this->request !== null && $this->request->query->has('g')) {
            try {
                return $this->groupRepository->getById((int) $this->request->query->get('g'));
            } catch (Exception $e) {
            }
        }
        return null;
    }

    /**
     * Get a role from the r parameter
     *
     * @return Role|null
     */
    public function getRole()
    {
        if ($this->request !== null && $this->request->query->has('r')) {
            try {
                return $this->roleRepository->getById((int) $this->request->query->get('r'));
            } catch (Exception $e) {
            }
        }
        return null;
    }

    /**
     * Get a user from the u parameter
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->request !== null && $this->request->query->has('u')) {
            try {
                return $this->userRepository->getById((int) $this->request->query->get('u'));
            } catch (Exception $e) {
            }
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
        $this->request->query->set('g', $group->id());
        $this->request->overrideGlobals();
    }

    /**
     * Set the role
     *
     * @param Role $role
     * @return void
     */
    public function setRole(Role $role)
    {
        $this->request->query->set('r', $role->id());
        $this->request->overrideGlobals();
    }

    /**
     * Set the user
     *
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->request->query->set('u', $user->id());
        $this->request->overrideGlobals();
    }

    /**
     * Reset the query strings to null
     *
     * @return void
     */
    public function reset(): void
    {
        $this->request->query->remove('u');
        $this->request->query->remove('g');
        $this->request->query->remove('r');
        $this->request->overrideGlobals();
    }
}