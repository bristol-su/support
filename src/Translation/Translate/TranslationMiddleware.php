<?php

namespace BristolSU\Support\Translation\Translate;

abstract class TranslationMiddleware
{

    public function intercept(string $line, string $lang, Handler $handler);

    // Methods to catch and return line, and one to save it. 
}
