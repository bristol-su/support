<?php

namespace BristolSU\Support\Module\Contracts;

/**
 * Interface ModuleManager
 * @package BristolSU\Support\Module\Contracts
 */
interface ModuleManager
{
    /**
     * @param $alias
     * @return mixed
     */
    public function register($alias);

    public function aliases();

    /**
     * @param string $alias
     * @return bool
     */
    public function exists(string $alias): bool;
}