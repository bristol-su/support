<?php


namespace BristolSU\Support\Authentication;


use BristolSU\Support\Authentication\Contracts\Authentication as AuthenticationContract;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\User\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaravelAuthentication implements AuthenticationContract
{

    /**
     * @var AuthFactory
     */
    private $auth;
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

    public function __construct(AuthFactory $auth, Request $request, RoleRepository $roleRepository, GroupRepository $groupRepository)
    {
        $this->auth = $auth;
        $this->request = $request;
        $this->roleRepository = $roleRepository;
        $this->groupRepository = $groupRepository;
    }

    public function getGroup()
    {

        if($this->auth->guard('group')->check()) {
            return $this->auth->guard('group')->user();
        }

        if($this->request !== null && $this->request->has('group_id')) {
            try {
                return $this->groupRepository->getById($this->request->query('group_id'));
            } catch (\Exception $e) {}
        }

        return null;
    }

    public function getRole()
    {
        if($this->auth->guard('role')->check()) {
            return $this->auth->guard('role')->user();
        }
        // TODO Refactor out. Also check credentials!
        if($this->request !== null && $this->request->has('role_id')) {
            try {
                return $this->roleRepository->getById($this->request->query('role_id'));
            } catch (\Exception $e) {}
        }

        return null;
    }

    public function getUser()
    {
        if($this->auth->guard('web')->check()) {
            return $this->auth->guard('web')->user();
        }
        if($this->auth->guard('api')->check()) {
            return $this->auth->guard('api')->user();
        }

        return null;
    }

    public function setGroup(Group $group)
    {
        $this->auth->guard('group')->login($group);
    }

    public function setRole(Role $role)
    {
        $this->auth->guard('role')->login($role);
    }

    public function setUser(User $user)
    {
        $this->auth->guard('web')->login($user);
    }
    
}
