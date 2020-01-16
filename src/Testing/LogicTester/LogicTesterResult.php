<?php

namespace BristolSU\Support\Testing\LogicTester;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use Illuminate\Foundation\Testing\Assert;

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
     * Holds the user/group/role combinations which must be called
     *
     * @var array Array of arrays: [$user, $group, $role]
     */
    private $required = [];

    /**
     * Should the evaluation always return a certain value?
     * 
     * @var null|bool 
     */
    private $overrideResult;
    
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
        $this->passes[] = $this->parseArguments($userModel, $groupModel, $roleModel);
        return $this;
    }

    /**
     * Return an array of the user id, group id and role id.
     *
     * @param User|null $userModel User model
     * @param Group|null $groupModel Group model
     * @param Role|null $roleModel Role model
     * @return array
     */
    private function parseArguments($userModel = null, $groupModel = null, $roleModel = null)
    {
        return [($userModel instanceof User?$userModel->id():null),($groupModel instanceof Group?$groupModel->id():null),($roleModel instanceof Role?$roleModel->id():null)];
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
        $this->fails[] = $this->parseArguments($userModel, $groupModel, $roleModel);
        return $this;
    }

    /**
     * The given combination must be called for the assertion to pass
     * 
     * @param User|null $userModel User model
     * @param Group|null $groupModel Group model
     * @param Role|null $roleModel Role model
     * @return $this
     */
    public function shouldBeCalled($userModel = null, $groupModel = null, $roleModel = null)
    {
        $this->required[] = $this->parseArguments($userModel, $groupModel, $roleModel);
        return $this;
    }

    /**
     * Set the default value if a user/group/role are given that haven't been previously registered
     *
     * @param bool $value Default value to return
     * @return LogicTesterResult
     */
    public function otherwise(bool $value = true)
    {
        $this->default = $value;
        return $this;
    }

    /**
     * Should the result of the evaluation always be true?
     */
    public function alwaysPass()
    {
        $this->overrideResult = true;
    }

    /**
     * Should the result of the evaluation always be false?
     */
    public function alwaysFail()
    {
        $this->overrideResult = false;
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
        $args = $this->parseArguments($userModel, $groupModel, $roleModel);
        $this->required = array_filter($this->required, function($parameters) use ($args) {
            return $parameters !== $args;
        });
     
        if($this->overrideResult !== null) {
            return $this->overrideResult;
        }
        
        if(in_array($args, $this->passes)) {
            return true;
        }
        if(in_array($args, $this->fails)) {
            return false;
        }
        return $this->default;
    }

    /**
     * Test the required assertions have all been called
     */
    public function __destruct()
    {
        Assert::assertCount(0, $this->required, 'Not all logic tests have been called.');
    }

}