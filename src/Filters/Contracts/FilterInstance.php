<?php

namespace BristolSU\Support\Filters\Contracts;

/**
 * An instance of a filter
 */
interface FilterInstance
{
    /**
     * Name of the filter instance
     * 
     * @return string
     */
    public function name();

    /**
     * Alias of the filter instance
     *
     * @return string
     */
    public function alias();

    /**
     * Settings for the filter instance
     *
     * @return array
     */
    public function settings();

    /**
     * What the filter is for.
     * 
     * This should return either user, group or role
     *
     * @return string
     */
    public function for();
}
