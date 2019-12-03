<?php


namespace BristolSU\Support\Filters\Filters\Group;


use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;

/**
 * Class GroupEmailIs
 * @package BristolSU\Support\Filters\Filters
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
     * Test if the filter passes
     *
     * @param string $settings Key of the chosen option
     *
     * @return bool
     */
    public function evaluate($settings): bool
    {
        return strtoupper($this->group()->name()) === strtoupper($settings['Group Name']);
    }

    /**
     * @return mixed|string
     */
    public function name()
    {
        return 'Group name is exactly';
    }

    /**
     * @return mixed|string
     */
    public function description()
    {
        return 'Group name exactly matches the name given';
    }

    /**
     * @return mixed|string
     */
    public function alias()
    {
        return 'group_name_is';
    }
}
