<?php


namespace BristolSU\Support\Authentication;


use BristolSU\Support\Authentication\Contracts\Authentication as AuthenticationContract;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use \BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;

/**
 * Class LaravelAuthentication
 * @package BristolSU\Support\Authentication
 */
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
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * LaravelAuthentication constructor.
     * @param AuthFactory $auth
     * @param Request $request
     * @param RoleRepository $roleRepository
     * @param GroupRepository $groupRepository
     * @param UserRepository $userRepository
     */
    public function __construct(AuthFactory $auth,
                                Request $request,
                                RoleRepository $roleRepository,
                                GroupRepository $groupRepository,
                                UserRepository $userRepository)
    {
        $this->auth = $auth;
        $this->request = $request;
        $this->roleRepository = $roleRepository;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return mixed|null
     */
    public function getGroup()
    {
        if($this->request !== null && $this->request->has('group_id')) {
            try {
                return $this->groupRepository->getById($this->request->query('group_id'));
            } catch (\Exception $e) {}
        }

        if($this->auth->guard('group')->check()) {
            return $this->auth->guard('group')->user();
        }


        return null;
    }

    /**
     * @return mixed|null
     */
    public function getRole()
    {
        // TODO Refactor out. Also check credentials!
        if($this->request !== null && $this->request->has('role_id')) {
            try {
                return $this->roleRepository->getById($this->request->query('role_id'));
            } catch (\Exception $e) {}
        }
        
        if($this->auth->guard('role')->check()) {
            return $this->auth->guard('role')->user();
        }

        return null;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if($this->request !== null && $this->request->has('user_id')) {
            try {
                return $this->userRepository->getById($this->request->query('user_id'));
            } catch (\Exception $e) {}
        }
        
        if($this->auth->guard('user')->check()) {
            return $this->auth->guard('user')->user();
        }

        return null;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->auth->guard('group')->login($group);
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role)
    {
        $this->auth->guard('role')->login($role);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->auth->guard('user')->login($user);
    }
    
}
