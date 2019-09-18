<?php

namespace BristolSU\Support\Filters\Contracts;

interface FilterManager
{

    public function getAll();

    public function getClassFromAlias($alias);
}