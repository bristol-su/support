<?php

namespace BristolSU\Support\Theme\Settings;

use BristolSU\Support\Settings\Definition\Group;

class ThemeGroup extends Group
{

    /**
     * The key of the group
     *
     * @return string
     */
    public function key(): string
    {
        return 'appearance.theme';
    }

    /**
     * The name for the group
     *
     * @return string
     */
    public function name(): string
    {
        return 'Theme';
    }

    /**
     * A description for the group
     *
     * @return string
     */
    public function description(): string
    {
        return 'Edit the theme your site uses';
    }
}
