<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use Closure;
use Illuminate\Http\Request;

class InjectActivityInstance
{

    /**
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    public function __construct(ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    public function handle(Request $request, Closure $next)
    {
        
        $activityInstance = $this->activityInstanceResolver->getActivityInstance();
        app()->instance(ActivityInstance::class, $activityInstance);
        
        return $next($request);
    }

}