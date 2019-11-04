<?php

namespace BristolSU\Support\Http\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

/**
 * Class InjectJavascriptVariables
 * @package BristolSU\Support\Http\Middleware
 */
class InjectJavascriptVariables
{

    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * InjectJavascriptVariables constructor.
     * @param Authentication $authentication
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        
        JavaScriptFacade::put([
            'ALIAS' => $request->route('module_instance_slug')->alias,
            'ACTIVITY_SLUG' => $request->route('activity_slug')->slug,
            'MODULE_INSTANCE_SLUG' => $request->route('module_instance_slug')->slug,
            'A_OR_P' => ($request->is('a/*')?'a':'p'),
            'user' => $this->authentication->getUser(),
            'group' => $this->authentication->getGroup(),
            'role' => $this->authentication->getRole()
        ]);
        
        return $next($request);
    }

}