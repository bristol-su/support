<?php

namespace BristolSU\Support\Http\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class InjectJavascriptVariables
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
        
        JavaScriptFacade::put([
            'ALIAS' => $request->route('module_instance_slug')->alias,
            'ACTIVITY_SLUG' => $request->route('activity_slug')->slug,
            'MODULE_INSTANCE_SLUG' => $request->route('module_instance_slug')->slug,
            'user' => $this->authentication->getUser(),
            'group' => $this->authentication->getGroup(),
            'role' => $this->authentication->getRole()
        ]);
        
        return $next($request);
    }

}