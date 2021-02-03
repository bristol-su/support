<?php

namespace BristolSU\Support\ActivityInstance;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository as ActivityInstanceRepositoryContract;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use Illuminate\Http\Request;

/**
 * Resolve an activity instance when using the API.
 */
class WebRequestActivityInstanceResolver implements ActivityInstanceResolver
{
    /**
     * Holds the request object.
     *
     * @var Request
     */
    private $request;

    /**
     * Holds the activity instance repository.
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
        $this->request->query->set('a', $activityInstance->id);
        $this->request->overrideGlobals();
    }

    /**
     * Gets the activity instance.
     *
     * The activity instance will be retrieved from the repository using the ID found in the query string under the key
     * 'a'. If not found, a NotInActivityInstanceException will be thrown
     *
     * @throws NotInActivityInstanceException
     * @return ActivityInstance
     */
    public function getActivityInstance(): ActivityInstance
    {
        if ($this->request->query->has('a')) {
            return $this->activityInstanceRepository->getById(
                $this->request->query->get('a')
            );
        }

        throw new NotInActivityInstanceException();
    }

    /**
     * Clear the activity instance.
     *
     * For the API, the activity instance is always set in the query string in the request object, so this
     * method throws an exception.
     *
     * @throws \Exception
     */
    public function clearActivityInstance()
    {
        $this->request->query->remove('a');
        $this->request->overrideGlobals();
    }
}
