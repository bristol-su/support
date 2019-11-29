<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Permissions\Facade\Permission;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;

class ApiActivityInstanceResolver implements ActivityInstanceResolver
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ActivityInstanceRepositoryContract
     */
    private $activityInstanceRepository;

    /**
     * @param Request $request
     */
    public function __construct(Request $request, ActivityInstanceRepositoryContract $activityInstanceRepository)
    {
        $this->request = $request;
        $this->activityInstanceRepository = $activityInstanceRepository;
    }

    /**
     * @param ActivityInstance $activityInstance
     */
    public function setActivityInstance(ActivityInstance $activityInstance)
    {
        throw new \Exception('Cannot set an activity instance when using the API');
    }

    /**
     * @return ActivityInstance
     * @throws NotInActivityInstanceException
     */
    public function getActivityInstance(): ActivityInstance
    {
        if($this->request->has('activity_instance_id')) {
            return $this->activityInstanceRepository->getById(
                $this->request->input('activity_instance_id')
            );
        }
        // TODO get the default activity instance if it's not a multi-completable activity. This will allow us to only enforce using activity_instance_id for a multi completable activity
        
        throw new NotInActivityInstanceException;
    }

    public function clearActivityInstance()
    {
        throw new \Exception('Cannot clear an activity instance when using the API');
    }
}