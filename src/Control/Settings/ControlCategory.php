<?php

namespace BristolSU\Support\Control\Settings;

use BristolSU\Support\Settings\Definition\Category;

class ControlCategory extends Category
{
    /**
     * The key of the category.
     *
     * @return string
     */
    public function key(): string
    {
        return 'control';
    }

    /**
     * The name for the category.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Control';
    }

    /**
     * A description for the category.
     *
     * @return string
     */
    public function description(): string
    {
        return 'Configure how Control works';
    }
}
