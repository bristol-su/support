<?php


namespace BristolSU\Support\Module\Contracts;


interface ModuleRepository
{
    public function all();

    public function findByAlias($alias);
}
