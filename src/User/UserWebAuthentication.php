<?php

namespace BristolSU\Support\User;

use BristolSU\Support\User\Contracts\UserAuthentication;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Resolve users using the Laravel authentication.
 */
class UserWebAuthentication implements UserAuthentication
{
    /**
     * Authentication factory for resolving and setting users.
     *
     * @var AuthFactory
     */
    private $auth;

    /**
     * @param AuthFactory $auth Auth factory for resolving and setting users with Laravel
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get the currently logged in user.
     *
     * @return User|null Null if no user found
     */
    public function getUser(): ?User
    {
        if ($this->auth->guard('web')->check()) {
            return $this->auth->guard('web')->user();
        }

        return null;
    }

    /**
     * Set the logged in user.
     *
     * @param User $user User to set
     */
    public function setUser(User $user)
    {
        $this->auth->guard('web')->login($user);
    }

    /**
     * Log out of the current user.
     *
     */
    public function logout(): void
    {
        $this->auth->guard('web')->logout();
    }
}
