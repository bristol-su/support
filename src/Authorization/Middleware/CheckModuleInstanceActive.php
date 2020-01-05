<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ModuleInactive;
use BristolSU\Support\Logic\Facade\LogicTester;
use Illuminate\Http\Request;

/**
 * Is the module instance active?
 */
class CheckModuleInstanceActive
{

    /**
     * Holds the authentication
     * 
     * @var Authentication
     */
    private $authentication;

    /**
     * Initialise middleware
     * .
     * @param Authentication $authentication Authentication to get models from
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Check if th module instance is active
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ModuleInactive If the module is not active
     */
    public function handle(Request $request, \Closure $next)
    {
        $moduleInstance = $request->route('module_instance_slug');
        if(!LogicTester::evaluate($moduleInstance->activeLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
            throw new ModuleInactive('The module instance is currently inactive', 403, null, $moduleInstance);
        }
        
        return $next($request);
    }
    
}