<?php


namespace BristolSU\Support\Activity\Middleware;

use BristolSU\Support\Activity\Activity;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;

/**
 * Inject the activity instance into the container.
 */
class InjectActivity
{
    /**
     * Holds a reference to the container.
     *
     * @var Container
     */
    private $app;

    /**
     * Initialise the middleware.
     *
     * @param Container $app The container to bind the activity to.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Bind the activity to the container.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $activity = $request->route('activity_slug');
        $this->app->instance(Activity::class, $activity);

        return $next($request);
    }
}
