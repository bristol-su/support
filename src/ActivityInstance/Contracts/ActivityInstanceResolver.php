<?php


namespace BristolSU\Support\ActivityInstance\Contracts;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;

/**
 * Resolve and persist the current Activity Instance.
 */
interface ActivityInstanceResolver
{
    /**
     * Persist an Activity Instance.
     *
     * Save the Activity Instance for the current session. The activity instance should
     * be able to be retrieved over many requests when set.
     *
     * @param ActivityInstance $activityInstance
     *
     */
    public function setActivityInstance(ActivityInstance $activityInstance);

    /**
     * Get the current activity instance.
     *
     * Get the activity instance set with setActivityInstance. Should thrown an exception if not found.
     *
     * @throws NotInActivityInstanceException
     * @return ActivityInstance
     */
    public function getActivityInstance(): ActivityInstance;

    /**
     * Clear the current activity instance.
     *
     * Remove the activity instance from persistence.
     *
     */
    public function clearActivityInstance();
}
