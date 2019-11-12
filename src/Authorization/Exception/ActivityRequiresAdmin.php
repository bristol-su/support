<?php

namespace BristolSU\Support\Authorization\Exception;

class ActivityRequiresAdmin extends \Exception
{

    /**
     * @var
     */
    private $activity;

    /**
     * ActivityRequiresAdmin constructor.
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