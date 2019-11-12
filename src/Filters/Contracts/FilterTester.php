<?php


namespace BristolSU\Support\Filters\Contracts;


use BristolSU\Support\Control\Contracts\Models\Group;
use BristolSU\Support\Control\Contracts\Models\Role;
use \BristolSU\Support\Control\Contracts\Models\User;

/**
 * Interface FilterTester
 * @package BristolSU\Support\Filters\Contracts
 */
interface FilterTester
{
    
    public function evaluate(FilterInstance $filterInstance, $model): bool;

}
