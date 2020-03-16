<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\User\Contracts\UserAuthentication;
use Illuminate\Http\Request;

class CheckAdminIsAtLeastUser
{

    /**
     * @var Authentication
     */
    private $authentication;
    /**
     * @var UserAuthentication
     */
    private $userAuthentication;

    public function __construct(Authentication $authentication, UserAuthentication $userAuthentication)
    {
        $this->authentication = $authentication;
        $this->userAuthentication = $userAuthentication;
    }

    /**
     * Set the user if it is not already set
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if($this->authentication->getUser() === null) {
            $this->authentication->setUser(
                $this->userAuthentication->getUser()->controlUser()
            );
        }
        return $next($request);
    }
    
}