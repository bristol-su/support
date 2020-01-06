<?php

namespace BristolSU\Support\Filters\Contracts;

/**
 * Repository for retrieving and updating filter instances
 */
interface FilterInstanceRepository
{

    /**
     * Create a filter instance
     * 
     * The attributes should be
     * [
     *      'alias' => 'alias_of_the_filter',
     *      'name' => 'Name of the filter',
     *      'settings' => [], // Settings for the filter
     *      'logic_id' => '', // ID of the logic row
     *      'logic_type' => 'all_true', 'any_true', 'all_false', 'any_false' // How the filter should be tested in the logic group
     * ]
     * 
     * @param array $attributes Attributes
     * @return FilterInstance
     */
    public function create($attributes = []);

    /**
     * Get all filter instances
     * 
     * @return FilterInstance[]
     */
    public function all();
}
