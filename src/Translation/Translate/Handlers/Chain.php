<?php

namespace BristolSU\Support\Translation\Translate\Handlers;

use BristolSU\Support\Translation\Translate;
use BristolSU\Support\Translation\Translate\Handler;

class Chain extends Handler
{

    public function translate(string $line, string $lang): ?string
    {
        foreach($this->getConfig('queue', []) as $translator) {
            $translation = Translate::driver($translator)->translate($line, $lang);
            if($translation !== null) {
                return $translation;
            }
        }
    }
}
