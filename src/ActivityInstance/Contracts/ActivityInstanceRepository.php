<?php

namespace BristolSU\Support\ActivityInstance\Contracts;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Support\Collection;

interface ActivityInstanceRepository
{

    public function firstFor(int $activityId, string $resourceType, int $resourceId): ActivityInstance;

    public function create(int $activityId, string $resourceType, int $resourceId, string $name, ?string $description): ActivityInstance;

    public function getById($id): ActivityInstance;

    public function allFor(int $activityId, string $resourceType, int $resourceId): Collection;
}