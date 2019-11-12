<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ModuleInactive;
use BristolSU\Support\Logic\Facade\LogicTester;
use Illuminate\Http\Request;

class CheckModuleInstanceActive
{

    /**
     * @var Authentication
     */
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function handle(Request $request, \Closure $next)
    {
        $moduleInstance = $request->route('module_instance_slug');
        if(!LogicTester::evaluate($moduleInstance->activeLogic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
            throw new ModuleInactive('The module instance is currently inactive', 403, null, $moduleInstance);
        }
        
        return $next($request);
    }
    
}