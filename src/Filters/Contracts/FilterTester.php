<?php

namespace BristolSU\Support\Filters\Contracts;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use Exception;

/**
 * Test filters for the result
 */
interface FilterTester
{

    /**
     * Tests a filter and returns the result.
     * 
     * This function will test the filter instance with the given model. 
     * 
     * @param FilterInstance $filterInstance Filter instance to test
     * @param User|Group|Role $model Model to test the filter instance up
     * @return bool If the filter passes
     * 
     * @throws Exception If the model type does not match the filter type
     */
    public function evaluate(FilterInstance $filterInstance, $model): bool;

}
