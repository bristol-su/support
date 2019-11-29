<?php

namespace BristolSU\Support\ActivityInstance\Contracts;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;

interface DefaultActivityInstanceGenerator
{

    public function generate(Activity $activity, string $resourceType, string $resourceId): ActivityInstance;
    
}