<?php

namespace BristolSU\Support\Authentication\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class IsAuthenticated
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
     * Check the user is logged in
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws AuthenticationException If a user is not logged in
     */
    public function handle(Request $request, \Closure $next)
    {
        if($this->authentication->hasUser()) {
            return $next($request);
        }
        throw new AuthenticationException();
    }

}
