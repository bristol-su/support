<?php


namespace BristolSU\Support\Filters\Contracts;


/**
 * Interface FilterInstanceRepository
 * @package BristolSU\Support\Filters\Contracts
 */
interface FilterInstanceRepository
{

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = []);

    public function all();
}
