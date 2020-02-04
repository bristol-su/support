<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserAuthentication;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Resolve a user from the API authentication
 */
class UserApiAuthentication implements UserAuthentication
{
    /**
     *  User authentication factory for resolving and setting users
     * 
     * @var AuthFactory
     */
    private $auth;

    /**
     * @param AuthFactory $auth Factory to resolve the user from
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }
    
    /**
     * Get the user from the API authentication
     * 
     * @return User|null Logged in user, or null if no user found.
     */
    public function getUser(): ?User
    {
        if ($this->auth->guard('api')->check()) {
            return $this->auth->guard('api')->user();
        }
        return null;
    }

    /**
     * Set a user. This method cannot be used since the user cannot be set for an API authentication
     *
     * @param User $user User to log in
     * @return void
     * @throws \Exception Always, the user cannot be set.
     */
    public function setUser(User $user)
    {
        throw new \Exception('Cannot set an API user');
    }

    /**
     * Log out of the current user
     *
     * @return void
     * @throws \Exception
     */
    public function logout(): void
    {
        throw new \Exception('Cannot log out an API user');
    }
}