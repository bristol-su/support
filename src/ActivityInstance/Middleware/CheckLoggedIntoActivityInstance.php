<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLoggedIntoActivityInstance
{

    /**
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    public function __construct(ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    /**
     * Check we're logged into an activity instance
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws NotInActivityInstanceException
     */
    public function handle(Request $request, \Closure $next)
    {
        try {
            $this->activityInstanceResolver->getActivityInstance();
        } catch (\Exception $exception) {
            // We're not currently in an activity instance, so we should throw an exception
            // The exception handler should gracefully handle this exception
            throw new NotInActivityInstanceException;
        }
        return $next($request);
    }

}