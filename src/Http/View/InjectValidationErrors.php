<?php

namespace BristolSU\Support\Http\View;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ViewErrorBag;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

/**
 * Inject Javascript Variables.
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
