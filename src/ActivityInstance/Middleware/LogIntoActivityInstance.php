<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use Closure;
use Illuminate\Http\Request;

/**
 * Log into an activity if passed in the request
 */
class LogIntoActivityInstance
{

    /**
     * Resolver to set the activity instance in
     *
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;
    
    /**
     * Repository to retrieve the activity instance from
     *
     * @var ActivityInstanceRepository
     */
    private $activityInstanceRepository;

    /**
     * Initialise the middleware
     *
     * @param ActivityInstanceRepository $activityInstanceRepository Repository to retrieve the activity instance from
     * @param ActivityInstanceResolver $activityInstanceResolver Resolver to set the activity instance in
     */
    public function __construct(ActivityInstanceRepository $activityInstanceRepository, ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
        $this->activityInstanceRepository = $activityInstanceRepository;
    }

    /**
     * Log into an activity instance if the ID is passed in the request
     *
     * @param Request $request Request object
     * @param Closure $next Next middleware callback
     * 
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('aiid')) {
            $activityInstance = $this->activityInstanceRepository->getById($request->input('aiid'));
            $this->activityInstanceResolver->setActivityInstance($activityInstance);
        }
        return $next($request);
    }

}