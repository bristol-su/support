<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterInstanceRepository as FilterInstanceRepositoryContract;

/**
 * Class FilterInstanceRepository
 * @package BristolSU\Support\Filters
 */
class FilterInstanceRepository implements FilterInstanceRepositoryContract
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = [])
    {
        return FilterInstance::create($attributes);
    }
    
    public function all()
    {
        return FilterInstance::all();
    }
}
