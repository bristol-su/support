<?php

namespace BristolSU\Support\Activity\Exception;

use Exception;

class ActivityRequiresRole extends Exception
{
    private $activity;

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