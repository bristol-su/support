<?php

namespace BristolSU\Support\Translation\Locale;

use Illuminate\Contracts\Foundation\Application;

abstract class DetectionStrategy
{

    private ?DetectionStrategy $successor = null;

    public function setNext(DetectionStrategy $strategy)
    {
        $this->successor = $strategy;
    }

    /**
     * This approach by using a template method pattern ensures you that
     * each subclass will not forget to call the successor
     */
    final public function detect(): ?string
    {
        $processed = $this->getCurrentLocale();

        if ($processed === null && $this->successor !== null) {
            // the request has not been processed by this handler => see the next
            $processed = $this->successor->detect();
        }

        return $processed;
    }

    /**
     * Get the locale requested by the user
     *
     * Return the ISO-639-1 Code for the language, or null if locale not found
     *
     * @return string|null
     */
    abstract protected function getCurrentLocale(): ?string;

}
