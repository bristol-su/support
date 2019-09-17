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
class ApiAuthentication implements Authentication
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
     * Get a group from the group_id parameter
     * 
     * @return Group|null
     */
    public function getGroup()
    {
        if (($role = $this->getRole()) !== null) {
            return $role->group();
        }
        
        if ($this->request !== null && $this->request->has('group_id')) {
            try {
                return $this->groupRepository->getById($this->request->query('group_id'));
            } catch (Exception $e) {
            }
        }
        return null;
    }

    /**
     * Get a role from the role_id parameter
     * 
     * @return Role|null
     */
    public function getRole()
    {
        if ($this->request !== null && $this->request->has('role_id')) {
            try {
                return $this->roleRepository->getById($this->request->query('role_id'));
            } catch (Exception $e) {
            }
        }
        return null;
    }

    /**
     * Get a user from the user_id parameter
     * 
     * @return User|null
     */    
    public function getUser()
    {
        if ($this->request !== null && $this->request->has('user_id')) {
            try {
                return $this->userRepository->getById($this->request->query('user_id'));
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
        $this->request->query->set('group_id', $group->id());
    }

    /**
     * Set the role
     * 
     * @param Role $role
     * @return void
     */
    public function setRole(Role $role)
    {
        $this->request->query->set('role_id', $role->id());
    }

    /**
     * Set the user
     * 
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->request->query->set('user_id', $user->id());
    }

    /**
     * Reset the query strings to null
     * 
     * @return void
     */
    public function reset(): void
    {
        $this->request->query->set('user_id', null);
        $this->request->query->set('group_id', null);
        $this->request->query->set('role_id', null);
    }
}