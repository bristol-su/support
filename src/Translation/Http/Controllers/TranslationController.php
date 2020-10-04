<?php

namespace BristolSU\Support\Translation\Http\Controllers;

use BristolSU\Support\Translation\Locale\Detect;
use BristolSU\Support\Translation\Translate;
use Illuminate\Http\Request;

class TranslationController
{

    public function translate(Request $request)
    {
        $lang = \BristolSU\Support\Translation\Detect::lang();
        if ($lang === null) {
            return 'no';
        }
        return Translate::translate(
            $request->input('line'),
            $lang
        ) ?? $request->input('line');
    }

}
