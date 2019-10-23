<?php


namespace BristolSU\Support\Logic\Contracts;


/**
 * Interface LogicRepository
 * @package BristolSU\Support\Logic\Contracts
 */
interface LogicRepository
{

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    public function all();
}
