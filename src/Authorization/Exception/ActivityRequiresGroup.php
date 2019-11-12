<?php

namespace BristolSU\Support\Authorization\Exception;

use Throwable;

/**
 * Class ActivityRequiresGroup
 * @package BristolSU\Support\Activity\Exception
 */
class ActivityRequiresGroup extends \Exception
{

    /**
     * @var
     */
    private $activity;

    /**
     * ActivityRequiresGroup constructor.
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