<?php

namespace BristolSU\Support\Testing\ActivityInstance;

use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Activity Instance Resolver using laravel's Auth framework.
 */
class LaravelAuthActivityInstanceResolver implements ActivityInstanceResolver
{
    /**
     * Laravel Auth Factory
     *
     * Used for saving and retrieving activity instances.
     *
     * @var AuthFactory
     */
    private $auth;

    /**
     * Initialise the activity instance resolver
     *
     * @param AuthFactory $auth
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Set the activity instance
     *
     * @param ActivityInstance $activityInstance Activity instance to set.
     */
    public function setActivityInstance(ActivityInstance $activityInstance)
    {
        $this->auth->guard('activity-instance')->login($activityInstance);
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
        if ($this->auth->guard('activity-instance')->check()) {
            return $this->auth->guard('activity-instance')->user();
        }
        throw new NotInActivityInstanceException('No activity instance found', 404);
    }

    /**
     * Clear the activity instance information.
     */
    public function clearActivityInstance()
    {
        $this->auth->guard('activity-instance')->logout();
    }
}