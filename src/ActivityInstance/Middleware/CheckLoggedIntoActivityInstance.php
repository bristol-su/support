<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use Illuminate\Http\Request;

/**
 * Check an activity instance is logged in to.
 */
class CheckLoggedIntoActivityInstance
{
    /**
     * Holds the activity instance resolver.
     *
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    /**
     * Initialise middleware.
     *
     * @param ActivityInstanceResolver $activityInstanceResolver Resolver to get the activity instance.
     */
    public function __construct(ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    /**
     * Check we're logged into an activity instance.
     *
     * @param Request $request Request Object
     * @param \Closure $next Next middleware callback
     * @throws NotInActivityInstanceException If an activity instance is not found
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        try {
            $this->activityInstanceResolver->getActivityInstance();
        } catch (\Exception $exception) {
            // We're not currently in an activity instance, so we should throw an exception
            // The exception handler should gracefully handle this exception
            throw new NotInActivityInstanceException();
        }

        return $next($request);
    }
}
