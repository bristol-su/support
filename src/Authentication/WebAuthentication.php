<?php


namespace BristolSU\Support\Authentication;


use BristolSU\Support\Authentication\Contracts\Authentication as AuthenticationContract;
use BristolSU\Support\User\Contracts\UserAuthentication;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use \BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;

/**
 * Authentication for getting a user, group or role from the laravel authentication framework
 */
class WebAuthentication implements AuthenticationContract
{

    /**
     * Laravel Authentication
     * 
     * @var AuthFactory
     */
    private $auth;
    
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
     * User Authentication (database access)
     * 
     * @var UserAuthentication
     */
    private $userAuthentication;

    /**
     * Initialise the authentication
     *
     * @param AuthFactory $auth Authentication
     * @param GroupRepository $groupRepository Group Repository
     * @param UserRepository $userRepository User Repository
     * @param UserAuthentication $userAuthentication Database User Authentication 
     */
    public function __construct(AuthFactory $auth, GroupRepository $groupRepository, UserRepository $userRepository, UserAuthentication $userAuthentication)
    {
        $this->auth = $auth;
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
        $this->userAuthentication = $userAuthentication;
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

        if ($this->auth->guard('group')->check()) {
            return $this->auth->guard('group')->user();
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
        if ($this->auth->guard('role')->check()) {
            return $this->auth->guard('role')->user();
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
        if ($this->auth->guard('user')->check()) {
            return $this->auth->guard('user')->user();
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
        $this->auth->guard('group')->login($group);
    }

    /**
     * Set the role
     * 
     * @param Role $role
     * @return void
     */
    public function setRole(Role $role)
    {
        $this->auth->guard('role')->login($role);
    }

    /**
     * Set the user
     * 
     * @param User $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->auth->guard('user')->login($user);
    }

    /**
     * Unset the user, group and role
     * 
     * @return void
     */
    public function reset(): void
    {
        $this->auth->guard('user')->logout();
        $this->auth->guard('group')->logout();
        $this->auth->guard('role')->logout();
    }
}
