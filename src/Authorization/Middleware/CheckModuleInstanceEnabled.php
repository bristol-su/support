<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authorization\Exception\ModuleInstanceDisabled;
use Illuminate\Http\Request;

/**
 * Is the module instance enabled?
 */
class CheckModuleInstanceEnabled
{

    /**
     * Check if the module instance is enabled
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ModuleInstanceDisabled If the module is not active
     */
    public function handle(Request $request, \Closure $next)
    {
        $moduleInstance = $request->route('module_instance_slug');
        if(!$moduleInstance->enabled) {
            throw ModuleInstanceDisabled::fromModuleInstance($moduleInstance);
        }
        return $next($request);
    }
    
}