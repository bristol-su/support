<?php

namespace BristolSU\Support\Filters\Contracts;

/**
 * Interface FilterManager
 * @package BristolSU\Support\Filters\Contracts
 */
interface FilterManager
{

    /**
     * @param $alias
     * @param $class
     * @return mixed
     */
    public function register($alias, $class);

    public function getAll();

    /**
     * @param $alias
     * @return mixed
     */
    public function getClassFromAlias($alias);
}