<?php

namespace BristolSU\Support\Translation\Locale\Strategies;

use BristolSU\Support\Translation\Locale\DetectionStrategy;
use Illuminate\Http\Request;

class HeaderDetectionStrategy extends DetectionStrategy
{

    /**
     * @var Request
     */
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the locale requested by the user
     *
     * Return the ISO-639-1 Code for the language, or null if locale not found
     *
     * @return string|null
     */
    protected function getCurrentLocale(): ?string
    {
        return $this->request->getPreferredLanguage();
    }
}
