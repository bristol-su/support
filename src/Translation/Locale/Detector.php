<?php

namespace BristolSU\Support\Translation\Locale;

class Detector
{

    /**
     * @var DetectionStrategy
     */
    private DetectionStrategy $detectionStrategy;

    public function __construct(DetectionStrategy $detectionStrategy)
    {
        $this->detectionStrategy = $detectionStrategy;
    }

    public function lang()
    {
        return $this->detectionStrategy->detect();
    }

}
