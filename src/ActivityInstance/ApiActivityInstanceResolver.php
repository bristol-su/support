<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Permissions\Facade\Permission;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;

/**
 * Resolve an activity instance when using the API
 */
class ApiActivityInstanceResolver implements ActivityInstanceResolver
{
    /**
     * Holds the request object
     * 
     * @var Request
     */
    private $request;
    
    /**
     * Holds the activity instance repository
     * 
     * @var ActivityInstanceRepositoryContract
     */
    private $activityInstanceRepository;

    /**
     * Initialise the Activity Instance resolver.
     *
     * @param Request $request Request object
     * @param ActivityInstanceRepositoryContract $activityInstanceRepository Repository to resolve the activity instance from.
     */
    public function __construct(Request $request, ActivityInstanceRepositoryContract $activityInstanceRepository)
    {
        $this->request = $request;
        $this->activityInstanceRepository = $activityInstanceRepository;
    }

    /**
     * Set the activity instance.
     * 
     * For this resolver, the activity instance id must always be passed through the request object, so this method 
     * will throw an exception.
     * 
     * @param ActivityInstance $activityInstance
     * @throws \Exception
     */
    public function setActivityInstance(ActivityInstance $activityInstance)
    {
        throw new \Exception('Cannot set an activity instance when using the API');
    }

    /**
     * Gets the activity instance
     * 
     * The activity instance will be retrieved from the repository using the ID found in the query string under the key
     * 'activity_instance_id'. If not found, a NotInActivityInstanceException will be thrown
     * 
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
        
        throw new NotInActivityInstanceException;
    }

    /**
     * Clear the activity instance
     * 
     * For the API, the activity instance is always set in the query string in the request object, so this
     * method throws an exception.
     * 
     * @throws \Exception
     */
    public function clearActivityInstance()
    {
        throw new \Exception('Cannot clear an activity instance when using the API');
    }
}