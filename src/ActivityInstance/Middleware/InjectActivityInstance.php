<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;

class InjectActivityInstance
{

    /**
     * @var Application
     */
    private $app;
    
    /**
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    public function __construct(Application $app, ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->app = $app;
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    public function handle(Request $request, Closure $next)
    {

        $activityInstance = $this->activityInstanceResolver->getActivityInstance();
        $this->app->instance(ActivityInstance::class, $activityInstance);

        return $next($request);
    }

}