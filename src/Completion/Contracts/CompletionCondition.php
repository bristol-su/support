<?php

namespace BristolSU\Support\Completion\Contracts;

use BristolSU\Module\UploadFile\Models\File;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ModuleInstance\Contracts\ModuleInstance;

interface CompletionCondition
{
    public function isComplete($settings, ActivityInstance $activityInstance, ModuleInstance $moduleInstance): bool;

    public function options(): array;
}