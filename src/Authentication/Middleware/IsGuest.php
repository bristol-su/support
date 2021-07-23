<?php

namespace BristolSU\Support\Authentication\Middleware;

use BristolSU\Auth\Settings\Access\DefaultHome;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Exception\IsAuthenticatedException;
use Illuminate\Http\Request;

class IsGuest
{

    /**
     * @var Authentication
     */
    private Authentication $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Check a user is not logged in
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws IsAuthenticatedException If the user is logged in
     */
    public function handle(Request $request, \Closure $next)
    {
        if($this->authentication->hasUser()) {
            throw new IsAuthenticatedException();
        }
        return $next($request);
    }

}
