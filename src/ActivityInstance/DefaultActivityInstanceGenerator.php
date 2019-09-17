<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use BristolSU\Support\ActivityInstance\Contracts\DefaultActivityInstanceGenerator as DefaultActivityInstanceGeneratorContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Generate an activity instance using the repository
 */
class DefaultActivityInstanceGenerator implements DefaultActivityInstanceGeneratorContract
{

    /**
     * Holds the activity instance repository
     * 
     * @var ActivityInstanceRepositoryContract
     */
    private $repository;

    /**
     * Initialise the generator
     * 
     * @param ActivityInstanceRepositoryContract $repository Repository to resolve the activity instance from.
     */
    public function __construct(ActivityInstanceRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Find or create the default activity instance.
     * 
     * This method first tries to find the first activity instance and return it.
     * If no activity instances are found, one is created.
     * 
     * @param Activity $activity Activity with which the activity instance should be associated with.
     * @param string $resourceType User, group or role. The resource type for the activity instance
     * @param string $resourceId The ID of the user, group or role.
     * 
     * @return ActivityInstance
     */
    public function generate(Activity $activity, string $resourceType, string $resourceId): ActivityInstance
    {
        try {
            $activityInstance = $this->repository->firstFor($activity->id, $resourceType, $resourceId);
        } catch (ModelNotFoundException $e) {
            $activityInstance = $this->repository->create($activity->id, $resourceType, $resourceId, $activity->name,
                'Default activity instance for activity '.$activity->name.' (#'.$activity->id.')');
        }
        return $activityInstance;
    }
}