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

class ApiAuthentication implements Authentication
{

    /**
     * @var Request
     */
    private $request;
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var GroupRepository
     */
    private $groupRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

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

    public function getGroup()
    {
        if(($role = $this->getRole()) !== null) {
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
     * @param Group $group
     * @return mixed
     */
    public function setGroup(Group $group)
    {
        $this->request->query->set('group_id', $group->id());
    }

    /**
     * @param Role $role
     * @return mixed
     */
    public function setRole(Role $role)
    {
        $this->request->query->set('role_id', $role->id());
        return $this->request;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function setUser(User $user)
    {
        $this->request->query->set('user_id', $user->id());
    }
    
    public function reset(): void
    {
        $this->request->query->set('user_id', null);
        $this->request->query->set('group_id', null);
        $this->request->query->set('role_id', null);
    }
}