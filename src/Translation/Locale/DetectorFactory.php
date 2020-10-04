<?php

namespace BristolSU\Support\Translation\Locale;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;

class DetectorFactory
{

    /**
     * @var Application
     */
    private Application $application;
    /**
     * @var DetectionStrategyStore
     */
    private DetectionStrategyStore $detectionStrategyStore;

    public function __construct(Application $application, DetectionStrategyStore $detectionStrategyStore)
    {
        $this->application = $application;
        $this->detectionStrategyStore = $detectionStrategyStore;
    }

    public function create(): Detector
    {
        return new Detector(
          $this->getChain()
        );
    }

    public function getChain(): DetectionStrategy
    {
        $strategies = $this->detectionStrategyStore->all();

        if (empty($strategies)) {
            throw new Exception('No locale detection strategies registered');
        }

        for ($i = 0; $i < (count($strategies)); $i++) {
            $strategies[$i] = app($strategies[$i]);
        }

        for ($i = 0; $i < (count($strategies) - 1); $i++) {
            $strategies[$i]->setNext($strategies[$i + 1]);
        }
        return $strategies[0];
    }

    public function __call($name, $arguments)
    {
        return $this->create()->{$name}(...$arguments);
    }

}
