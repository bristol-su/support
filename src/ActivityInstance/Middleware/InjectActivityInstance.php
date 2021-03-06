<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;

/**
 * Inject the activity instance into the container.
 */
class InjectActivityInstance
{
    /**
     * Container to bind the activity instance in to.
     * @var Container
     */
    private $container;
    
    /**
     * Holds the activity instance resolver to resolve the activity instance from.
     *
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;

    /**
     * Middleware initialiser.
     *
     * @param Container $container Container to bind the activity instance in to
     * @param ActivityInstanceResolver $activityInstanceResolver Resolver to get the activity instance
     */
    public function __construct(Container $container, ActivityInstanceResolver $activityInstanceResolver)
    {
        $this->container = $container;
        $this->activityInstanceResolver = $activityInstanceResolver;
    }

    /**
     * Binds the activity instance from the resolver into the container.
     *
     * @param Request $request
     * @param Closure $next
     * @throws NotInActivityInstanceException
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $activityInstance = $this->activityInstanceResolver->getActivityInstance();
        $this->container->instance(ActivityInstance::class, $activityInstance);

        return $next($request);
    }
}
