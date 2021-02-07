<?php

namespace BristolSU\Support\Control\Settings\Attributes;

use BristolSU\Support\Settings\Definition\Group;

class AttributeGroup extends Group
{
    /**
     * The key of the group.
     *
     * @return string
     */
    public function key(): string
    {
        return 'control.data-fields';
    }

    /**
     * The name for the group.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Data Fields';
    }

    /**
     * A description for the group.
     *
     * @return string
     */
    public function description(): string
    {
        return 'Additional information you collect about your users, groups or roles';
    }
}
