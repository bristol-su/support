<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\Contracts\UserAuthentication as UserAuthenticationContract;
use BristolSU\Support\User\User;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Class UserAuthentication
 * @package BristolSU\Support\Authentication
 */
class UserWebAuthentication implements UserAuthenticationContract
{

    /**
     * @var AuthFactory
     */
    private $auth;

    /**
     * UserAuthentication constructor.
     * @param AuthFactory $auth
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        if($this->auth->guard('web')->check()) {
            return $this->auth->guard('web')->user();
        }
        return null;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->auth->guard('web')->login($user);
    }
    
}