<?php


namespace BristolSU\Support\Logic\Contracts;


/**
 * Interface Specification
 * @package BristolSU\Support\Logic\Contracts
 */
interface Specification
{

    /**
     * @return bool
     */
    public function isSatisfied() : bool;

}
