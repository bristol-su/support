<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserAuthentication;
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
        return null;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function setUser(User $user)
    {
        throw new \Exception('Cannot set an API user');
    }
}