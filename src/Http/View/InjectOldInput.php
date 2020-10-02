<?php

namespace BristolSU\Support\Http\View;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\User\Contracts\UserAuthentication;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;
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
