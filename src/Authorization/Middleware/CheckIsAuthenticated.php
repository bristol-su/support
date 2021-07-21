<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class CheckIsAuthenticated
{

    private Authentication $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function handle(Request $request, \Closure $next)
    {
        if(!$this->authentication->hasUser()) {
            throw new AuthenticationException('Unauthenticated.');
        }

        return $next($request);
    }

}
