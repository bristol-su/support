<?php

namespace BristolSU\Support\Http\View\Router;

use Illuminate\Contracts\View\View;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class InjectNamedRoutes
{

    /**
     * @var NamedRouteRetriever
     */
    private NamedRouteRetriever $retriever;

    public function __construct(NamedRouteRetriever $retriever)
    {
        $this->retriever = $retriever;
    }

    public function compose(View $view)
    {
        JavaScriptFacade::put([
          'named_routes' => $this->retriever->all()
        ]);
    }

}
