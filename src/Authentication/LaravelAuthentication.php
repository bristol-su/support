<?php


namespace BristolSU\Support\Authentication;


use BristolSU\Support\Authentication\Contracts\Authentication as AuthenticationContract;
use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\User\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\Auth;

class LaravelAuthentication implements AuthenticationContract
{

    /**
     * @var AuthFactory
     */
    private $auth;

    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    public function getGroup()
    {
        if($this->auth->guard('role')->check()) {
            return $this->auth->guard('role')->user()->group;
        }

        if($this->auth->guard('group')->check()) {
            return $this->auth->guard('group')->user();
        }

        return null;
    }

    public function getRole()
    {
        if($this->auth->guard('role')->check()) {
            return $this->auth->guard('role')->user();
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
