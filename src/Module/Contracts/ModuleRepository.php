<?php


namespace BristolSU\Support\Module\Contracts;


/**
 * Interface ModuleRepository
 * @package BristolSU\Support\Module\Contracts
 */
interface ModuleRepository
{
    public function all();

    /**
     * @param $alias
     * @return mixed
     */
    public function findByAlias($alias);
}
