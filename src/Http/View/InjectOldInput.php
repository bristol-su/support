<?php

namespace BristolSU\Support\Http\View;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

/**
 * Inject Javascript Variables
 */
class InjectOldInput
{

    /**
     * @var Request
     */
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {
        JavaScriptFacade::put([
          'old_input' => $this->request->old()
        ]);

    }

}
