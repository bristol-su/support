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
class InjectValidationErrors
{

    public function compose(View $view)
    {
        $data = $view->getData();
        JavaScriptFacade::put([
          'server_validation_errors' => (
            array_key_exists('errors', $data) && $data['errors'] instanceof ViewErrorBag
            ? ($data['errors'])->toArray() : []
          )
        ]);

    }

}
