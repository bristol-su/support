<?php

namespace BristolSU\Support\Http\View\Router;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class NamedRouteRetriever implements NamedRouteRetrieverInterface
{

    /**
     * @var Router
     */
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function all(): array
    {
        return collect($this->router->getRoutes()->getRoutesByName())
          ->map(function (Route $route) {
              return $route->uri();
          })->toArray();
    }

}
