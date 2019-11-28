<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use Illuminate\Http\Request;

class ClearActivityInstance
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
        $this->activityInstanceResolver->clearActivityInstance();
        return $next($request);
    }
}