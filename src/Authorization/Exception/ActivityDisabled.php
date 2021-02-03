<?php

namespace BristolSU\Support\Authorization\Exception;

use BristolSU\Support\Activity\Activity;

class ActivityDisabled extends \Exception
{
    /**
     * The activity that was disabled.
     * @var Activity
     */
    protected $activity;

    /**
     * Create an instance of the exception.
     *
     * @param Activity $activity
     * @return ActivityDisabled
     */
    public static function fromActivity(Activity $activity)
    {
        $exception = new self();
        $exception->setActivity($activity);

        return $exception;
    }

    /**
     * Set the activity that caused the exception to be thrown.
     *
     * @param Activity $activity
     */
    public function setActivity(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Set the activity that caused the exception to be thrown.
     *
     * @return Activity
     */
    public function activity()
    {
        return $this->activity;
    }
}
