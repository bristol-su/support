<?php

namespace BristolSU\Support\Testing\LogicTester;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;

/**
 * Handles collecting and evaluating collections of user, group or role models
 */
class LogicTesterResult
{

    /**
     * Holds the user/group/role combinations which are in a logic group
     * 
     * @var array Array of arrays: [$user, $group, $role] 
     */
    private $passes = [];

    /**
     * Holds the user/group/role combinations which are not in a logic group
     *
     * @var array Array of arrays: [$user, $group, $role]
     */
    private $fails = [];

    /**
     * Holds the default return value if the user/group/role combination have not been given a result
     *
     * @var bool
     */
    private $default = false;

    /**
     * The given combination of user, group and role should return true when tested
     * 
     * @param User|null $userModel User model 
     * @param Group|null $groupModel Group model
     * @param Role|null $roleModel Role model
     * @return $this
     */
    public function pass($userModel = null, $groupModel = null, $roleModel = null)
    {
        $this->passes[] = [$userModel, $groupModel, $roleModel];
        return $this;
    }

    /**
     * The given combination of user, group and role should return false when tested
     *
     * @param User|null $userModel User model
     * @param Group|null $groupModel Group model
     * @param Role|null $roleModel Role model
     * @return $this
     */
    public function fail($userModel = null, $groupModel = null, $roleModel = null)
    {
        $this->fails[] = [$userModel, $groupModel, $roleModel];
        return $this;
    }

    /**
     * Set the default value if a user/group/role are given that haven't been previously registered
     * 
     * @param bool $value Default value to return
     */
    public function otherwise(bool $value = true)
    {
        $this->default = $value;
    }

    /**
     * Evaluate a user/group/role combination
     *
     * @param User|null $userModel User model
     * @param Group|null $groupModel Group model
     * @param Role|null $roleModel Role model
     * @return bool
     */
    public function evaluate($userModel = null, $groupModel = null, $roleModel = null): bool
    {
        if(in_array([$userModel, $groupModel, $roleModel], $this->passes)) {
            return true;
        }
        if(in_array([$userModel, $groupModel, $roleModel], $this->fails)) {
            return false;
        }
        return $this->default;
    }
    
}