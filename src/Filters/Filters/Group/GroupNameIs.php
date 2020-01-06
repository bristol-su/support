<?php

namespace BristolSU\Support\Filters\Filters\Group;

use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;

/**
 * Test the group name matches
 */
class GroupNameIs extends GroupFilter
{


    /**
     * Get possible options as an array
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'Group Name' => ''
        ];
    }

    /**
     * Test if the group has the given name
     *
     * @param string $settings [ 'Group Name' => 'name' ]
     *
     * @return bool
     */
    public function evaluate($settings): bool
    {
        return strtoupper($this->group()->name()) === strtoupper($settings['Group Name']);
    }

    /**
     * Get the filter name
     * 
     * @return string Name
     */
    public function name()
    {
        return 'Group name is exactly';
    }

    /**
     * Filter description
     * 
     * @return string Description
     */
    public function description()
    {
        return 'Group name exactly matches the name given';
    }

    /**
     * Filter alias
     * 
     * @return string Alias
     */
    public function alias()
    {
        return 'group_name_is';
    }
}
