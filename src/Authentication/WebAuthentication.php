<?php


namespace BristolSU\Support\Authentication;


use BristolSU\Support\Authentication\Contracts\Authentication as AuthenticationContract;
use BristolSU\Support\Authentication\Contracts\UserAuthentication;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use \BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;

/**
 * Class LaravelAuthentication
 * @package BristolSU\Support\Authentication
 */
class WebAuthentication implements AuthenticationContract
{

    /**
     * @var AuthFactory
     */
    private $auth;
    /**
     * @var GroupRepository
     */
    private $groupRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserAuthentication
     */
    private $userAuthentication;

    /**
     * LaravelAuthentication constructor.
     * @param AuthFactory $auth
     */
    public function __construct(AuthFactory $auth, GroupRepository $groupRepository, UserRepository $userRepository, UserAuthentication $userAuthentication)
    {
        $this->auth = $auth;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
        $this->userAuthentication = $userAuthentication;
    }

    /**
     * @return mixed|null
     */
    public function getGroup()
    {
        if (($role = $this->getRole()) !== null) {
            return $role->group();
        }

        if ($this->auth->guard('group')->check()) {
            return $this->auth->guard('group')->user();
        }

        return null;
    }

    /**
     * @return mixed|null
     */
    public function getRole()
    {
        if ($this->auth->guard('role')->check()) {
            return $this->auth->guard('role')->user();
        }

        return null;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if ($this->auth->guard('user')->check()) {
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
    
    public function reset(): void
    {
        $this->auth->guard('user')->logout();
        $this->auth->guard('group')->logout();
        $this->auth->guard('role')->logout();
    }
}
