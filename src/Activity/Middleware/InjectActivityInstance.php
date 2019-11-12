<?php


namespace BristolSU\Support\Activity\Middleware;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;

/**
 * Class InjectActivityInstance
 * @package BristolSU\Support\Activity\Middleware
 */
class InjectActivityInstance
{

    /**
     * @var Container
     */
    private $app;

    /**
     * InjectActivityInstance constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
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
