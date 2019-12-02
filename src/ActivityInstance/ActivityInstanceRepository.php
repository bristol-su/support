<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use Illuminate\Support\Collection;

class ActivityInstanceRepository implements ActivityInstanceRepositoryContract
{

    public function firstFor(int $activityId, string $resourceType, int $resourceId): ActivityInstance
    {
        return ActivityInstance::where([
            'activity_id' => $activityId,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId
        ])->firstOrFail();
    }

    public function create(int $activityId, string $resourceType, int $resourceId, string $name, ?string $description): ActivityInstance
    {
        return ActivityInstance::create([
            'activity_id' => $activityId,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'name' => $name,
            'description' => $description
        ]);
    }

    public function getById($id): ActivityInstance
    {
        return ActivityInstance::findOrFail($id);
    }

    public function allFor(int $activityId, string $resourceType, int $resourceId): Collection
    {
        return ActivityInstance::where([
            'activity_id' => $activityId,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId
        ])->get();
    }

    public function allForActivity(int $activityId): Collection
    {
        return ActivityInstance::where('activity_id', $activityId)->get();
    }
}