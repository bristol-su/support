<?php

namespace BristolSU\Support\Filters\Contracts;

interface FilterManager
{

    public function register($alias, $class);

    public function getAll();

    public function getClassFromAlias($alias);
}