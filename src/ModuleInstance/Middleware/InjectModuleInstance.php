<?php


namespace BristolSU\Support\ModuleInstance\Middleware;


use BristolSU\Support\ModuleInstance\ModuleInstance;
use Closure;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Request;

/**
 * Inject the module instance into the container
 */
class InjectModuleInstance
{

    /**
     * Holds a reference to the container to inject the module instance into 
     * 
     * @var Container
     */
    private $app;

    /**
     * @param Container $app Container to inject the module instance into
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Inject the module instance into the container
     * 
     * @param Request $request Request object
     * @param Closure $next Next middleware callback
     * 
     * @return mixed
     */
    // TODO Inject as a string
    public function handle(Request $request, Closure $next)
    {
        $moduleInstance = $request->route('module_instance_slug');
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        return $next($request);
    }

}
