<?php

namespace BristolSU\Support\ActivityInstance\Contracts;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Support\Collection;

/**
 * A repository for retrieving Activity Instances.
 */
interface ActivityInstanceRepository
{
    /**
     * Get the first activity instance found with the given parameters.
     *
     * @param int $activityId ID of the activity to which the activity instance should belong
     * @param string $resourceType Resource (owner) type. One of user, group or role.
     * @param int $resourceId Resource (owner) id. ID of the model in resource type.
     * @return ActivityInstance
     */
    public function firstFor(int $activityId, string $resourceType, int $resourceId): ActivityInstance;

    /**
     * Create a new Activity Instance.
     *
     * @param int $activityId ID of the activity to which the activity instance should belong
     * @param string $resourceType Resource (owner) type. One of user, group or role.
     * @param int $resourceId Resource (owner) id. ID of the model in resource type.
     * @param string $name Name of the activity instance.
     * @param string|null $description Description for the activity instance.
     *
     * @return ActivityInstance
     */
    public function create(int $activityId, string $resourceType, int $resourceId, string $name, ?string $description): ActivityInstance;

    /**
     * Get an activity instance by ID.
     *
     * @param int $id ID of the activity instance
     * @return ActivityInstance
     */
    public function getById($id): ActivityInstance;

    /**
     * Get all activity instances with the given parameters.
     *
     * @param int $activityId ID of the activity to which the activity instances should belong
     * @param string $resourceType Resource (owner) type. One of user, group or role.
     * @param int $resourceId Resource (owner) id. ID of the model in resource type.
     *
     * @return Collection
     */
    public function allFor(int $activityId, string $resourceType, int $resourceId): Collection;

    /**
     * Get all activity instances belonging to an activity.
     *
     * @param int $activityId ID Of the activity
     *
     * @return Collection
     */
    public function allForActivity(int $activityId): Collection;

    /**
     * Get all activity instances for the given resource.
     *
     * @param string $resourceType Resource (owner) type. One of user, group or role.
     * @param int $resourceId Resource (owner) id. ID of the model in resource type.
     *
     * @return Collection
     */
    public function allForResource(string $resourceType, int $resourceId): Collection;
}
