<?php

namespace BristolSU\Support\Activity\Exception;

use Exception;

/**
 * Class ActivityRequiresRole
 * @package BristolSU\Support\Activity\Exception
 */
class ActivityRequiresRole extends Exception
{
    /**
     * @var
     */
    private $activity;

    /**
     * ActivityRequiresRole constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param $activity
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null, $activity)
    {
        parent::__construct($message, $code, $previous);
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }
}