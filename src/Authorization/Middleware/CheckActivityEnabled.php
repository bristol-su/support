<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authorization\Exception\ActivityDisabled;
use Illuminate\Http\Request;

/**
 * Is the activity enabled?
 */
class CheckActivityEnabled
{

    /**
     * Check if the activity is enabled
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws ActivityDisabled If the module is not active
     */
    public function handle(Request $request, \Closure $next)
    {
        $activity = $request->route('activity_slug');
        if(!$activity->enabled) {
            throw ActivityDisabled::fromActivity($activity);
        }
        return $next($request);
    }
    
}