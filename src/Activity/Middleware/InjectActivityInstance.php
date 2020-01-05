<?php


namespace BristolSU\Support\Activity\Middleware;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;

/**
 * Inject the activity instance into the container.
 */
class InjectActivityInstance
{

    /**
     * Holds a reference to the container
     * 
     * @var Container
     */
    private $app;

    /**
     * Initialise the middleware
     * 
     * @param Container $app The container to bind the activity to.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Bind the activity to the container
     * 
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // TODO Should bind activity to the string 'activity', to avoid confusion when resolving
        
        $activity = $request->route('activity_slug');
        $this->app->instance(Activity::class, $activity);

        return $next($request);
    }
}
