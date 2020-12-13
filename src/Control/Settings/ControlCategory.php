<?php

namespace BristolSU\Support\Control\Settings;

use BristolSU\Support\Settings\Definition\Category;

class ControlCategory extends Category
{

    public function name(): string
    {
        return 'Control';
    }

    public function icon(): string
    {
        return 'person';
    }
}
