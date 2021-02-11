<?php

namespace BristolSU\Support\Http\View;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

/**
 * Inject Javascript Variables.
 */
class InjectJavascriptVariables
{
    /**
     * @var Authentication
     */
    private Authentication $authentication;

    /**
     * @var ActivityInstanceResolver
     */
    private ActivityInstanceResolver $activityInstanceResolver;

    /**
     * @var Request
     */
    private Request $request;

    public function __construct(Authentication $authentication, ActivityInstanceResolver $activityInstanceResolver, Request $request)
    {
        $this->authentication = $authentication;
        $this->activityInstanceResolver = $activityInstanceResolver;
        $this->request = $request;
    }

    public function compose(View $view)
    {
        JavaScriptFacade::put([
            'admin' => $this->request->is('a/*'),
            'user' => $this->authentication->getUser(),
            'group' => $this->authentication->getGroup(),
            'role' => $this->authentication->getRole(),
            'activity' => ($this->request->has('activity_slug') ? $this->request->route('activity_slug') : null),
            'activity_instance' => $this->getActivityInstance(),
            'module_instance' => ($this->request->has('module_instance_slug') ? $this->request->route('module_instance_slug') : null)
        ]);
    }

    private function getActivityInstance()
    {
        try {
            return $this->activityInstanceResolver->getActivityInstance();
        } catch (NotInActivityInstanceException $e) {
            return null;
        }
    }
}
