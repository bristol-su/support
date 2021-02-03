<?php

namespace BristolSU\Support\Authorization\Exception;

use BristolSU\Support\Activity\Activity;
use Exception;
use Throwable;

/**
 * Exception to indicate an activity could not be accessed with the current credentials.
 */
class ActivityRequiresParticipant extends Exception
{
    /**
     * Holds the activity that was accessed.
     *
     * @var Activity
     */
    private $activity;

    /**
     * Initialise the exception.
     *
     * @param string $message Message for the exception
     * @param int $code Status code
     * @param Throwable|null $previous Previous exception
     * @param Activity $activity Activity that was accessed
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null, $activity = null)
    {
        parent::__construct($message, $code, $previous);
        $this->activity = $activity;
    }

    /**
     * Create the exception with a given activity.
     *
     * @param Activity $activity Activity that was accessed
     * @param string $message Message for the exception
     * @param int $code Status code
     *
     * @return ActivityRequiresParticipant
     */
    public static function createWithActivity(Activity $activity, string $message = '', int $code = 0)
    {
        return new self($message, $code, null, $activity);
    }

    /**
     * Get the activity.
     *
     * @return Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
