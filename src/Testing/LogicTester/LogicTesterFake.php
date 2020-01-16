<?php

namespace BristolSU\Support\Testing\LogicTester;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;

/**
 * A Logic Tester implementation used for manually setting logic group results
 */
class LogicTesterFake implements LogicTester
{

    /**
     * Holds the logic tester results
     * 
     * @var LogicTesterResult[]
     */
    private $results = [];

    /**
     * Create a logic tester for a specific logic group
     * 
     * @param Logic $logic
     * @return LogicTesterResult
     */
    public function forLogic(Logic $logic)
    {
        if(!array_key_exists($logic->id, $this->results)) {
            $this->results[$logic->id] = new LogicTesterResult();
        }
        return $this->results[$logic->id];
    }

    public function bind()
    {
        app()->instance(LogicTester::class, $this);
    }


    /**
     * See if the given credentials have a predetermined result for the given logic
     * 
     * @param Logic $logic Logic to test
     * @param User|null $userModel User to test against
     * @param Group|null $groupModel Group to test against
     * @param Role|null $roleModel Role to test against
     * @return bool
     */
    public function evaluate(Logic $logic, $userModel = null, $groupModel = null, $roleModel = null): bool
    {
        if(!array_key_exists($logic->id, $this->results)) {
            return false;
        }
        
        return $this->results[$logic->id]->evaluate($userModel, $groupModel, $roleModel);
    }
}