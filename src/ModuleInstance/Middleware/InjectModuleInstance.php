<?php


namespace BristolSU\Support\ModuleInstance\Middleware;


use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InjectModuleInstance
 * @package BristolSU\Support\ModuleInstance\Middleware
 */
class InjectModuleInstance
{

    /**
     * @var Container
     */
    private $app;

    /**
     * InjectModuleInstance constructor.
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
    // TODO Inject as a string
    public function handle(Request $request, \Closure $next)
    {
        $moduleInstance = $request->route('module_instance_slug');
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        return $next($request);
    }

}
