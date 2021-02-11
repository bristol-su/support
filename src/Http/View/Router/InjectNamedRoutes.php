<?php

namespace BristolSU\Support\Http\View\Router;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class InjectNamedRoutes
{
    /**
     * @var NamedRouteRetrieverInterface
     */
    private NamedRouteRetrieverInterface $retriever;

    /**
     * @var Request
     */
    private Request $request;

    public function __construct(NamedRouteRetrieverInterface $retriever, Request $request)
    {
        $this->retriever = $retriever;
        $this->request = $request;
    }

    public function compose(View $view)
    {
        JavaScriptFacade::put([
            'named_routes' => $this->retriever->all(),
            'current_route' => $this->retriever->currentRouteName(),
            'base_url' => $this->request->getBaseUrl()
        ]);
    }
}
