<?php


namespace BristolSU\Support\Filters;


use BristolSU\Support\Filters\Contracts\FilterInstanceRepository as FilterInstanceRepositoryContract;

class FilterInstanceRepository implements FilterInstanceRepositoryContract
{
    public function create($attributes = [])
    {
        return FilterInstance::create($attributes);
    }
}
