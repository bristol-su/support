<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use Illuminate\Http\Request;

class CheckActivityInstanceForActivity
{
    /**
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    public function __construct(ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    public function handle(Request $request, \Closure $next)
    {
        $activityInstance = $this->activityInstanceResolver->getActivityInstance();
        
        if($activityInstance->activity_id !== $request->route('activity_slug')->id) {
            throw new NotInActivityInstanceException('Not logged into the correct activity instance for the activity');
        }
        return $next($request);
        
    }
}