<?php


namespace BristolSU\Support\Module\Contracts;


interface ModuleFactory
{
    public function fromAlias(string $alias): Module;
}