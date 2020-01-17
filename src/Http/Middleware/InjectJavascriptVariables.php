<?php

namespace BristolSU\Support\Http\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

/**
 * Inject Javascript Variables
 */
class InjectJavascriptVariables
{

    /**
     * Authentication resolver
     * 
     * @var Authentication
     */
    private $authentication;
    
    /**
     * Activity Instance Resolver
     * 
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    /**
     * @param Authentication $authentication Authentication resolver
     * @param ActivityInstanceResolver $activityInstanceResolver Activity instance resolver
     */
    public function __construct(Authentication $authentication, ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->authentication = $authentication;
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    /**
     * Inject variables to be used in JavaScript
     *
     * @param Request $request Request Object
     * @param \Closure $next Next middleware callback
     * @return mixed 
     * @throws NotInActivityInstanceException If an activity instance is not found
     */
    public function handle(Request $request, \Closure $next)
    {
        JavaScriptFacade::put([
            'ALIAS' => $request->route('module_instance_slug')->alias,
            'ACTIVITY_SLUG' => $request->route('activity_slug')->slug,
            'MODULE_INSTANCE_SLUG' => $request->route('module_instance_slug')->slug,
            'A_OR_P' => ($request->is('a/*') ? 'a' : 'p'),
            'user' => $this->authentication->getUser(),
            'group' => $this->authentication->getGroup(),
            'role' => $this->authentication->getRole(),
            'activityinstance' => $this->activityInstanceResolver->getActivityInstance(),
            'moduleinstance' => $request->route('module_instance_slug'),
            'data_user' => ($this->authentication->getUser() === null ?null:$this->authentication->getUser()->data())
        ]);
        
        return $next($request);
    }

}