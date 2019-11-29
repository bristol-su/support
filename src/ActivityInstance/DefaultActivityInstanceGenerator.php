<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use BristolSU\Support\ActivityInstance\Contracts\DefaultActivityInstanceGenerator as DefaultActivityInstanceGeneratorContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DefaultActivityInstanceGenerator implements DefaultActivityInstanceGeneratorContract
{

    /**
     * @var ActivityInstanceRepositoryContract
     */
    private $repository;

    public function __construct(ActivityInstanceRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

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