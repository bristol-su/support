<?php

namespace BristolSU\Support\Testing\ActivityInstance;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Activity Instance Resolver using laravel's Auth framework.
 */
class SessionActivityInstanceResolver implements ActivityInstanceResolver
{
    /**
     * @var Session
     */
    private Session $session;
    /**
     * @var ActivityInstanceRepository
     */
    private ActivityInstanceRepository $activityInstanceRepository;

    public function __construct(Session $session, ActivityInstanceRepository $activityInstanceRepository)
    {
        $this->session = $session;
        $this->activityInstanceRepository = $activityInstanceRepository;
    }

    /**
     * Set the activity instance
     *
     * @param ActivityInstance $activityInstance Activity instance to set.
     */
    public function setActivityInstance(ActivityInstance $activityInstance)
    {
        $this->session->put('activity-instance', $activityInstance->id);
    }

    /**
     *
     * Get the activity instance
     *
     * @return ActivityInstance Activity instance set through setActivityInstance
     *
     * @throws NotInActivityInstanceException If the activity instance is not set.
     */
    public function getActivityInstance(): ActivityInstance
    {
        if($this->session->has('activity-instance')) {
            try {
                return $this->activityInstanceRepository->getById(
                    (int) $this->session->get('activity-instance')
                );
            } catch (ModelNotFoundException $e) {
                $errorMessage = sprintf('No activity instance with an id %s found', $this->session->get('activity-instance'));
            }
        }

        throw new NotInActivityInstanceException($errorMessage ?? 'No activity instance found', 404);
    }

    /**
     * Clear the activity instance information.
     */
    public function clearActivityInstance()
    {
        $this->session->remove('activity-instance');
    }
}
