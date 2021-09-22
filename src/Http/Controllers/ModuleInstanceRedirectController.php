<?php

namespace BristolSU\Support\Http\Controllers;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Http\Request;

/**
 * Redirect to a module page.
 */
class ModuleInstanceRedirectController
{
    /**
     * Redirect the general activity-slug/module-instance-slug to the specific module URL.
     *
     * @param Request $request Request object
     * @param Activity $activity Current activity
     * @param ModuleInstance $moduleInstance Current module instance
     *
     * @return \Illuminate\Http\RedirectResponse Redirection to the true module url
     */
    public function index(Request $request, Activity $activity, ModuleInstance $moduleInstance)
    {
        $url = $request->path() . '/' . $moduleInstance->alias();
        if ($request->query->count() > 0) {
            $url .= '?' . $request->getQueryString();
        }

        return redirect()->to($url);
    }
}
