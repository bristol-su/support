<?php


namespace BristolSU\Support\Filters\Contracts;


/**
 * Interface FilterFactory
 * @package BristolSU\Support\Filters\Contracts
 */
interface FilterFactory
{

    /**
     * @param $className
     * @return mixed
     */
    public function createFilterFromClassName($className);

}
