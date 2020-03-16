<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\ResourceIdGenerator;
use Illuminate\Http\Request;

class CheckActivityInstanceAccessible
{

    /**
     * Gets the current activity instance
     * 
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;
    
    /**
     * Generates an ID for the resource
     * 
     * @var ResourceIdGenerator
     */
    private $resourceIdGenerator;

    public function __construct(ActivityInstanceResolver $activityInstanceResolver, ResourceIdGenerator $resourceIdGenerator)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
        $this->resourceIdGenerator = $resourceIdGenerator;
    }

    /**
     * Throw an exception if the activity instance resource ID is not logged in currently
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws NotInActivityInstanceException
     */
    public function handle(Request $request, \Closure $next)
    {
        $activityInstance = $this->activityInstanceResolver->getActivityInstance();
        $resourceId = $this->resourceIdGenerator->fromString($activityInstance->activity->activity_for);
        if((int) $activityInstance->resource_id !== (int) $resourceId) {
            throw new NotInActivityInstanceException('Incorrect activity instance set');
        }
        
        return $next($request);
    }
    
}