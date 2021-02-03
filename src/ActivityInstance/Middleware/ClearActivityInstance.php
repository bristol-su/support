<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use Illuminate\Http\Request;

/**
 * Clear the current activity instance from the resolver.
 */
class ClearActivityInstance
{
    /**
     * Holds the activity instance resolver.
     *
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    /**
     * Initialise the middleware.
     *
     * @param ActivityInstanceResolver $activityInstanceResolver Resolver to clear the activity instance from
     */
    public function __construct(ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    /**
     * Clears the activity instance from the resolver.
     *
     * @param Request $request Request object
     * @param \Closure $next Next middleware callback
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $this->activityInstanceResolver->clearActivityInstance();

        return $next($request);
    }
}
