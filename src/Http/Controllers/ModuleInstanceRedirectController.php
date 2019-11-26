<?php

namespace BristolSU\Support\Http\Controllers;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Http\Request;

class ModuleInstanceRedirectController
{
    public function index(Request $request, Activity $activity, ModuleInstance $moduleInstance)
    {
        return redirect()
            ->to($request->path() . '/' . $moduleInstance->alias());
    }
}