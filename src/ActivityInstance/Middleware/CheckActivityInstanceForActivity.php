<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use Illuminate\Http\Request;

/**
 * Check the activity instance belongs to the activity being accessed.
 */
class CheckActivityInstanceForActivity
{
    /**
     * Holds the activity instance resolver
     * 
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    /**
     * Initialise the middleware
     * 
     * @param ActivityInstanceResolver $activityInstanceResolver Resolver to get the current activity instance.
     */
    public function __construct(ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    /**
     * Check the logged in activity instance is for the relevant activity
     * 
     * @param Request $request Request object
     * @param \Closure $next Next middleware callback
     * @return mixed
     * @throws NotInActivityInstanceException If the activity instance does not belong to the activity
     */
    public function handle(Request $request, \Closure $next)
    {
        $activityInstance = $this->activityInstanceResolver->getActivityInstance();
        if ($activityInstance->activity_id !== $request->route('activity_slug')->id) {
            throw new NotInActivityInstanceException('Not logged into the correct activity instance for the activity');
        }
        return $next($request);
        
    }
}