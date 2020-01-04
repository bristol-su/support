<?php


namespace BristolSU\Support\Filters\Contracts;


use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use \BristolSU\ControlDB\Contracts\Models\User;

/**
 * Interface FilterTester
 * @package BristolSU\Support\Filters\Contracts
 */
interface FilterTester
{
    
    public function evaluate(FilterInstance $filterInstance, $model): bool;

}
