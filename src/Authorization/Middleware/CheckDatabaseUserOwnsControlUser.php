<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\IncorrectLogin;
use BristolSU\Support\User\Contracts\UserAuthentication;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

/**
 * Check the database user, if logged in, owns the control user, if logged in
 */
class CheckDatabaseUserOwnsControlUser
{

    /**
     * Holds the logged in database user
     *
     * @var UserAuthentication
     */
    private $userAuthentication;

    /**
     * Holds the logged in control user
     *
     * @var Authentication
     */
    private $authentication;

    /**
     * Initialise the middleware
     *
     * @param UserAuthentication $userAuthentication Database user resolver
     * @param Authentication $authentication Control user resolver
     */
    public function __construct(UserAuthentication $userAuthentication, Authentication $authentication)
    {
        $this->userAuthentication = $userAuthentication;
        $this->authentication = $authentication;
    }

    /**
     * Throw an error if the logged in user is not owned by the logged in database user
     *
     * @param Request $request The request object
     * @param \Closure $next The next middleware in the pipeline
     * @return mixed
     * @throws IncorrectLogin
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($this->hasUsers() && !$this->usersAreEqual()) {
            throw new IncorrectLogin('Logged into incorrect user');
        }

        return $next($request);
    }

    /**
     * Check if the authentication and the User Authentication have the users to check.
     *
     * @return bool
     */
    private function hasUsers(): bool
    {
        return $this->userAuthentication->getUser() !== null && $this->authentication->getUser() !== null;
    }

    private function usersAreEqual(): bool
    {
        return $this->userAuthentication->getUser()->controlUser()->id() === $this->authentication->getUser()->id();
    }
}