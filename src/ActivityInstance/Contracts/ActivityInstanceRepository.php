<?php

namespace BristolSU\Support\ActivityInstance\Contracts;

use BristolSU\Support\ActivityInstance\ActivityInstance;

interface ActivityInstanceRepository
{

    public function firstFor(int $activityId, string $resourceType, int $resourceId): ActivityInstance;

    public function create(int $activityId, string $resourceType, int $resourceId, string $name, ?string $description): ActivityInstance;
}