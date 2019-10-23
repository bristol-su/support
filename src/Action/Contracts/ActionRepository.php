<?php

namespace BristolSU\Support\Action\Contracts;

/**
 * Interface ActionRepository
 * @package BristolSU\Support\Action\Contracts
 */
interface ActionRepository
{

    public function all();

    /**
     * @param $class
     * @return mixed
     */
    public function fromClass($class);

}
