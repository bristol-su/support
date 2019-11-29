<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use Illuminate\Http\Request;

class LogIntoActivityInstance
{

    /**
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;
    /**
     * @var ActivityInstanceRepository
     */
    private $activityInstanceRepository;

    public function __construct(ActivityInstanceRepository $activityInstanceRepository, ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
        $this->activityInstanceRepository = $activityInstanceRepository;
    }

    /**
     * Log into an activity instance if the ID is passed in the request
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if($request->has('aiid')) {
            $activityInstance = $this->activityInstanceRepository->getById($request->input('aiid'));
            $this->activityInstanceResolver->setActivityInstance($activityInstance);
        }
        return $next($request);
    }
    
}