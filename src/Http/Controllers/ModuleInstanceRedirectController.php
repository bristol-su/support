<?php

namespace BristolSU\Support\Http\Controllers;

use BristolSU\Module\UploadFile\Http\Controllers\Controller;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Http\Request;

class ModuleInstanceRedirectController extends Controller
{
    public function index(Request $request, Activity $activity, ModuleInstance $moduleInstance)
    {
        return redirect()
            ->to($request->path() . '/' . $moduleInstance->alias());
    }
}