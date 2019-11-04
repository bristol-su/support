<?php

namespace BristolSU\Support\Authentication\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Facade\LogicTester;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\Request;

class CheckValidUserParametersGiven
{

    /**
     * @var Authentication
     */
    private $authentication;
    /**
     * @var Factory
     */
    private $authFactory;

    public function __construct(Authentication $authentication, Factory $authFactory)
    {
        $this->authentication = $authentication;
        $this->authFactory = $authFactory;
    }

    public function handle(Request $request, \Closure $next)
    {
        // TODO Refactor. Need authentication to be either the APIAuthentication or WebAuthenticaton
        
        $activity = $request->route('activity_slug');

        if(!LogicTester::evaluate(
            $activity->adminLogic, 
            $this->authFactory->guard('user')->user(),
            $this->authFactory->guard('group')->user(),
            $this->authFactory->guard('role')->user())) {
            
            /*
             * Check we own the user, the group and/or the role IF GIVEN in the request
             */
            
        }
        
        return $next($request);
    }

}