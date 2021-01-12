<?php

namespace BristolSU\Support\Theme\Settings;

use BristolSU\Support\Settings\Definition\Category;

class AppearanceCategory extends Category
{

    /**
     * The key of the category
     *
     * @return string
     */
    public function key(): string
    {
        return 'appearance';
    }

    /**
     * The name for the category
     *
     * @return string
     */
    public function name(): string
    {
        return 'Appearance';
    }

    /**
     * A description for the category
     *
     * @return string
     */
    public function description(): string
    {
        return 'Change how the site looks and feels';
    }
}
