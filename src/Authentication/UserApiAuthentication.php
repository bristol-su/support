<?php

namespace BristolSU\Support\Authentication;

use BristolSU\Support\Authentication\Contracts\UserAuthentication;
use BristolSU\Support\User\User;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class UserApiAuthentication implements UserAuthentication
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
        if($this->auth->guard('api')->check()) {
            return $this->auth->guard('api')->user();
        }
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function setUser(User $user)
    {
        $this->auth->guard('api')->login($user);

    }
}